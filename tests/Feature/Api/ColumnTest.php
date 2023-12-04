<?php

namespace Tests\Feature\Api;

use App\Enums\Color;
use App\Models\Board;
use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColumnTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Board $board;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();
        $this->board = $this->user->boards()->first();
    }

    public function test_get_all_columns_inside_board(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.boards.columns.index', $this->board->id));
        
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
                        'color',
                        'cards' => [
                            '*' => [
                                'id',
                                'body',
                                'color',
                                '_links',
                            ],
                        ],
                        '_links',
                    ],
                ]
            ])
            ->assertJsonPath('message', 'Success.');
    }

    public function test_create_column_success(): void
    {
        $board = $this->user->boards()
            ->create([
                'name' => 'Empty Board',
            ]);

        $requestBody = [
            'name' => 'Test Column',
            'order' => 0,
        ];

        $response = $this
            ->actingAs($this->user)
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
        $requestBody = [
            'name' => 'Test Column',
            'order' => 4,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('api.boards.columns.store', $this->board->id), $requestBody);

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
        $shiftedColumn = $this->board->columns()->whereOrder(1)->first();

        $requestBody = [
            'name' => 'Test Column',
            'order' => 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('api.boards.columns.store', $this->board->id), $requestBody);

        $response
            ->assertCreated();

        $this->assertEquals(2, $shiftedColumn->fresh()->order);
    }

    public function test_get_column_details_success(): void
    {
        $column = $this->board->columns()->first();

        $response = $this
            ->actingAs($this->user)
            ->get(route('api.columns.show', $column->id));

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
                            '_links',
                        ],
                    ],
                    '_links',
                ],
            ])
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data.id', $column->id)
            ->assertJsonPath('data.name', $column->name);
    }

    public function test_get_column_details_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.columns.show', 'fictionalid'));

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
        $column = $this->board->columns()->first();

        $requestBody = [
            'name' => 'Updated Column',
            'color' => $column->color->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.columns.update', $column->id), $requestBody);

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
            'color' => $requestBody['color'],
        ]);
    }

    public function test_update_column_not_found(): void
    {
        $requestBody = [
            'name' => 'Updated Column',
            'color' => fake()->randomElement(Color::class)->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.columns.update', 'fictionalid'), $requestBody);

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
        $column = $this->board->columns()->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.columns.destroy', $column->id));

        $response
            ->assertOk()
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
        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.columns.destroy', 'fictionalid'));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column not found.');
    }

    public function swap_column_success(): void
    {
        $targetColumn = $this->board->columns()->whereOrder(0)->first();
        $shiftedColumn = $this->board->columns()->whereOrder(1)->first();

        $responseBody = [
            'order' => $targetColumn->order + 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.columns.swap', $targetColumn->id), $responseBody);

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

    public function swap_column_backward_success(): void
    {
        $targetColumn = $this->board->columns()->whereOrder(2)->first();
        $shiftedColumn = $this->board->columns()->whereOrder(1)->first();

        $responseBody = [
            'order' => $targetColumn->order - 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.columns.swap', $targetColumn->id), $responseBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Column was successfully moved.');

        $this->assertEquals(1, $targetColumn->fresh()->order);
        $this->assertEquals(2, $shiftedColumn->fresh()->order);
    }

    public function swap_column_throws_zero_delta(): void
    {
        $targetColumn = $this->board->columns()->whereOrder(1)->first();

        $responseBody = [
            'order' => $targetColumn->order,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.columns.swap', $targetColumn->id), $responseBody);

        $response
            ->assertBadRequest()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'The column order is same with specified order.');
    }
}
