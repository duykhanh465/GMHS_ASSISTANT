<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token_and_user(): void
    {
        User::factory()->create([
            'email' => 'doctor@gmhs.test',
            'password' => 'password',
            'role' => 'doctor',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'doctor@gmhs.test',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.email', 'doctor@gmhs.test')
            ->assertJsonPath('user.role', 'doctor');
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_login_rejects_wrong_password(): void
    {
        User::factory()->create(['email' => 'doctor@gmhs.test', 'password' => 'password']);

        $this->postJson('/api/login', [
            'email' => 'doctor@gmhs.test',
            'password' => 'wrong',
        ])->assertStatus(422);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'off@gmhs.test',
            'password' => 'password',
            'is_active' => false,
        ]);

        $this->postJson('/api/login', [
            'email' => 'off@gmhs.test',
            'password' => 'password',
        ])->assertStatus(422);
    }

    public function test_me_requires_auth(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }

    public function test_me_and_logout(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('email', $user->email);

        $this->withToken($token)->postJson('/api/logout')->assertOk();
    }
}
