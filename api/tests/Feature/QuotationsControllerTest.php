<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_returns_a_valid_quote()
    {
        $response = $this->postJson('/api/quotation', [
            'age' => '25,30,40',
            'currency_id' => 'USD',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'currency_id',
                'quotation_id'
            ]);
    }
}
