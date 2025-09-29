<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GithubAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::name('games.')
    ->prefix('games')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::post('/create', [GameController::class, 'create'])->name('create');
        Route::post('/join', [GameController::class, 'join'])->name('join');
        Route::get('/{game}/start', [GameController::class, 'start'])->name('start');
        Route::get('/{game}', [GameController::class, 'show'])->name('show');
    });

Route::name('auth.')
    ->group(function () {
        Route::get('/auth/github/redirect', [GithubAuthenticationController::class, 'redirect'])->name('redirect');
        Route::get('/auth/github/callback', [GithubAuthenticationController::class, 'callback'])->name('callback');
    });


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
