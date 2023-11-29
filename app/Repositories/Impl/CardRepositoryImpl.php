<?php

namespace App\Repositories\Impl;

use App\Dto\CardDto;
use App\Exceptions\ApplicationError\AlreadyOfColumnMemberException;
use App\Models\Card;
use App\Models\Column;
use App\Repositories\CardRepository;
use Illuminate\Database\Eloquent\Collection;

class CardRepositoryImpl implements CardRepository
{
    public function getAllByColumnId(string $columnId): Collection
    {
        return Card::select(['id', 'body', 'column_id'])
            ->whereColumnId($columnId)
            ->get();
    }

    public function create(string $columnId, CardDto $data): string
    {
        return Column::findOrFail($columnId)
            ->cards()
            ->create([
                'body' => $data->body,
            ])
            ->id;
    }

    public function get(string $id): ?Card
    {
        return Card::findOrFail($id);
    }

    public function update(string $id, CardDto $data): void
    {
        Card::findOrFail($id)
            ->update([
                'body' => $data->body,
            ]);
    }

    public function delete(string $id): void
    {
        Card::findOrFail($id)
            ->delete();
    }

    public function moveToColumn(string $id, string $columnId): void
    {
        $card = Card::findOrFail($id);
        
        if ($card->column_id === $columnId) {
            throw new AlreadyOfColumnMemberException();
        }
        
        $card->findOrFail($id)
            ->column()
            ->associate($columnId);
    }
}