<?php

namespace Tests\Feature\Api;

use App\Models\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
        $board = $user->boards()
            ->create([
                'name' => 'Empty Board',
            ]);

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
    
    public function test_create_column_to_first_order(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $firstColumn = $board->columns()->whereName('Open')->first();

        $requestBody = [
            'name' => 'Test Column',
            'next_column_id' => $firstColumn->id,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response->assertCreated();

        $columns = $board->columns()->get();
        $sortedColumns = $this->sort($columns);

        $this->assertEquals($requestBody['name'], $sortedColumns->first()->name);
    }

    public function test_create_column_in_middle_order(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();
        $secondColumn = $board->columns()->whereName('In Progress')->first();

        $requestBody = [
            'name' => 'Test Column',
            'next_column_id' => $secondColumn->id,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response->assertCreated();

        $columns = $board->columns()->get();
        $sortedColumns = $this->sort($columns);

        $this->assertEquals($requestBody['name'], $sortedColumns->get(1)->name);
    }

    public function test_create_column_at_last_order(): void
    {
        $user = User::whereEmail('test@example.com')->first();
        $board = $user->boards()->first();

        $requestBody = [
            'name' => 'Test Column',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('api.boards.columns.store', $board->id), $requestBody);

        $response->assertCreated();

        $columns = $board->columns()->get();
        $sortedColumns = $this->sort($columns);

        $this->assertEquals($requestBody['name'], $sortedColumns->last()->name);
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

    private function sort(Collection $columns): \Illuminate\Support\Collection
    {
        $result = collect();

        $prevColumn = $columns->first(fn (Column $column, int $key) => is_null($column->next_column_id));

        while (!is_null($prevColumn)) {
            $result->prepend($prevColumn);
            $prevColumn = $columns->first(fn (Column $column, int $key) => $column->next_column_id === $prevColumn->id);
        }

        return $result;
    }
}
