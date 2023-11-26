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
        return Board::whereUserId($userId)
            ->with('user:id,name')
            ->get();
    }
    
    public function create(BoardDto $data, string $userId): string
    {
        return User::findOrFail($userId)
            ->boards()
            ->create([
                'name' => $data->name(),
            ])
            ->id;
    }
    
    public function getById(string $id): ?Board
    {
        return Board::whereId($id)
            ->with('user:id,name')
            ->firstOrFail();
    }
    
    public function updateById(string $id, BoardDto $data): void
    {
        Board::findOrFail($id)
            ->update([
                'name' => $data->name(),
            ]);
    }
    
    public function deleteById(string $id): void
    {
        Board::findOrFail($id)
            ->delete();
    }
}
