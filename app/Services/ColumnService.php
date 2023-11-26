<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

interface ColumnService
{
    public function getAllByBoardId(string $boardId): Collection;
}