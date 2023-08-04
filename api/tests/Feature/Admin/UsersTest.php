<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_not_admin()
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

    public function test_admin_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'status',
                'profile' => [
                    'grade',
                    'part',
                    'last_name',
                    'first_name',
                    'name_kana',
                    'birthday',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'profile' => [
                'grade' => 18,
                'part' => 'T',
                'last_name' => '山田',
                'first_name' => '太郎',
                'name_kana' => 'ヤマダタロウ',
                'birthday' => '2000-01-01',
            ],
        ]);
    }

    public function test_admin_manager()
    {
        $this->postJson('/login', [
            'email' => 'admin_manager@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'status',
                'profile' => [
                    'grade',
                    'part',
                    'last_name',
                    'first_name',
                    'name_kana',
                    'birthday',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'profile' => [
                'grade' => 18,
                'part' => 'T',
                'last_name' => '山田',
                'first_name' => '太郎',
                'name_kana' => 'ヤマダタロウ',
                'birthday' => '2000-01-01',
            ],
        ]);
    }

    public function test_admin_accountant()
    {
        $this->postJson('/login', [
            'email' => 'admin_accountant@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'status',
                'profile' => [
                    'grade',
                    'part',
                    'last_name',
                    'first_name',
                    'name_kana',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
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

    public function test_admin_camp()
    {
        $this->postJson('/login', [
            'email' => 'admin_camp@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'status',
                'profile' => [
                    'grade',
                    'part',
                    'last_name',
                    'first_name',
                    'name_kana',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
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
