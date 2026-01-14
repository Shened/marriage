<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', fn () =>
        Inertia::render('Auth/Login')
    )->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', fn () =>
        Inertia::render('Auth/Register')
    )->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', fn () =>
        Inertia::render('Auth/ForgotPassword')
    )->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', fn ($token) =>
        Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => request('email'),
        ])
    )->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/verify-email', fn () =>
        Inertia::render('Auth/VerifyEmail')
    )->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    )->middleware('throttle:6,1')
     ->name('verification.send');

    Route::get('/confirm-password', fn () =>
        Inertia::render('Auth/ConfirmPassword')
    )->name('password.confirm');

    Route::post('/confirm-password', [PasswordController::class, 'confirm']);

    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
