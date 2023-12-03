<?php

namespace Tests\Feature\Api;

use App\Enums\Color;
use App\Models\Column;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;
    
    private User $user;
    private Column $column;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed();

        $this->user = User::whereEmail('test@example.com')->first();
        
        $board = $this->user->boards()->first();
        $this->column = $board->columns()->first();
    }
    
    public function test_get_all_card_within_column(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.columns.cards.index', $this->column->id));
        
        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'body',
                        '_links',
                    ],
                ],
            ])
            ->assertJsonPath('message', 'Success.');
    }
    
    public function test_create_card_success(): void
    {
        $requestBody = [
            'body' => 'Doing something...',
        ];
        
        $response = $this
            ->actingAs($this->user)
            ->post(route('api.columns.cards.store', $this->column->id), $requestBody);
        
        $response
            ->assertCreated()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'card' => [
                        'id',
                    ],
                ],
            ])
            ->assertJsonPath('message', 'Card created successfully.');
        
        $this->assertDatabaseHas('cards', [
            'body' => $requestBody['body'],
        ]);
    }
    
    public function test_get_card_details_success(): void
    {
        $card = $this->column->cards()->first();

        $response = $this
            ->actingAs($this->user)
            ->get(route('api.cards.show', $card->id));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'body',
                    'created_at',
                    'updated_at',
                    '_links',
                ],
            ])
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data.id', $card->id)
            ->assertJsonPath('data.name', $card->name);
    }

    public function test_get_card_details_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.cards.show', 'fictionalid'));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card not found.');
    }

    public function test_update_card_success(): void
    {
        $card = $this->column->cards()->first();

        $requestBody = [
            'body' => 'Updated card...',
            'color' => $card->color->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.cards.update', $card->id), $requestBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card updated successfully.');

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'body' => $requestBody['body'],
            'color' => $requestBody['color'],
        ]);
    }

    public function test_update_card_not_found(): void
    {
        $requestBody = [
            'body' => 'Updated card...',
            'color' => fake()->randomElement(Color::class)->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.cards.update', 'fictionalid'), $requestBody);

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card not found.');
    }

    public function test_delete_card_success(): void
    {
        $card = $this->column->cards()->first();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.cards.destroy', $card->id));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card was successfully deleted.');

        $this->assertModelMissing($card);
    }

    public function test_delete_card_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('api.cards.destroy', 'fictionalid'));

        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card not found.');
    }
    
    public function test_move_card_success(): void
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
            ->patch(route('api.cards.move', $card->id), $requestBody);

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card was successfully moved.');

        $this->assertEquals($destinationColumn->id, $card->fresh()->column_id);
    }
    
    public function test_move_card_failed(): void
    {
        $card = $this->column->cards()->first();

        $requestBody = [
            'column_id' => $this->column->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.cards.move', $card->id), $requestBody);

        $response
            ->assertBadRequest()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card cannot be moved because the card is already the member of the given column.');
    }
    
    public function test_move_card_to_column_of_cross_board_member_should_failed(): void
    {
        $card = $this->column->cards()->first();
        $crossColumn = $this->user->boards()
            ->whereNot('id', $this->column->board_id)
            ->first()
            ->columns()
            ->first();
        
        $requestBody = [
            'column_id' => $crossColumn->id,
        ];

        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.cards.move', $card->id), $requestBody);

        $response
            ->assertBadRequest()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card cannot be moved because the given column is out of board member.');
    }
    
    public function test_move_card_not_found(): void
    {
        $destinationColumn = Column::whereNot('id', $this->column->id)->first();

        $requestBody = [
            'column_id' => $destinationColumn->id,
        ];
        
        $response = $this
            ->actingAs($this->user)
            ->patch(route('api.cards.move', 'fictionalid'), $requestBody);
        
        $response
            ->assertNotFound()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'message',
            ])
            ->assertJsonPath('message', 'Card not found.');
    }
}
