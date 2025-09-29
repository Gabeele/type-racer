<?php

namespace Tests\Feature;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;


    public static function conflictGameStatuses(): array
    {
        return [
            [GameStatus::PLAYING, 'Game is already started!'],
            [GameStatus::ENDED, 'Game has ended.']
        ];
    }

    public function test_create_uses_game_service_and_redirects_to_show()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $game = Game::factory()->create();

        $service = Mockery::mock(GameService::class);
        $service->shouldReceive('create')->once()->andReturnSelf();
        $service->shouldReceive('attachUser')->once()->with($user)->andReturnSelf();
        $service->shouldReceive('getGame')->once()->andReturn($game);
        $this->app->instance(GameService::class, $service);

        $this->post(route('games.create'))
            ->assertRedirect(route('games.show', $game));

        Mockery::close();
    }

    public function test_join_with_valid_code_attaches_user_and_redirects_to_show()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $game = Game::factory()->create(['code' => 'ABCD1234']);

        $service = Mockery::mock(GameService::class);
        $service->shouldReceive('attachUser')->once()->with($user)->andReturnSelf();
        $this->app->instance(GameService::class, $service);

        $this->post(route('games.join'), ['code' => 'ABCD1234'])
            ->assertRedirect(route('games.show', $game));
    }

    public function test_join_requires_code_and_validates_input()
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('games.join'), []) // missing code
        ->assertSessionHasErrors(['code']);
    }

    public function test_join_with_unknown_code_returns_404()
    {
        $this->actingAs(User::factory()->create());

        // No game with this code
        $this->post(route('games.join'), ['code' => 'UNKNOWN'])
            ->assertNotFound();
    }

    public function test_show_renders_inertia_lobby_with_game_and_players()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $game = Game::factory()->create();
        // If you have a users() many-to-many, attach user to ensure players list is non-empty
        if (method_exists($game, 'users')) {
            $game->users()->attach($user->id);
        }

        $this->get(route('games.show', $game))
            ->assertOk()
            ->assertInertia(fn(Assert $page) => $page
                ->component('Game/Lobby')
                ->where('game.id', $game->id)
                ->has('players', fn(Assert $players) => $players->where('0.id', $user->id)->etc())
            );
    }

    public function test_game_status_is_updated_to_playing()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $game = Game::factory()->create(['status' => GameStatus::LOBBY]);

        $this->get(route('games.start', $game))
            ->assertOk()
            ->assertJson(['game' => $game->refresh()->toArray()]);

        $this->assertTrue($game->status === GameStatus::PLAYING);
        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'status' => GameStatus::PLAYING
        ]);
    }

    #[DataProvider('conflictGameStatuses')]
    public function test_prevent_game_status_update_if_not_lobby(GameStatus $status, string $message)
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $game = Game::factory()->create(['status' => $status]);

        $this->get(route('games.start', $game))
            ->assertConflict()
            ->assertJson(['message' => $message]);
    }

}
