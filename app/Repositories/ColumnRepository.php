<?php

namespace App\Repositories;

use App\Dto\ColumnDto;
use Illuminate\Database\Eloquent\Collection;

interface ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection;

    public function create(string $boardId, ColumnDto $data): string;
}