<?php

namespace Tests\Feature\Web;

use App\Enums\BoardPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();
    }

    public function test_update_board_by_owner_success(): void
    {
        $board = $this->user->ownedBoards->first();

        $requestBody = [
            'name' => 'Renamed BoardPage',
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.boards.update', $board->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_delete_board_by_owner_success(): void
    {
        $board = $this->user->ownedBoards->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('web.boards.destroy', $board->id));

        $response->assertRedirect();
    }

    public function test_update_board_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();
        $board = $this->user->ownedBoards->first();

        $requestBody = [
            'name' => 'Renamed BoardPage',
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->patch(route('web.boards.update', $board->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_delete_board_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();
        $board = $this->user->ownedBoards->first();

        $response = $this
            ->actingAs($unauthorizedUser)
            ->delete(route('web.boards.destroy', $board->id));

        $response->assertForbidden();
    }

    public function test_update_board_by_collaborator_should_forbidden(): void
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::LIMITED_ACCESS]);

        $requestBody = [
            'name' => 'Renamed BoardPage',
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->patch(route('web.boards.update', $board->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_delete_board_by_collaborator_should_forbidden(): void
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::LIMITED_ACCESS]);

        $response = $this
            ->actingAs($collaborationUser)
            ->delete(route('web.boards.destroy', $board->id));

        $response->assertForbidden();
    }

    public function test_full_access_permission_level()
    {
        $board = $this->user->ownedBoards->first();

        $requestBody = [
            'name' => 'Renamed BoardPage',
        ];

        $this->actingAs($this->user)
            ->patch(route('web.boards.update', $board->id), $requestBody)->assertRedirect();
    }

    public function test_limited_access_permission_level()
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::LIMITED_ACCESS]);

        $this->actingAs($collaborationUser)
            ->delete(route('web.boards.destroy', $board->id))
            ->assertForbidden();

        $this->actingAs($collaborationUser)
            ->delete(route('web.columns.destroy', $board->columns()->first()->id))
            ->assertRedirect();
    }

    public function test_card_operator_permission_level()
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::CARD_OPERATOR]);

        $column = $board->columns()->first();

        $this->actingAs($collaborationUser)
            ->delete(route('web.columns.destroy', $column->id))
            ->assertForbidden();

        $this->actingAs($collaborationUser)
            ->delete(route('web.cards.destroy', $column->cards()->first()->id))
            ->assertRedirect();
    }

    public function test_limited_card_operator_permission_level()
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::LIMITED_CARD_OPERATOR]);

        $columns = $board->columns()->get();
        $card = $columns[0]->cards()->first();

        $this->actingAs($collaborationUser)
            ->delete(route('web.cards.destroy', $card->id))
            ->assertForbidden();

        $this->actingAs($collaborationUser)
            ->patch(route('web.cards.move', $card->id), [
                'columnId' => $columns[1]->id,
            ])
            ->assertRedirect();
    }

    public function test_read_only_permission_level()
    {
        $board = $this->user->ownedBoards->first();
        $collaborationUser = User::factory()->create();
        $board->users()->attach($collaborationUser, ['permission' => BoardPermission::READ_ONLY]);

        $columns = $board->columns()->get();
        $card = $columns[0]->cards()->first();

        $this->actingAs($collaborationUser)
            ->patch(route('web.cards.move', $card->id), [
                'columnId' => $columns[0]->id,
            ])
            ->assertForbidden();

        $this->actingAs($collaborationUser)
            ->get(route('web.page.board.show', $board->alias_id))
            ->assertOk();
    }

}
