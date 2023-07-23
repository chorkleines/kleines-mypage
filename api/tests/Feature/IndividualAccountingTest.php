<?php

namespace Tests\Feature;

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
}
