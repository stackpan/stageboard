<?php

namespace Tests\Feature\Web;

use App\Enums\BoardPermission;
use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardCollaboratorTest extends TestCase
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

    public function test_add_collaborator(): void
    {
        $collaborator = User::factory()->create();

        $requestBody = [
            'userId' => $collaborator->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('web.boards.collaborators.store', $this->board->id), $requestBody);

        $response->assertRedirect();

        $this->assertDatabaseHas('user_board', [
            'board_id' => $this->board->id,
            'user_id' => $collaborator->id,
            'permission' => BoardPermission::LIMITED_ACCESS->name,
        ]);
    }

    public function test_remove_collaborator(): void
    {
        $collaborator = User::factory()->create();

        $this->board->users()->attach($collaborator->id, ['permission' => BoardPermission::LIMITED_ACCESS]);

        $requestBody = [
            'userId' => $collaborator->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->delete(route('web.boards.collaborators.destroy', $this->board->id), $requestBody);

        $response->assertRedirect();

        $this->assertDatabaseMissing('user_board', [
            'board_id' => $this->board->id,
            'user_id' => $collaborator->id,
        ]);
    }

    public function test_add_collaborator_as_collaborator_should_forbidden(): void
    {
        $collaborators = User::factory()->count(2)->create();

        $this->board->users()->attach($collaborators[0]->id, [
            'permission' => BoardPermission::LIMITED_ACCESS
        ]);

        $requestBody = [
            'userId' => $collaborators[1]->id,
        ];

        $response = $this
            ->actingAs($collaborators[0])
            ->post(route('web.boards.collaborators.store', $this->board->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_remove_collaborator_as_collaborator_should_forbidden(): void
    {
        $collaborators = User::factory()->count(2)->create();

        foreach ($collaborators as $collaborator) {
            $this->board->users()->attach($collaborator->id, ['permission' => BoardPermission::LIMITED_ACCESS]);
        }

        $requestBody = [
            'userId' => $collaborators[1]->id,
        ];

        $response = $this
            ->actingAs($collaborators[0])
            ->delete(route('web.boards.collaborators.destroy', $this->board->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_get_collaborators(): void
    {
        $collaborator = User::factory()->create();

        $this->board->users()->attach($collaborator->id, ['permission' => BoardPermission::LIMITED_ACCESS]);

        $response = $this
            ->actingAs($this->user)
            ->get(route('web.boards.collaborators.show', $this->board->id));

        $response
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'firstName',
                    'lastName',
                    'email',
                    'emailVerifiedAt',
                ]
            ])
            ->assertJsonCount(2);
    }

    public function test_grant_permission_success(): void
    {
        $collaborator = User::factory()->create();

        $this->board->users()->attach($collaborator->id, ['permission' => BoardPermission::LIMITED_ACCESS->name]);

        $requestBody = [
            'userId' => $collaborator->id,
            'permission' => BoardPermission::CARD_OPERATOR->name,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.boards.collaborators.update', $this->board->id), $requestBody);

        $response->assertRedirect();

        $this->assertDatabaseHas('user_board', [
            'board_id' => $this->board->id,
            'user_id' => $collaborator->id,
            'permission' => BoardPermission::CARD_OPERATOR->name,
        ]);
    }

    public function test_grant_permission_to_full_access_must_be_failed(): void
    {
        $collaborator = User::factory()->create();

        $this->board->users()->attach($collaborator->id, ['permission' => BoardPermission::LIMITED_ACCESS->name]);

        $requestBody = [
            'userId' => $collaborator->id,
            'permission' => BoardPermission::FULL_ACCESS->name,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.boards.collaborators.update', $this->board->id), $requestBody);

        $response->assertRedirect();

        $this->assertDatabaseHas('user_board', [
            'board_id' => $this->board->id,
            'user_id' => $collaborator->id,
            'permission' => BoardPermission::LIMITED_ACCESS->name,
        ]);
    }
}
