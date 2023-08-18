<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndividualAccountingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_individual_accounting_lists_using_master()
    {
        $this->postJson('/login', [
            'email' => 'admin@chorkleines.com',
            'password' => 'password',
        ]);

        $response = $this->getJson('/api/admin/individual-accountings');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'datetime'
            ],
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'name' => '2020年度引き継ぎ',
            'datetime' => '2020-12-31 12:00:00'
        ]);
    }
}
