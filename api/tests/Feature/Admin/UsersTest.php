<?php

namespace Tests\Feature\Admin;

use App\Enums\Role;
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

    public function test_get_user_by_id_using_non_admin_user()
    {
        $this->postJson('/login', [
            'email' => 'user_present@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/1');
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

    public function test_get_user_by_id_using_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'status',
            'roles' => [],
            'profile' => [
                'grade',
                'part',
                'last_name',
                'first_name',
                'name_kana',
                'birthday',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                'MASTER',
            ],
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

    public function test_get_user_by_id_using_manager()
    {
        $this->postJson('/login', [
            'email' => 'admin_manager@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'status',
            'roles' => [],
            'profile' => [
                'grade',
                'part',
                'last_name',
                'first_name',
                'name_kana',
                'birthday',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'email' => 'admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                'MASTER',
            ],
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

    public function test_get_user_by_id_using_accountant()
    {
        $this->postJson('/login', [
            'email' => 'admin_accountant@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'status',
            'roles' => [],
            'profile' => [
                'grade',
                'part',
                'last_name',
                'first_name',
                'name_kana',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'status' => UserStatus::PRESENT,
            'roles' => [
                'MASTER',
            ],
            'profile' => [
                'grade' => 18,
                'part' => 'T',
                'last_name' => '山田',
                'first_name' => '太郎',
                'name_kana' => 'ヤマダタロウ',
            ],
        ]);
    }

    public function test_get_user_by_id_using_camp()
    {
        $this->postJson('/login', [
            'email' => 'admin_camp@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'status',
            'roles' => [],
            'profile' => [
                'grade',
                'part',
                'last_name',
                'first_name',
                'name_kana',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'status' => UserStatus::PRESENT,
            'roles' => [
                'MASTER',
            ],
            'profile' => [
                'grade' => 18,
                'part' => 'T',
                'last_name' => '山田',
                'first_name' => '太郎',
                'name_kana' => 'ヤマダタロウ',
            ],
        ]);
    }

    public function test_get_resigned_user_by_id_using_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/7');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'status',
            'roles' => [],
            'profile' => [
                'grade',
                'part',
                'last_name',
                'first_name',
                'name_kana',
                'birthday',
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 7,
            'email' => 'user_resigned@chorkleines.com',
            'status' => UserStatus::RESIGNED,
            'roles' => [],
        ]);
    }

    public function test_get_resigned_user_by_id_using_accountant()
    {
        $this->postJson('/login', [
            'email' => 'admin_accountant@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/users/7');
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

    public function test_update_user_using_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
        $response->assertStatus(204);
        $response = $this->getJson('/api/admin/users/99');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
    }

    public function test_update_user_using_manager()
    {
        $this->postJson('/login', [
            'email' => 'admin_manager@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
        $response->assertStatus(204);
        $response = $this->getJson('/api/admin/users/99');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
    }

    public function test_update_user_using_accountant()
    {
        $this->postJson('/login', [
            'email' => 'admin_accountant@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
        $response->assertStatus(403);
    }

    public function test_update_user_using_camp()
    {
        $this->postJson('/login', [
            'email' => 'admin_camp@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'email' => 'new_admin@chorkleines.com',
            'status' => UserStatus::PRESENT,
            'roles' => [
                Role::MANAGER,
                Role::ACCOUNTANT,
            ],
        ]);
        $response->assertStatus(403);
    }

    public function test_update_user_using_already_used_email()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'email' => 'admin@chorkleines.com',
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Bad Request',
            'status' => 400,
            'detail' => [
                'email' => [
                    'The email has already been taken.',
                ],
            ],
        ]);
    }

    public function test_update_user_using_non_existing_status()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'status' => 'NON_EXISTING_STATUS',
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Bad Request',
            'status' => 400,
            'detail' => [
                'status' => [
                    'The selected status is invalid.',
                ],
            ],
        ]);
    }

    public function test_update_user_using_non_existing_roles()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/users/99', [
            'roles' => [
                'NON_EXISTING_ROLE',
                Role::ACCOUNTANT,
            ],
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'title',
            'status',
            'detail',
        ]);
        $response->assertJson([
            'title' => 'Bad Request',
            'status' => 400,
            'detail' => [
                'roles.0' => [
                    'The selected roles.0 is invalid.',
                ],
            ],
        ]);
    }
}
