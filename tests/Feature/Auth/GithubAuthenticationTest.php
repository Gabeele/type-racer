<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\Provider as ProviderContract;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GithubAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_starts_oauth_flow()
    {
        $provider = Mockery::mock(ProviderContract::class);
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('https://github.com/login'));

        Socialite::shouldReceive('driver')->with('github')->once()->andReturn($provider);

        $this->get('/auth/github/redirect')
            ->assertRedirect('https://github.com/login');
    }

    public function test_callback_creates_user_and_logs_in()
    {
        $provider = Mockery::mock(ProviderContract::class);
        $socialUser = $this->socialiteUser(
            id: '9999',
            name: 'Jane Doe',
            nickname: 'jdoe',
            email: 'jane@example.com',
            token: 'token-123',
            refreshToken: 'refresh-456'
        );
        $provider->shouldReceive('user')->once()->andReturn($socialUser);

        Socialite::shouldReceive('driver')->with('github')->once()->andReturn($provider);

        $this->get('/auth/github/callback')
            ->assertRedirect('/dashboard');

        $this->assertTrue(Auth::check());
        $this->assertDatabaseHas('users', [
            'github_id' => '9999',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'github_token' => 'token-123',
            'github_refresh_token' => 'refresh-456',
        ]);
    }

    private function socialiteUser(string $id, ?string $name, ?string $nickname, ?string $email, string $token, ?string $refreshToken): SocialiteUserContract
    {
        return new class($id, $name, $nickname, $email, $token, $refreshToken) implements SocialiteUserContract {
            public function __construct(
                public string  $id,
                public ?string $name,
                public ?string $nickname,
                public ?string $email,
                public string  $token,
                public ?string $refreshToken
            )
            {
            }

            public function getId()
            {
                return $this->id;
            }

            public function getNickname()
            {
                return $this->nickname;
            }

            public function getName()
            {
                return $this->name;
            }

            public function getEmail()
            {
                return $this->email;
            }

            public function getAvatar()
            {
                return null;
            }
        };
    }

    public function test_callback_updates_existing_user_and_logs_in()
    {
        $existing = User::factory()->create([
            'github_id' => '42',
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'github_token' => 'old-token',
            'github_refresh_token' => 'old-refresh',
        ]);

        $provider = Mockery::mock(ProviderContract::class);
        $socialUser = $this->socialiteUser(
            id: '42',
            name: null,
            nickname: 'newnick',
            email: 'new@example.com',
            token: 'new-token',
            refreshToken: null
        );
        $provider->shouldReceive('user')->once()->andReturn($socialUser);

        Socialite::shouldReceive('driver')->with('github')->once()->andReturn($provider);

        $this->get('/auth/github/callback')
            ->assertRedirect('/dashboard');

        $this->assertTrue(Auth::check());
        $this->assertSame($existing->id, Auth::id());

        $this->assertDatabaseHas('users', [
            'id' => $existing->id,
            'github_id' => '42',
            'name' => 'newnick',
            'email' => 'new@example.com',
            'github_token' => 'new-token',
            'github_refresh_token' => null,
        ]);
    }
}
