<?php

namespace App\Services\Impl;

use App\Dto\ColumnDto;
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

    public function create(string $boardId, ColumnDto $data): string
    {
        return $this->columnRepository->create($boardId, $data);
    }
}
