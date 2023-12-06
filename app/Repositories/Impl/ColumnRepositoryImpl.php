<?php

namespace App\Repositories\Impl;

use App\Dto\ColumnDto;
use App\Enums\Color;
use App\Models\Board;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Database\Eloquent\Collection;

class ColumnRepositoryImpl implements ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection
    {
        return Column::whereBoardId($boardId)
            ->with('cards')
            ->get();
    }

    public function getCountByBoardId(string $boardId): int
    {
        return Column::whereBoardId($boardId)->count();
    }

    public function create(string $boardId, ColumnDto $data): string
    {
        $color = $data->color ?? fake()->randomElement(Color::class);

        return Board::findOrFail($boardId)
            ->columns()
            ->create([
                'name' => $data->name,
                'order' => $data->order,
                'color' => $color,
            ])
            ->id;
    }

    public function get(string $id, ?bool $nullable = false): ?Column
    {
        $query = Column::whereId($id)
            ->with('cards');

        return $nullable ? $query->first() : $query->firstOrFail();
    }

    public function update(string $id, ColumnDto $data): void
    {
        $column = Column::findOrFail($id)
            ->update([
                'name' => $data->name,
                'color' => $data->color,
            ]);
    }

    public function delete(string $id): void
    {
        Column::findOrFail($id)
            ->delete();
    }

    public function shift(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->increment('order');
    }

    public function unshift(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->decrement('order');
    }

    public function swap(string $id, int $toOrder): void
    {
        Column::findOrFail($id)
            ->update([
                'order' => $toOrder,
            ]);
    }
}
