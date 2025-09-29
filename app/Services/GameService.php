<?php

namespace App\Services;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Str;

class GameService
{
    protected Game $game;

    public function __construct()
    {
    }

    public function create(): GameService
    {
        $code = $this->generateCode();

        $this->game = $this->createGame($code);

        return $this;
    }

    private function generateCode(): string
    {
        do {
            $code = Str::random(6);
        } while (Game::where('code', $code)->exists());

        return $code;
    }


    private function createGame(string $code): Game
    {
        return Game::create([
            'code' => $code,
            'status' => GameStatus::LOBBY,
        ]);
    }

    public function attachUser(User $user): GameService
    {
        $this->game->users()->attach($user);

        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }


}
