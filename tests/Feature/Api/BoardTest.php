<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();
    }

    public function test_get_all_user_boards_success(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.boards.index'));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
               'message',
               'data' => [
                    '*' => [
                        'id',
                        'name',
                        'thumbnail_url',
                        'user' => [
                            'id',
                            'name',
                        ],
//                        'opened_at',
                        '_links',
                    ],
                ],
            ])
            ->assertJsonPath('message', 'Success.')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.user.id', $this->user->id);
    }

    public function test_create_board_success(): void
    {
        $requestBody = [
            'name' => 'Board Test',
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('api.boards.store'), $requestBody);

        $response
            ->assertCreated()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'board' => [
                        'id',
                    ],
                ],
            ])
            ->assertJsonPath('message', 'Board created successfully.');

        $this->assertDatabaseHas('boards', [
            'name' => $requestBody['name'],
        ]);
    }

    public function test_get_board_details_success(): void
    {
        $board = $this->user->collaborationBoards()->first();

        $response = $this
            ->actingAs($this->user)
            ->get(route('api.boards.show', $board->id));

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
                    'user' => [
                        'id',
                        'name',
                    ],
                    'columns' => [
                        '*' => [
                            'id',
                            'name',
                            'order',
                            '_links',
                        ]
                    ],
                    '_links',
                ],
            ])
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data.id', $board->id)
            ->assertJsonPath('data.name', $board->name);
    }

    public function test_get_board_details_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.boards.show', 'randomid'));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Board not found.');
    }

    public function test_rename_board_success(): void
    {
        $board = $this->user->collaborationBoards()->first();

        $requestBody = [
            'name' => 'Renamed Board',
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.boards.update', $board->id), $requestBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Board updated successfully.');

        $this->assertDatabaseHas('boards', [
            'id' => $board->id,
            'name' => $requestBody['name'],
        ]);
    }

    public function test_rename_board_not_found(): void
    {
        $requestBody = [
            'name' => 'Renamed Board',
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.boards.update', 'randomid'), $requestBody);

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Board not found.');
    }

    public function test_delete_board_success(): void
    {
        $board = $this->user->collaborationBoards()->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.boards.destroy', $board->id));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Board was successfully deleted.');

        $this->assertModelMissing($board);
    }

    public function test_delete_board_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.boards.destroy', 'randomid'));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Board not found.');
    }
}
