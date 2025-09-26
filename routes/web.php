<?php

use App\Http\Controllers\GameController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('game', [GameController::class, 'create'])->name('game.create');
Route::post('game', [GameController::class, 'join'])->name('game.join');


// TODO Extract this stuff into a controller or service
Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::updateOrCreate(
        ['github_id' => $githubUser->id],
        [
            'name' => $githubUser->name ?? $githubUser->nickname,
            'email' => $githubUser->email,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken ?? null,
            'password' => null,
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
