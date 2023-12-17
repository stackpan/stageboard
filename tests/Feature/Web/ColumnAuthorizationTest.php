<?php

namespace Tests\Feature\Web;

use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ColumnAuthorizationTest extends TestCase
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

    public function test_create_column_by_owner_should_success(): void
    {
        $requestBody = [
            'name' => 'Test Column',
            'order' => 0,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('web.boards.columns.store', $this->board->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_update_column_by_owner_should_success(): void
    {
        $column = $this->board->columns()->first();

        $requestBody = [
            'name' => 'Updated Column',
            'color' => $column->color->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.columns.update', $column->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_delete_column_by_owner_should_success(): void
    {
        $column = $this->board->columns()->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('web.columns.destroy', $column->id));

        $response->assertRedirect();
    }

    public function test_swap_column_by_owner_should_success(): void
    {
        $targetColumn = $this->board->columns()->whereOrder(0)->first();

        $responseBody = [
            'order' => $targetColumn->order + 1,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('web.columns.swap', $targetColumn->id), $responseBody);

        $response->assertRedirect();
    }

    public function test_create_column_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $requestBody = [
            'name' => 'Test Column',
            'order' => 0,
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->post(route('web.boards.columns.store', $this->board->id), $requestBody);

        $response->assertRedirect();
    }

    public function test_update_column_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $column = $this->board->columns()->first();

        $requestBody = [
            'name' => 'Updated Column',
            'color' => $column->color->value,
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->patch(route('web.columns.update', $column->id), $requestBody);

        $response->assertRedirect();

    }

    public function test_delete_column_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $column = $this->board->columns()->first();

        $response = $this
            ->actingAs($collaborationUser)
            ->delete(route('web.columns.destroy', $column->id));

        $response->assertRedirect();
    }

    public function test_swap_column_by_collaborator_should_success(): void
    {
        $collaborationUser = User::factory()->create();
        $this->board->users()->save($collaborationUser);

        $targetColumn = $this->board->columns()->whereOrder(0)->first();

        $responseBody = [
            'order' => $targetColumn->order + 1,
        ];

        $response = $this
            ->actingAs($collaborationUser)
            ->patch(route('web.columns.swap', $targetColumn->id), $responseBody);

        $response->assertRedirect();
    }

    public function test_create_column_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $requestBody = [
            'name' => 'Test Column',
            'order' => 0,
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->post(route('web.boards.columns.store', $this->board->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_update_column_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $column = $this->board->columns()->first();

        $requestBody = [
            'name' => 'Updated Column',
            'color' => $column->color->value,
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->patch(route('web.columns.update', $column->id), $requestBody);

        $response->assertForbidden();
    }

    public function test_delete_column_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $column = $this->board->columns()->first();

        $response = $this
            ->actingAs($unauthorizedUser)
            ->delete(route('web.columns.destroy', $column->id));

        $response->assertForbidden();
    }

    public function test_swap_column_by_unauthorized_user_should_forbidden(): void
    {
        $unauthorizedUser = User::factory()->create();

        $targetColumn = $this->board->columns()->whereOrder(0)->first();

        $responseBody = [
            'order' => $targetColumn->order + 1,
        ];

        $response = $this
            ->actingAs($unauthorizedUser)
            ->patch(route('web.columns.swap', $targetColumn->id), $responseBody);

        $response->assertForbidden();
    }
}
