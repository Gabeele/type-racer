<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index()
    {
        return Game::all();
    }

    public function create(GameService $gameService)
    {
        $game = $gameService
            ->create()
            ->attachUser(auth()->user())
            ->getGame();

        return Inertia::render('Game/Lobby', ['game' => $game, 'players' => $game->users()->get()]);
    }

    // TODO extract out
    public function join(Request $request, GameService $gameService)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $game = Game::where('code', $data['code'])->firstOrFail();

        $gameService
            ->attachUser($game, auth()->user());

        return Inertia::render('Game/Lobby', [
            'game' => $game,
            'players' => $game->users()->get(),
        ]);
    }


    public function show(Game $game)
    {
        return $game;
    }

    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'code' => ['required'],
        ]);

        $game->update($data);

        return $game;
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json();
    }
}
