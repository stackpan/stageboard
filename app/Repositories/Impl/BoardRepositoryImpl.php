<?php

namespace App\Repositories\Impl;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use App\Repositories\BoardRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepositoryImpl implements BoardRepository
{
    public function getAllByUserId(string $userId): Collection
    {
        return Board::select([
                'id', 'name', 'thumbnail_url', 'user_id', 'created_at', 'updated_at',
            ])
            ->whereUserId($userId)
            ->with('user:id,name')
//            ->with('opened_at')
            ->get();
    }

    public function create(string $userId, BoardDto $data,): string
    {
        return User::findOrFail($userId)
            ->boards()
            ->create([
                'name' => $data->name,
            ])
            ->id;
    }

    public function get(string $id, ?array $columns): ?Board
    {
        $cols = $columns ?? ['id', 'name', 'created_at', 'updated_at', 'user_id'];

        return Board::select($cols)
            ->whereId($id)
            ->with('user:id,name')
            ->with([
                'columns:id,name,order,color,board_id',
                'columns.cards:id,body,color,column_id'
            ])
            ->firstOrFail();
    }

    public function update(string $id, BoardDto $data): void
    {
        Board::findOrFail($id)
            ->update([
                'name' => $data->name,
            ]);
    }

    public function delete(string $id): void
    {
        Board::findOrFail($id)
            ->delete();
    }
}
