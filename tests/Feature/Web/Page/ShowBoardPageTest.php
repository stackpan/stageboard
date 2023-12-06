<?php

namespace Tests\Feature\Web\Page;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ShowBoardPageTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();
    }

    public function test_show_board_page(): void
    {
        $board = $this->user->boards()->first();

        $response = $this
            ->actingAs($this->user)
            ->get(route('web.page.board.show', $board->alias_id));

        $response
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Board/Show')
                ->has('board', fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                    ->etc())
                ->has('columns.0.cards.0')
                ->etc()
            );
    }
}
