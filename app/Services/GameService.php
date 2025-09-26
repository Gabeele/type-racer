<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Str;

class GameService
{
    public function __construct()
    {
    }

    public function create(): string
    {
        $code = $this->generateCode();
        return $this->createGame($code);
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
            'code' => $code
        ]);
    }


}
