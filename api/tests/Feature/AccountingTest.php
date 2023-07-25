<?php

namespace Tests\Feature;

use App\Enums\AccountingType;
use App\Enums\PaymentMethod;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingTest extends TestCase
{
    use RefreshDatabase;

    public function test_accountings()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-30'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
        ]);

        $response = $this->get('/api/accountings');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'id' => $accounting_record->id,
                'accounting_id' => $accounting_list->accounting_id,
                'user_id' => $user_id,
                'price' => 12000,
                'is_paid' => false,
                'datetime' => null,
                'accounting_list' => [
                    'accounting_id' => $accounting_list->accounting_id,
                    'name' => '2022年度団費集金',
                    'deadline' => '2022-06-30',
                    'admin' => AccountingType::GENERAL,
                ],
            ],
        ]);
    }

    public function test_unpaid_accounting()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-30'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
        ]);

        $response = $this->get('/api/accountings/'.$accounting_list->accounting_id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $accounting_record->id,
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'is_paid' => false,
            'datetime' => null,
            'accounting_list' => [
                'accounting_id' => $accounting_list->accounting_id,
                'name' => '2022年度団費集金',
                'deadline' => '2022-06-30',
                'admin' => AccountingType::GENERAL,
            ],
            'accounting_payments' => [],
        ]);
    }

    public function test_paid_accounting_using_cash()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-30'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'datetime' => '2022-06-01 12:34:56',
            'is_paid' => true,
        ]);

        $accounting_payment = \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 12000,
            'method' => PaymentMethod::CASH,
        ]);

        $response = $this->get('/api/accountings/'.$accounting_list->accounting_id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $accounting_record->id,
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'is_paid' => true,
            'datetime' => '2022-06-01 12:34:56',
            'accounting_list' => [
                'accounting_id' => $accounting_list->accounting_id,
                'name' => '2022年度団費集金',
                'deadline' => '2022-06-30',
                'admin' => AccountingType::GENERAL,
            ],
            'accounting_payments' => [[
                'id' => $accounting_payment->id,
                'accounting_record_id' => $accounting_record->id,
                'price' => 12000,
                'method' => PaymentMethod::CASH,
            ]],
        ]);
    }

    public function test_paid_accounting_using_individual_accounting()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-30'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'datetime' => '2022-06-01 12:34:56',
            'is_paid' => true,
        ]);

        $accounting_payment = \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 12000,
            'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
        ]);

        \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user_id,
            'price' => 12000,
            'accounting_payment_id' => $accounting_payment->id,
            'datetime' => '2022-06-01 12:34:56',
        ]);

        $response = $this->get('/api/accountings/'.$accounting_list->accounting_id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $accounting_record->id,
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'is_paid' => true,
            'datetime' => '2022-06-01 12:34:56',
            'accounting_list' => [
                'accounting_id' => $accounting_list->accounting_id,
                'name' => '2022年度団費集金',
                'deadline' => '2022-06-30',
                'admin' => AccountingType::GENERAL,
            ],
            'accounting_payments' => [[
                'id' => $accounting_payment->id,
                'accounting_record_id' => $accounting_record->id,
                'price' => 12000,
                'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
            ]],
        ]);

        $response = $this->get('/api/individual_accountings');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'accounting_payment' => [
                    'accounting_record_id' => $accounting_record->id,
                    'accounting_record' => [
                        'accounting_id' => $accounting_list->accounting_id,
                        'accounting_list' => [
                            'accounting_id' => $accounting_list->accounting_id,
                            'admin' => AccountingType::GENERAL,
                            'deadline' => '2022-06-30',
                            'name' => '2022年度団費集金',
                        ],
                        'datetime' => '2022-06-01 12:34:56',
                        'id' => $accounting_record->id,
                        'is_paid' => true,
                        'price' => 12000,
                        'user_id' => $user_id,
                    ],
                    'id' => $accounting_payment->id,
                    'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
                    'price' => 12000,
                ],
                'accounting_payment_id' => $accounting_payment->id,
                'datetime' => '2022-06-01 12:34:56',
                'individual_accounting_list' => null,
                'list_id' => null,
                'price' => 12000,
                'user_id' => $user_id,
            ],
        ]);
    }

    public function test_paid_accounting_using_cash_and_individual_accounting()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);
        $response = $this->get('/api/me');
        $user_id = $response->json()['user_id'];

        $accounting_list = \App\Models\AccountingList::create(
            [
                'name' => '2022年度団費集金',
                'deadline' => new DateTime('2022-06-30'),
                'admin' => AccountingType::GENERAL,
            ]
        );

        $accounting_record = \App\Models\AccountingRecord::create([
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'datetime' => '2022-06-01 12:34:56',
            'is_paid' => true,
        ]);

        $accounting_payment_cash = \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 10000,
            'method' => PaymentMethod::CASH,
        ]);

        $accounting_payment_individual_accounting = \App\Models\AccountingPayment::create([
            'accounting_record_id' => $accounting_record->id,
            'price' => 2000,
            'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
        ]);

        \App\Models\IndividualAccountingRecord::create([
            'user_id' => $user_id,
            'price' => 2000,
            'accounting_payment_id' => $accounting_payment_individual_accounting->id,
            'datetime' => '2022-06-01 12:34:56',
        ]);

        $response = $this->get('/api/accountings/'.$accounting_list->accounting_id);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $accounting_record->id,
            'accounting_id' => $accounting_list->accounting_id,
            'user_id' => $user_id,
            'price' => 12000,
            'is_paid' => true,
            'datetime' => '2022-06-01 12:34:56',
            'accounting_list' => [
                'accounting_id' => $accounting_list->accounting_id,
                'name' => '2022年度団費集金',
                'deadline' => '2022-06-30',
                'admin' => AccountingType::GENERAL,
            ],
            'accounting_payments' => [
                [
                    'id' => $accounting_payment_cash->id,
                    'accounting_record_id' => $accounting_record->id,
                    'price' => 10000,
                    'method' => PaymentMethod::CASH,
                ],
                [
                    'id' => $accounting_payment_individual_accounting->id,
                    'accounting_record_id' => $accounting_record->id,
                    'price' => 2000,
                    'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
                ],
            ],
        ]);

        $response = $this->get('/api/individual_accountings');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'accounting_payment' => [
                    'accounting_record_id' => $accounting_record->id,
                    'accounting_record' => [
                        'accounting_id' => $accounting_list->accounting_id,
                        'accounting_list' => [
                            'accounting_id' => $accounting_list->accounting_id,
                            'admin' => AccountingType::GENERAL,
                            'deadline' => '2022-06-30',
                            'name' => '2022年度団費集金',
                        ],
                        'datetime' => '2022-06-01 12:34:56',
                        'id' => $accounting_record->id,
                        'is_paid' => true,
                        'price' => 12000,
                        'user_id' => $user_id,
                    ],
                    'id' => $accounting_payment_individual_accounting->id,
                    'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
                    'price' => 2000,
                ],
                'accounting_payment_id' => $accounting_payment_individual_accounting->id,
                'datetime' => '2022-06-01 12:34:56',
                'individual_accounting_list' => null,
                'list_id' => null,
                'price' => 2000,
                'user_id' => $user_id,
            ],
        ]);
    }
}
