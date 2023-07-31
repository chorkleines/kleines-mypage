<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_payment_info()
    {
        // TODO: create a new user and test it
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/home/payment_info');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'arrears',
            'balance',
        ]);
    }
}
