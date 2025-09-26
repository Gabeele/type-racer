<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return Game::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required'],
        ]);

        return Game::create($data);
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
