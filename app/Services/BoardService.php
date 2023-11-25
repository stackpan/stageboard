<?php

namespace App\Services;

use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardService
{
    public function getAllByUserId(string $userId): Collection;
    
    public function create(array $validated, string $userId): bool;
    
    public function getById(string $id): ?Board;
    
    public function updateById(string $id, array $validated): bool;
    
    public function deleteById(string $id): bool;
}