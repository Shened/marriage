<?php

namespace App\Http\Controllers;

use App\Models\Confirmacao;
use App\Notifications\NovaConfirmacaoPresenca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ConfirmacaoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'apelido' => 'required|string|max:255',
            'idade' => 'required|integer|min:0|max:120',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'presenca' => 'required|in:sim,não',
            'temParceiro' => 'boolean',
            'temFilhos' => 'boolean',
            'restricoes' => 'nullable|string',
            'parceiro' => 'nullable|array',
            'parceiro.nome' => 'required_if:temParceiro,1|string|max:255',
            'parceiro.idade' => 'nullable|integer|min:0|max:120',
            'filhos' => 'nullable|array',
            'filhos.*.nome' => 'required|string|max:255',
            'filhos.*.idade' => 'nullable|integer|min:0|max:18',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $confirmacao = Confirmacao::create([
                'nome' => $data['nome'],
                'apelido' => $data['apelido'],
                'idade' => $data['idade'],
                'email' => $data['email'] ?? null,
                'telefone' => $data['telefone'] ?? null,
                'presenca' => $data['presenca'],
                'tem_parceiro' => (bool) ($data['temParceiro'] ?? false),
                'tem_filhos' => (bool) ($data['temFilhos'] ?? false),
                'restricoes' => $data['restricoes'] ?? null,
                'ip_address' => $request->ip(),
            ]);

            if (!empty($data['parceiro']['nome'] ?? null)) {
                $confirmacao->parceiro()->create([
                    'nome' => $data['parceiro']['nome'],
                    'idade' => $data['parceiro']['idade'] ?? null,
                ]);
            }

            if (!empty($data['filhos'] ?? [])) {
                foreach ($data['filhos'] as $filho) {
                    if (!empty($filho['nome'])) {
                        $confirmacao->filhos()->create([
                            'nome' => $filho['nome'],
                            'idade' => $filho['idade'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            Notification::route('mail', 'joao.psm98@gmail.com')
                ->notify(new NovaConfirmacaoPresenca($confirmacao));

            return response()->json([
                'success' => true,
                'message' => 'Confirmação registada com sucesso!',
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar confirmação.',
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Confirmacao::with(['parceiro', 'filhos']);

        // Filtro por presença
        if ($request->has('presenca') && $request->presenca !== 'todos') {
            $query->where('presenca', $request->presenca);
        }

        // Busca por nome, email ou telefone
        if ($request->has('busca') && ! empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('telefone', 'like', "%{$busca}%")
                    ->orWhere('idade', 'like', "%{$busca}%")
                    ->orWhereRaw("CONCAT(nome, ' ', apelido) LIKE ?", "%{$busca}%")
                    ->orWhereRaw("CONCAT(nome, ' ', apelido) LIKE ?", "%{$busca}%")
                    ->orWhereRaw("CONCAT(nome, ' ', apelido, ' ', idade) LIKE ?", "%{$busca}%")
                    ->orWhereRaw("CONCAT(nome, ' ', idade) LIKE ?", "%{$busca}%")
                    ->orWhereRaw("CONCAT(apelido, ' ', idade) LIKE ?", "%{$busca}%")
                    ->orWhere('apelido', 'like', "%{$busca}%");
            });
        }

        $confirmacoes = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $confirmacoes,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Confirmacao $confirmacao)
    {
        $confirmacao->load(['parceiro', 'filhos']);

        return response()->json([
            'success' => true,
            'data' => $confirmacao,
        ]);
    }

    /**
     * Get statistics.
     */
    public function stats()
    {
        $stats = [
            'total' => Confirmacao::count(),
            'confirmados' => Confirmacao::where('presenca', 'sim')->count(),
            'nao_confirmados' => Confirmacao::where('presenca', 'nao')->count(),
            'com_parceiro' => Confirmacao::where('tem_parceiro', true)->count(),
            'com_filhos' => Confirmacao::where('tem_filhos', true)->count(),
            'total_parceiros' => DB::table('parceiros')->count(),
            'total_filhos' => DB::table('filhos')->count(),
        ];

        // Total de convidados (pessoas que confirmaram + parceiros + filhos)
        $confirmados = Confirmacao::where('presenca', 'sim')->count();
        $stats['total_convidados'] = $confirmados + $stats['total_parceiros'] + $stats['total_filhos'];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function destroy(Confirmacao $confirmacao)
    {
        $confirmacao->delete();

        return response()->json(['success' => true]);
    }
}
