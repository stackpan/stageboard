<?php

namespace App\Services\Impl;

use App\Repositories\ColumnRepository;
use App\Services\ColumnService;
use Illuminate\Database\Eloquent\Collection;

class ColumnServiceImpl implements ColumnService
{
    public function __construct(
        private readonly ColumnRepository $columnRepository,
    )
    {
        //
    }

    public function getAllByBoardId(string $boardId): Collection
    {
        return $this->columnRepository->getAllByBoardId($boardId);
    }
}
