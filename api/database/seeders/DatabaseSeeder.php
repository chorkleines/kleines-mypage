<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\AccountingType;
use App\Enums\BulletinBoardStatus;
use App\Enums\Part;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Enums\UserStatus;
use DateTime;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function createAdminUser($role)
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'admin_'.strtolower($role).'@chorkleines.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password is password
            'status' => UserStatus::PRESENT,
        ]);
        \App\Models\Profile::factory()->create([
            'user_id' => $user->user_id,
        ]);
        \App\Models\Admin::create([
            'user_id' => $user->user_id,
            'role' => $role,
        ]);
    }

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
            'last_name' => '山田',
            'first_name' => '太郎',
            'name_kana' => 'ヤマダタロウ',
            'grade' => '18',
            'part' => Part::TENOR,
            'birthday' => '2000-01-01',
        ]);
        \App\Models\Admin::create([
            'user_id' => $user->user_id,
            'role' => Role::MASTER,
        ]);
        echo 'Email: admin@chorkleines.com'.PHP_EOL;
        echo 'Password: password'.PHP_EOL;

        $this->createAdminUser(Role::MANAGER);
        $this->createAdminUser(Role::ACCOUNTANT);
        $this->createAdminUser(Role::CAMP);

        // create random users
        \App\Models\User::factory(100)->has(\App\Models\Profile::factory())->create();

        // create accounting records
        $individual_accounting_list = \App\Models\IndividualAccountingList::create(
            [
                'name' => '2020年度引き継ぎ',
                'datetime' => new DateTime('2020-12-31 12:00:00'),
            ]
        );

        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\IndividualAccountingRecord::create([
                'user_id' => $user->user_id,
                'price' => random_int(1000, 10000),
                'list_id' => $individual_accounting_list->list_id,
                'datetime' => date('Y-m-d H:i:s', random_int(time() - (30 * 24 * 60 * 60), time())),
            ]);
        }

        $accounting_names = [
            'テスト集金1',
            'テスト集金2',
            'テスト集金3',
            'テスト集金4',
            'テスト集金5',
            'テスト集金6',
            'テスト集金7',
            'テスト集金8',
            'テスト集金9',
            'テスト集金10',
        ];
        foreach ($accounting_names as $accounting_name) {
            $accounting_list = \App\Models\AccountingList::create([
                'name' => $accounting_name,
                'deadline' => date('Y-m-d', random_int(time() - (30 * 24 * 60 * 60), time() + (30 * 24 * 60 * 60))),
                'admin' => AccountingType::GENERAL,
            ]);

            foreach ($users as $user) {
                if (random_int(0, 5) % 5 == 0) {
                    continue;
                }
                $price = random_int(1000, 10000);
                $is_paid = random_int(0, 5) % 5 != 0;
                if (! $is_paid) {
                    \App\Models\AccountingRecord::create([
                        'accounting_id' => $accounting_list->accounting_id,
                        'user_id' => $user->user_id,
                        'price' => $price,
                    ]);
                    continue;
                }

                $paid_individual = min($price, $user->individualAccountingRecords->sum('price'));
                $paid_cash = $price - $paid_individual;
                $datetime = date('Y-m-d H:i:s', random_int(time() - (30 * 24 * 60 * 60), time()));
                $accounting_record = \App\Models\AccountingRecord::create([
                    'accounting_id' => $accounting_list->accounting_id,
                    'user_id' => $user->user_id,
                    'price' => $price,
                    'datetime' => $datetime,
                    'is_paid' => true,
                ]);
                if ($paid_cash > 0) {
                    \App\Models\AccountingPayment::create([
                        'accounting_record_id' => $accounting_record->id,
                        'price' => $paid_cash,
                        'method' => PaymentMethod::CASH,
                    ]);
                }
                if ($paid_individual > 0) {
                    $payment = \App\Models\AccountingPayment::create([
                        'accounting_record_id' => $accounting_record->id,
                        'price' => $paid_individual,
                        'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
                    ]);
                    \App\Models\IndividualAccountingRecord::create([
                        'user_id' => $user->user_id,
                        'price' => $paid_individual,
                        'accounting_payment_id' => $payment->id,
                    ]);
                }
            }
        }

        // create bulletin board
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
