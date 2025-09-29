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

Route::post('/games/create', [GameController::class, 'create'])->name('games.create');
Route::post('/games/join', [GameController::class, 'join'])->name('games.join');
Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');

Route::get('/auth/github/redirect', [GithubAuthenticationController::class, 'redirect']);
Route::get('/auth/github/callback', [GithubAuthenticationController::class, 'callback']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
