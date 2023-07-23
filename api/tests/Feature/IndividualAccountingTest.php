<?php

namespace Tests\Feature;

use App\Enums\AccountingType;
use App\Enums\PaymentMethod;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndividualAccountingTest extends TestCase
{
    use RefreshDatabase;

    public function test_individual_accounting()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $individual_accounting_list = \App\Models\IndividualAccountingList::create(
            [
                'name' => '2019年度引き継ぎ',
                'datetime' => new DateTime('2019-12-31 12:34:56'),
            ]
        );
        $individual_accounting_record = \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user_id,
            'price' => 8790,
            'list_id' => $individual_accounting_list->list_id,
            'datetime' => '2019-12-31 13:20:32',
        ]);

        $response = $this->get('/api/individual_accountings');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'price' => 8790,
                'name' => '2019年度引き継ぎ',
                'datetime' => '2019-12-31 13:20:32',
                'created_at' => date('Y-m-d H:i:s', strtotime($individual_accounting_record->created_at)),
                'updated_at' => date('Y-m-d H:i:s', strtotime($individual_accounting_record->updated_at)),
            ],
        ]);
    }

    public function test_use_individual_accounting_for_payment()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $individual_accounting_list = \App\Models\IndividualAccountingList::create(
            [
                'name' => '2019年度引き継ぎ',
                'datetime' => new DateTime('2019-12-31 12:34:56'),
            ]
        );

        \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user_id,
            'price' => 12000,
            'list_id' => $individual_accounting_list->list_id,
            'datetime' => '2019-12-31 13:20:32',
        ]);

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-31 12:34:56'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'datetime' => '2022-06-01 12:34:56',
        ]);

        \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 10000,
            'method' => PaymentMethod::CASH,
        ]);

        $payment = \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 2000,
            'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
        ]);
        $individual_accounting_record = \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user_id,
            'price' => 2000,
            'accounting_payment_id' => $payment->id,
            'datetime' => '2022-06-01 12:34:56',
        ]);

        $response = $this->get('/api/individual_accountings');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'accounting_id' => $accounting_list->accounting_id,
                'price' => 2000,
                'name' => '2022年度団費集金',
                'datetime' => '2022-06-01 12:34:56',
                'created_at' => date('Y-m-d H:i:s', strtotime($individual_accounting_record->created_at)),
                'updated_at' => date('Y-m-d H:i:s', strtotime($individual_accounting_record->updated_at)),
            ],
        ]);
    }
}
