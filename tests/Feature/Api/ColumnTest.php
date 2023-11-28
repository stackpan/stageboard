<?php

namespace Tests\Feature\Api;

use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                        'order',
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
        $board = $user->boards()
            ->create([
                'name' => 'Empty Board',
            ]);

        $requestBody = [
            'name' => 'Test Column',
            'order' => 0,
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

    public function test_create_column_out_of_available_columns(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $requestBody = [
            'name' => 'Test Column',
            'order' => 4,
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
            ->assertJsonPath('message', 'The order is out of available columns.');
    }

    public function test_column_shifting(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $shiftedColumn = $board->columns()->whereOrder(1)->first();

        $requestBody = [
            'name' => 'Test Column',
            'order' => 1,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response
            ->assertCreated();

        $this->assertEquals(2, $shiftedColumn->fresh()->order);
    }

    public function test_get_column_details_success(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $column = $board->columns()->first();

        $response = $this
            ->actingAs($user)
            ->get(route('api.boards.columns.show', [$board->id, $column->id]));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'order',
                    'cards' => [
                        '*' => [
                            'id',
                            'body',
                            'links' => [
                                'self' => [
                                    'href',
                                ],
                                'move' => [
                                    'href',
                                ],
                            ],
                        ],
                    ],
                    'links' => [
                        'self' => [
                            'href',
                        ],
                        'move' => [
                            'href',
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data.id', $column->id)
            ->assertJsonPath('data.name', $column->name);
    }

    public function test_get_column_details_not_found(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $response = $this
            ->actingAs($user)
            ->get(route('api.boards.columns.show', [$board->id, 'fictionalid']));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column not found.');
    }

    public function test_update_column_success(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $column = $board->columns()->first();

        $requestBody = [
            'name' => 'Updated Column',
        ];

        $response = $this
            ->actingAs($user)
            ->patch(route('api.boards.columns.update', [$board->id, $column->id]), $requestBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column updated successfully.');

        $this->assertDatabaseHas('columns', [
            'id' => $column->id,
            'name' => $requestBody['name'],
        ]);
    }

    public function test_update_column_not_found(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $response = $this
            ->actingAs($user)
            ->patch(route('api.boards.columns.update', [$board->id, 'fictionalid']));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column not found.');
    }

    public function test_delete_column_success(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $column = $board->columns()->first();

        $response = $this
            ->actingAs($user)
            ->delete(route('api.boards.columns.destroy', [$board->id, $column->id]));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column was successfully deleted.');

        $this->assertDatabaseMissing('columns', [
            'id' => $column->id,
        ]);
    }

    public function test_delete_column_not_found(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $response = $this
            ->actingAs($user)
            ->delete(route('api.boards.columns.destroy', [$board->id, 'fictionalid']));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column not found.');
    }

    public function test_move_column_success(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $targetColumn = $board->columns()->whereOrder(0)->first();
        $shiftedColumn = $board->columns()->whereOrder(1)->first();

        $responseBody = [
            'order' => $targetColumn->order + 1,
        ];

        $response = $this
            ->actingAs($user)
            ->patch(route('api.boards.columns.move', [$board->id, $targetColumn->id]), $responseBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column was successfully moved.');

        $this->assertEquals(1, $targetColumn->fresh()->order);
        $this->assertEquals(0, $shiftedColumn->fresh()->order);
    }
}
