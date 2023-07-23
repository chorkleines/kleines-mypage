<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_using_correct_email_and_password()
    {
        $response = $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_login_using_wrong_password()
    {
        $response = $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_using_unexisting_email()
    {
        $response = $this->postJson('/login', [
            'email' => 'does_not_exist@chorkleines.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }
}
