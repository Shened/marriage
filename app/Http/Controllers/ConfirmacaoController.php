<?php

namespace App\Http\Controllers;

use App\Models\Confirmacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfirmacaoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'apelido' => 'required|string|max:255',
            'idade' => 'required|integer|min:0|max:120',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'presenca' => 'required|in:sim,nao',
            'temParceiro' => 'boolean',
            'temFilhos' => 'boolean',
            'restricoes' => 'nullable|string',
            'parceiro' => 'nullable|array',
            'parceiro.nome' => 'required_if:temParceiro,true|string|max:255',
            'parceiro.idade' => 'nullable|integer|min:0|max:120',
            'filhos' => 'nullable|array',
            'filhos.*.nome' => 'required|string|max:255',
            'filhos.*.idade' => 'nullable|integer|min:0|max:18',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'apelido.required' => 'O apelido é obrigatório.',
            'idade.required' => 'A idade é obrigatória.',
            'idade.min' => 'A idade deve ser maior que 0.',
            'idade.max' => 'A idade deve ser menor que 120.',
            'email.email' => 'Email inválido.',
            'presenca.required' => 'Por favor, confirme sua presença.',
            'presenca.in' => 'Valor de presença inválido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Criar confirmação principal
            $confirmacao = Confirmacao::create([
                'nome' => $request->nome,
                'apelido' => $request->apelido,
                'idade' => $request->idade,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'presenca' => $request->presenca,
                'tem_parceiro' => $request->temParceiro ?? false,
                'tem_filhos' => $request->temFilhos ?? false,
                'restricoes' => $request->restricoes,
                'ip_address' => $request->ip(),
            ]);

            // Criar parceiro se houver
            if ($request->temParceiro && !empty($request->parceiro['nome'])) {
                $confirmacao->parceiro()->create([
                    'nome' => $request->parceiro['nome'],
                    'idade' => $request->parceiro['idade'] ?? null,
                ]);
            }

            // Criar filhos se houver
            if ($request->temFilhos && !empty($request->filhos)) {
                foreach ($request->filhos as $filho) {
                    if (!empty($filho['nome'])) {
                        $confirmacao->filhos()->create([
                            'nome' => $filho['nome'],
                            'idade' => $filho['idade'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Confirmação registrada com sucesso!',
                'data' => [
                    'id' => $confirmacao->id,
                    'nome' => $confirmacao->nome,
                    'presenca' => $confirmacao->presenca,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar confirmação. Por favor, tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
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
        if ($request->has('busca') && !empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
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
            'data' => $confirmacoes
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
            'data' => $confirmacao
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
            'data' => $stats
        ]);
    }

    public function destroy(Confirmacao $confirmacao)
    {
        $confirmacao->delete();
        return response()->json(['success' => true]);
    }
}
