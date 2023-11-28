<?php

namespace App\Repositories\Impl;

use App\Dto\ColumnDto;
use App\Models\Board;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Database\Eloquent\Collection;

class ColumnRepositoryImpl implements ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection
    {
        return Column::select(['id', 'name', 'order', 'board_id'])
            ->whereBoardId($boardId)
            ->get();
    }

    public function getCountByBoardId(string $boardId): int
    {
        return Column::whereBoardId($boardId)->count();
    }

    public function create(string $boardId, ColumnDto $data): string
    {
        return Board::findOrFail($boardId)
            ->columns()
            ->create([
                'name' => $data->name,
                'order' => $data->order,
            ])
            ->id;
    }

    public function get(string $id, ?bool $nullable = false): ?Column
    {
        $query = Column::whereId($id)
            ->with('cards:id,body,column_id');

        return $nullable ? $query->first() : $query->firstOrFail();
    }

    public function update(string $id, ColumnDto $data): void
    {
        Column::findOrFail($id)
            ->update([
                'name' => $data->name,
            ]);
    }

    public function delete(string $id): void
    {
        Column::findOrFail($id)
            ->delete();
    }

    public function shiftByBoardId(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->increment('order');
    }

    public function unshiftByBoardId(string $boardId, int $fromOrder, ?int $toOrder = null): void
    {
        $query = Column::whereBoardId($boardId)
            ->where('order', '>=', $fromOrder);

        if (!is_null($toOrder)) {
            $query->where('order', '<=', $toOrder);
        }

        $query->decrement('order');
    }

    public function move(string $id, int $toOrder): void
    {
        Column::findOrFail($id)
            ->update([
                'order' => $toOrder,
            ]);
    }
}