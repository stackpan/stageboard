<?php

namespace Tests\Feature\Web;

use App\Models\Board;
use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardAuthorizationTest extends TestCase
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

    public function test_create_card_by_owner_should_success(): void
    {
        $requestBody = [
            'body' => 'Doing something...',
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('web.columns.cards.store', $this->column->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_update_card_by_owner_should_success(): void
    {
        $card = $this->column->cards()->first();

        $requestBody = [
            'body' => 'Updated card...',
            'color' => $card->color->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.cards.update', $card->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_delete_card_by_owner_should_success(): void
    {
        $card = $this->column->cards()->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('web.cards.destroy', $card->id));

        $response->assertRedirect();
    }

    public function test_move_card_by_owner_should_success(): void
    {
        $card = $this->column->cards()->first();
        $destinationColumn = Column::whereBoardId($this->column->board_id)
            ->whereNot('id', $this->column->id)
            ->first();

        $requestBody = [
            'column_id' => $destinationColumn->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.cards.move', $card->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_create_card_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $requestBody = [
            'body' => 'Doing something...',
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->post(route('web.columns.cards.store', $this->column->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_update_card_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $card = $this->column->cards()->first();

        $requestBody = [
            'body' => 'Updated card...',
            'color' => $card->color->value,
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->patch(route('web.cards.update', $card->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_delete_card_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $card = $this->column->cards()->first();

        $response = $this
            ->actingAs($collaborationUser)
            ->delete(route('web.cards.destroy', $card->id));

        $response->assertRedirect();
    }

    public function test_move_card_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $card = $this->column->cards()->first();
        $destinationColumn = Column::whereBoardId($this->column->board_id)
            ->whereNot('id', $this->column->id)
            ->first();

        $requestBody = [
            'column_id' => $destinationColumn->id,
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->patch(route('web.cards.move', $card->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_create_card_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $requestBody = [
            'body' => 'Doing something...',
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->post(route('web.columns.cards.store', $this->column->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_update_card_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $card = $this->column->cards()->first();

        $requestBody = [
            'body' => 'Updated card...',
            'color' => $card->color->value,
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->patch(route('web.cards.update', $card->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_delete_card_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $card = $this->column->cards()->first();

        $response = $this
            ->actingAs($unauthorizedUser)
            ->delete(route('web.cards.destroy', $card->id));

        $response->assertForbidden();
    }

    public function test_move_card_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $columns = $this->board->columns()->get();

        $card = $columns[0]->cards()->first();
        $destinationColumn = $columns[1];

        $requestBody = [
            'columnId' => $destinationColumn->id,
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->patch(route('web.cards.move', $card->id), $requestBody);

        $response->assertForbidden();
    }
}
