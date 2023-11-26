<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection;
}