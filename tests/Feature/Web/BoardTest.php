<?php

namespace Tests\Feature\Web;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_get_one()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('web.boards.show', $this->user->boards()->first()));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'aliasId',
                'name',
                'thumbnailUrl',
                'openedAt',
                'createdAt',
                'updatedAt',
                'user' => ['id', 'name'],
            ]);
    }


}
