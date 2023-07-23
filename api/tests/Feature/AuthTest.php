<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_info_using_unauthenticated_user()
    {
        $response = $this->get('/api/me');

        $response->assertStatus(401);
    }

    public function test_get_user_info_using_authenticated_user()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');

        $response->assertStatus(200);
        $response->assertJson([
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
        ]);
    }

    public function test_check_if_authenticated_using_unauthenticated_user()
    {
        $response = $this->get('/api/auth');

        $response->assertStatus(200);
        $this->assertSame('unauthenticated', $response->json());
    }

    public function test_check_if_authenticated_using_authenticated_user()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/auth');

        $response->assertStatus(200);
        $this->assertSame('authenticated', $response->json());
    }
}
