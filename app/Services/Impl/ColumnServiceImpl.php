<?php

namespace App\Services\Impl;

use App\Dto\ColumnDto;
use App\Repositories\ColumnRepository;
use App\Services\ColumnService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($boardId, $data) {
            if ($data->nextColumnId) {
                $prevColumn = $this->columnRepository->getPrev($data->nextColumnId);
                if (!is_null($prevColumn)) {
                    $this->columnRepository->unlink($prevColumn->id);
                }
            } else {
                $prevColumn = $this->columnRepository->getLast($boardId);
            }

            $newColumnId = $this->columnRepository->create($boardId, $data);

            if (!is_null($prevColumn)) {
                $this->columnRepository->link($prevColumn->id, $newColumnId);
            }

            return $newColumnId;
        });
    }
}
