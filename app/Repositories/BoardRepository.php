<?php

namespace App\Repositories;

use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardRepository
{
    public function getAllByUserId(string $userId): Collection;
    
    public function create(string $userId, BoardDto $data): string;
    
    public function get(string $id, ?array $columns): ?Board;

    public function update(string $id, BoardDto $data): void;
    
    public function delete(string $id): void;
}
