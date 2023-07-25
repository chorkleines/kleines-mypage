<?php

namespace Tests\Feature;

use App\Enums\AccountingType;
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
}
