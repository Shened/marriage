<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfirmacaoController;
use App\Http\Controllers\GalleryController;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))
        ->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/confirmacoes', [ConfirmacaoController::class, 'index'])->name('confirmacoes.index');
    Route::get('/confirmacoes/{confirmacao}', [ConfirmacaoController::class, 'show'])->name('confirmacoes.show');
    Route::get('/confirmacoes/stats/all', [ConfirmacaoController::class, 'stats'])->name('confirmacoes.stats.all');
    Route::delete('/confirmacoes/{confirmacao}', [ConfirmacaoController::class, 'destroy']);
});

// Rotas da galeria
Route::get('/galeria/{token}', [GalleryController::class, 'index'])
    ->whereUuid('token')
    ->name('gallery.index');

Route::post('/galeria/{token}/upload', [GalleryController::class, 'upload'])
    ->whereUuid('token')
    ->name('gallery.upload');

// Eliminar foto (APENAS ADMIN)
Route::delete('/galeria/{token}/photo/{photoId}', [GalleryController::class, 'deletePhoto'])
    ->whereUuid('token')
    ->name('gallery.photo.delete');

Route::post('/confirmacoes', [ConfirmacaoController::class, 'store']);

require __DIR__.'/auth.php';