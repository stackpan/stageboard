<?php

namespace Tests\Feature\Api;

use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ColumnTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_columns_inside_board(): void
    {
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
            ->assertJsonPath('message', 'Success.');
    }

    public function test_create_column_success(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $requestBody = [
            'name' => 'Test Column',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response
            ->assertCreated()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message'
            ])
            ->assertJsonPath('message', 'Column created successfully.');

        $this->assertDatabaseHas('columns', [
            'name' => $requestBody['name'],
        ]);
    }
    
    public function test_create_column_out_of_board_member(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $column = Column::whereNot('board_id', $board->id)->first();

        $requestBody = [
            'name' => 'Test Column',
            'next_column_id' => $column->id,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response
            ->assertBadRequest()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message'
            ])
            ->assertJsonPath('message', 'The next column id is out of specified board member.');

    }
}
