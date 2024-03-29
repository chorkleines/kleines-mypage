<?php

namespace Tests\Feature;

use App\Enums\Part;
use App\Enums\UserStatus;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_info_using_unauthenticated_user()
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
        $this->assertSame(['authenticated' => false], $response->json());
    }

    public function test_check_if_authenticated_using_authenticated_user()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/auth');

        $response->assertStatus(200);
        $this->assertSame(['authenticated' => true], $response->json());
    }

    public function test_check_if_forgot_password_email_is_successfully_sent()
    {
        $user = \App\Models\User::factory()->create([
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password is password
            'status' => 'PRESENT',
        ]);
        \App\Models\Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson('/api/password/forgot', [
            'email' => $user->email,
        ]);
        $response->assertStatus(200);
    }

    public function test_email_verifiction()
    {
        $user = \App\Models\User::factory()->create([
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password is password
            'status' => 'PRESENT',
        ]);
        \App\Models\Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        Notification::fake();
        $response = $this->postJson('/api/password/forgot', [
            'email' => $user->email,
        ]);
        $response->assertStatus(200);
        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function ($notification, $channels) use ($user, &$token) {
                $token = $notification->token;

                return true;
            }
        );

        $response = $this->get('/api/auth');
        $response->assertStatus(200);
        $this->assertSame(['authenticated' => false], $response->json());

        $response = $this->postJson('/api/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Your password has been reset!',
        ]);

        $response = $this->get('/api/auth');
        $response->assertStatus(200);
        $this->assertSame(['authenticated' => true], $response->json());

        $this->assertTrue(Hash::check('Password123', DB::table('users')->where('email', $user->email)->first()->password));
        $this->assertFalse(Hash::check('password', DB::table('users')->where('email', $user->email)->first()->password));
    }
}
