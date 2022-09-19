<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\AccountingType;
use App\Enums\BulletinBoardStatus;
use App\Enums\Part;
use App\Enums\Role;
use App\Enums\UserStatus;
use DateTime;
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
        if (app()->isProduction()) {
            return;
        }

        // create admin user
        $user = \App\Models\User::factory()->create([
            'email' => 'admin@chorkleines.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password is password
            'status' => UserStatus::PRESENT,
        ]);
        \App\Models\Profile::factory()->create([
            'user_id' => $user->user_id,
        ]);
        \App\Models\Admin::create([
            'user_id' => $user->user_id,
            'role' => Role::MASTER,
        ]);
        echo 'Email: admin@chorkleines.com'.PHP_EOL;
        echo 'Password: password'.PHP_EOL;

        \App\Models\Profile::factory(100)->create();

        $accounting_list = \App\Models\AccountingList::create([
            'name' => 'テスト集金',
            'deadline' => date('2021-12-31'),
            'admin' => AccountingType::GENERAL,
        ]);

        \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user->user_id,
            'price' => 1000,
            'paid_cash' => 1000,
            'datetime' => new DateTime('2021-12-30 12:00:00'),
        ]);

        $individual_accounting_list = \App\Models\IndividualAccountingList::create(
            [
                'name' => '2020年度引き継ぎ',
                'datetime' => new DateTime('2020-12-31 12:00:00'),
            ]
        );

        \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user->user_id,
            'price' => 1000,
            'list_id' => $individual_accounting_list->list_id,
        ]);

        $bulletin_board = \App\Models\BulletinBoard::create([
            'user_id' => $user->user_id,
            'title' => 'test',
            'status' => BulletinBoardStatus::RELEASE,
        ]);

        \App\Models\BulletinBoardContent::create([
            'bulletin_board_id' => $bulletin_board->bulletin_board_id,
            'user_id' => $user->user_id,
            'datetime' => now(),
            'content' => 'content',
        ]);
    }
}
