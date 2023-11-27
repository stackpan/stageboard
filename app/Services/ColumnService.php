<?php

namespace App\Services;

use App\Dto\ColumnDto;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnService
{
    public function getAllByBoardId(string $boardId): Collection;

    public function create(string $boardId, ColumnDto $data): string;
}