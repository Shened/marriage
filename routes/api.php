<?php

use App\Http\Controllers\ConfirmacaoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes


// Protected routes (require authentication)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/confirmacoes', [ConfirmacaoController::class, 'index']);
//     Route::get('/confirmacoes/{confirmacao}', [ConfirmacaoController::class, 'show']);
//     Route::get('/confirmacoes/stats/all', [ConfirmacaoController::class, 'stats']);
// });