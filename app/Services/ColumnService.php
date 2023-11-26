<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

interface ColumnService
{
    public function getAllByBoardId(string $boardId): Collection;

    public function create(string $boardId, array $validated): string;
}