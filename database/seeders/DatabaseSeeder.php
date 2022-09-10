<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Part;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        if (app()->isProduction()) {
            return;
        }

        $user = \App\Models\User::create([
            'email' => 'admin@chorkleines.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password is password
            'status' => UserStatus::PRESENT,
        ]);

        \App\Models\Profile::create([
            'user_id' => $user->user_id,
            'last_name' => '管理者',
            'first_name' => '',
            'name_kana' => 'カンリシャ',
            'grade' => 00,
            'part' => Part::SOPRANO,
        ]);

        \App\Models\Admin::create([
            'user_id' => $user->user_id,
            'role' => Role::MASTER,
        ]);

        echo 'Email: admin@chorkleines.com' . PHP_EOL;
        echo 'Password: password' . PHP_EOL;
    }
}
