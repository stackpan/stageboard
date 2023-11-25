<?php

namespace App\Repositories;

use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardRepository
{
    public function getAllByUserId(string $userId): Collection;
    
    public function create(BoardDto $data, string $userId): bool;
    
    public function getById(string $id): ?Board;

    public function updateById(string $id, BoardDto $data): bool;
    
    public function deleteById(string $id): bool;
}
