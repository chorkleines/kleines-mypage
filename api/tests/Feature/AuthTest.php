<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_get_user_info_using_unauthenticated_user()
    {
        $response = $this->get('/api/me');

        $response->assertStatus(401);
    }

    public function test_check_if_authenticated_using_unauthenticated_user()
    {
        $response = $this->get('/api/auth');

        $response->assertStatus(200);
        $this->assertSame('unauthenticated', $response->json());
    }
}
