<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_me_without_authenticated_user()
    {
        $response = $this->get('/api/me');

        $response->assertStatus(401);
    }

    public function test_auth_without_authenticated_user()
    {
        $response = $this->get('/api/auth');

        $response->assertStatus(200);
        $this->assertSame('unauthenticated', $response->json());
    }
}
