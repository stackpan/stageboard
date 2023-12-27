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

    public function test_generate_success(): void
    {
        $freshBoard = Board::factory()->for($this->user, 'owner')->create();

        $response = $this
            ->actingAs($this->user)
            ->post(route('web.boards.columns.generate', $freshBoard->id));

        $response->assertRedirect();

        $this->assertEquals(4, $freshBoard->columns()->count());
    }

    public function test_generate_failed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('web.boards.columns.generate', $this->board->id));

        $response
            ->assertForbidden()
            ->assertJsonPath('message', 'The board already has columns');
    }


}
