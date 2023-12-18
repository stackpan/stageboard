<?php

namespace App\Repositories\Impl;

use App\Dto\CardDto;
use App\Enums\CardColor;
use App\Exceptions\ApplicationError\AlreadyOfColumnMemberException;
use App\Exceptions\ApplicationError\CrossColumnMemberException;
use App\Models\Card;
use App\Models\Column;
use App\Repositories\CardRepository;
use Illuminate\Database\Eloquent\Collection;

class CardRepositoryImpl implements CardRepository
{
    public function getAllByColumn(Column $column): Collection
    {
        return Card::whereColumnId($column->id)
            ->get();
    }

    public function create(Column $column, CardDto $data): string
    {
        $color = $data->color ?? fake()->randomElement(CardColor::class);

        return $column->cards()
            ->create([
                'body' => $data->body,
                'color' => $color,
            ])
            ->id;
    }

    public function getById(string $id): ?Card
    {
        return Card::findOrFail($id);
    }

    public function update(Card $card, CardDto $data): void
    {
        $card->update([
                'body' => $data->body,
                'color' => $data->color,
            ]);
    }

    public function delete(Card $card): void
    {
        $card->delete();
    }

    /**
     * @throws CrossColumnMemberException
     * @throws AlreadyOfColumnMemberException
     */
    public function moveToColumn(Card $card, string $columnId): void
    {
        if ($card->column_id === $columnId) {
            throw new AlreadyOfColumnMemberException();
        }

        $column = Column::findOrFail($columnId);

        if ($column->board_id !== $card->column->board_id) {
            throw new CrossColumnMemberException();
        }

        $card->column()
            ->associate($columnId)
            ->save();
    }
}
