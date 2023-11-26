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
        return Column::select(['id', 'name', 'next_column_id', 'board_id'])
            ->whereBoardId($boardId)
            ->get();
    }

    public function create(string $boardId, ColumnDto $data): string
    {
        return Board::findOrFail($boardId)
            ->columns()
            ->create([
                'name' => $data->name(),
                'next_column_id' => $data->nextColumnId(),
            ]);
    }
}