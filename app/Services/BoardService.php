<?php

namespace App\Services;

use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardService
{
    public function getAllByUserId(string $userId): Collection;
    
    public function create(string $userId, BoardDto $data): string;
    
    public function getById(string $id): ?Board;
    
    public function updateById(string $id, BoardDto $data): void;
    
    public function deleteById(string $id): void;
}