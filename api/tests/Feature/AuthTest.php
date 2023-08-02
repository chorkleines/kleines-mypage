<?php

namespace Tests\Feature;

use App\Enums\Part;
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
        $response->assertJsonStructure([
            'email',
            'status',
            'last_name',
            'first_name',
            'name_kana',
            'grade',
            'part',
            'birthday',
            'roles' => [],
        ]);
        $response->assertJson([
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'last_name' => '山田',
            'first_name' => '太郎',
            'name_kana' => 'ヤマダタロウ',
            'grade' => 18,
            'part' => Part::TENOR,
            'birthday' => '2000-01-01',
            'roles' => ['MASTER'],
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
