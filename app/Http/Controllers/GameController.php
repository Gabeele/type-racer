<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

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

        return redirect()->route('games.show', $game);
    }

    // TODO extract out
    public function join(Request $request, GameService $gameService)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $game = Game::where('code', $data['code'])->firstOrFail();

        $gameService
            ->attachUser(auth()->user());

        return redirect()->route('games.show', $game);
    }


    public function show(Game $game)
    {
        return Inertia::render('Game/Lobby', ['game' => $game, 'players' => $game->users()->get()]);
    }

    public function start(Game $game)
    {
        if ($game->status === GameStatus::PLAYING) {
            return response()->json(['message' => 'Game is already started!'], Response::HTTP_CONFLICT);
        } else if ($game->status === GameStatus::ENDED) {
            return response()->json(['message' => 'Game has ended.'], Response::HTTP_CONFLICT);
        }

        $game->update(['status' => GameStatus::PLAYING]);

        return response()->json(['game' => $game]);
    }
}
