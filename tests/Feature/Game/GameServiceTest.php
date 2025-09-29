<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_persists_game_with_six_char_unique_code_and_is_chainable()
    {
        // Pre-existing game to ensure new code differs (uniqueness loop)
        $existing = Game::factory()->create(['code' => 'ABC123']);

        $service = new GameService();
        $returned = $service->create();

        $this->assertSame($service, $returned);

        $game = $service->getGame();
        $this->assertInstanceOf(Game::class, $game);
        $this->assertNotNull($game->id);
        $this->assertIsString($game->code);
        $this->assertSame(6, strlen($game->code));
        $this->assertNotSame($existing->code, $game->code);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'code' => $game->code,
        ]);
    }

    public function test_attach_user_links_user_on_pivot_and_is_chainable()
    {
        $user = User::factory()->create();

        $service = new GameService();
        $service->create();
        $game = $service->getGame();

        $returned = $service->attachUser($user);
        $this->assertSame($service, $returned);

        $this->assertTrue(
            $game->users()->whereKey($user->id)->exists()
        );

        $this->assertDatabaseHas('game_user', [
            'game_id' => $game->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_get_game_returns_same_instance_created_by_service()
    {
        $service = new GameService();
        $service->create();
        $game = $service->getGame();

        $this->assertInstanceOf(Game::class, $game);
        $this->assertNotNull($game->id);
        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    public function test_multiple_creations_produce_distinct_codes()
    {
        $serviceA = new GameService();
        $serviceA->create();
        $codeA = $serviceA->getGame()->code;

        $serviceB = new GameService();
        $serviceB->create();
        $codeB = $serviceB->getGame()->code;

        $this->assertNotSame($codeA, $codeB);
        $this->assertSame(6, strlen($codeA));
        $this->assertSame(6, strlen($codeB));
    }
}
