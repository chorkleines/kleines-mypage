<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_users()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => 1,
            'status' => UserStatus::PRESENT,
            'profile' => [
                'grade' => 18,
                'part' => 'T',
                'last_name' => '山田',
                'first_name' => '太郎',
                'name_kana' => 'ヤマダタロウ',
            ],
        ]);
    }
}
