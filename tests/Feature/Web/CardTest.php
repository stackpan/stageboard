<?php

namespace Tests\Feature\Web;

use App\Models\Board;
use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{

    use RefreshDatabase;

    private User $user;
    private Board $board;
    private Column $column;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();

        $this->board = $this->user->ownedBoards->first();
        $this->column = $this->board->columns()->first();
    }

    public function test_get_one()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('web.cards.show', $this->column->cards()->first()));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'body',
                'color',
                'createdAt',
                'updatedAt',
            ]);
    }


}
