<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    private function checkToken(string $token)
    {
        $isValid = $token === config('wedding.token') || $token === config('wedding.admin_token');
        abort_if(!$isValid, 403);
    }

    private function checkAdminToken(string $token)
    {
        abort_if($token !== config('wedding.admin_token'), 403);
    }

    private function isAdmin(string $token): bool
    {
        return $token === config('wedding.admin_token');
    }

    public function index(string $token)
    {
        $this->checkToken($token);

        $photos = Photo::latest()->get();

        return Inertia::render('Gallery/Index', [
            'photos' => $photos,
            'token' => $token,
            'isAdmin' => $this->isAdmin($token),
        ]);
    }

    public function upload(Request $request, string $token)
    {
        $this->checkToken($token);

        $request->validate([
            'photos.*' => 'required|image|max:15360', // até 15MB
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('gallery', 'public');
            Photo::create(['path' => $path]);
        }

        return back();
    }

    /**
     * Delete a photo (admin only)
     */
    public function deletePhoto(string $token, int $photoId)
    {
        // Verificar se é admin
        $this->checkAdminToken($token);

        $photo = Photo::findOrFail($photoId);

        // Deletar arquivo do storage
        Storage::disk('public')->delete($photo->path);

        // Deletar registro
        $photo->delete();

        return back();
    }
}