<?php

namespace Tests\Feature;

use App\Enums\Part;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_profile_using_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/profiles/99', [
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
        $response->assertStatus(204);
        $response = $this->getJson('/api/admin/users/99');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
    }

    public function test_update_profile_using_manager()
    {
        $this->postJson('/login', [
            'email' => 'admin_manager@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/profiles/99', [
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
        $response->assertStatus(204);
        $response = $this->getJson('/api/admin/users/99');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
    }

    public function test_update_profile_using_accountant()
    {
        $this->postJson('/login', [
            'email' => 'admin_accountant@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/profiles/99', [
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
        $response->assertStatus(403);
    }

    public function test_update_profile_using_camp()
    {
        $this->postJson('/login', [
            'email' => 'admin_camp@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/profiles/99', [
            'last_name' => '山本',
            'first_name' => '二郎',
            'name_kana' => 'ヤマモトジロウ',
            'grade' => 23,
            'part' => Part::SOPRANO,
            'birthday' => '1998-01-01',
        ]);
        $response->assertStatus(403);
    }

    public function test_update_profile_using_non_existing_part()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->putJson('/api/admin/profiles/99', [
            'part' => 'NON_EXISTING_PART',
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
                'part' => [
                    'The selected part is invalid.',
                ],
            ],
        ]);
    }
}
