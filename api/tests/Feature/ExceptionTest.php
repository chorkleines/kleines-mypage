<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExceptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_401_unauthorized()
    {
        $response = $this->get('/api/me');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Unauthorized',
            'status' => 401,
            'detail' => 'Unauthorized',
        ]);
    }

    public function test_404_not_found()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/not_found');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Not Found',
            'status' => 404,
            'detail' => 'Not Found',
        ]);
    }

    public function test_403_forbidden()
    {
        $this->postJson('/login', [
            'email' => 'user_present@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Forbidden',
            'status' => 403,
            'detail' => 'Forbidden',
        ]);
    }
}
