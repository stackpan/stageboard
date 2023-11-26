<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ColumnTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_columns_inside_board() {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        
        $response = $this
            ->actingAs($user)
            ->get(route('api.boards.columns.index', $board->id));
        
        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'next_column_id',
                        'links' => [
                            'self' => [
                                'href',
                            ],
                            'move' => [
                                'href',
                            ],
                        ],
                    ],
                ]
            ])
            ->assertJsonPath('message', 'Success');
    }
    
    
}
