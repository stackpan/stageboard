<?php

namespace App\Repositories;

use App\Dto\ColumnDto;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection;

    public function create(string $boardId, ColumnDto $data): string;

    public function getLast(string $boardId): ?Column;
    
    public function getPrev(string $columnId): ?Column;


    public function unlink(string $id): void;
    public function link(string $id, string $nextId): void;
}