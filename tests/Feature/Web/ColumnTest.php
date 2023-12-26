<?php

namespace Tests\Feature\Web;

use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->board = $this->user->ownedBoards->first();
    }

    public function test_get_all_by_board()
    {
        $this->markTestSkipped();

        $response = $this
            ->actingAs($this->user)
            ->get(route('web.boards.columns.index', $this->board->id));

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'order',
                    'color',
                    'createdAt',
                    'updatedAt',
                    'cards' => [
                        '*' => [
                            'id',
                            'body',
                            'color',
                            'createdAt',
                            'updatedAt',
                        ],
                    ]
                ]
            ]);
    }


    public function test_get_one()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('web.columns.show', $this->board->columns()->first()));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'order',
                'color',
                'createdAt',
                'updatedAt',
            ]);
    }
}
