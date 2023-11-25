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
        return Board::whereUserId($userId)->with('user:id,name')->get();
    }
    
    public function create(BoardDto $data, string $userId): bool
    {
        $user = User::find($userId);
        
        $user->boards()->create([
            'name' => $data->name(),
        ]);
        
        return true;
    }
    
    public function getById(string $id): ?Board
    {
        return Board::whereId($id)->with('user:id,name')->first();
    }
    
    public function updateById(string $id, BoardDto $data): bool
    {
        if (!$board = Board::find($id)) {
            return false;
        }
        
        $board->update(['name' => $data->name()]);
        
        return $board->wasChanged();
    }
    
    public function deleteById(string $id): bool
    {
        if (!$board = Board::find($id)) {
            return false;
        }
        
        $board->delete();
        
        return true;
    }
}
