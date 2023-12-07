<?php

namespace App\Services\Impl;

use App\Dto\ColumnDto;
use App\Exceptions\ApplicationError\ZeroDeltaStepException;
use App\Models\Column;
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
        $this->columnRepository->shift($boardId, $data->order);
        return $this->columnRepository->create($boardId, $data);
    }

    public function get(string $id): ?Column
    {
        return $this->columnRepository->get($id);
    }

    public function update(string $id, ColumnDto $data): void
    {
        $this->columnRepository->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->columnRepository->delete($id);
    }

    public function swap(string $id, int $destinationOrder): void
    {
        $column = $this->columnRepository->get(id: $id, withRelation: false);
        $targetIndex = $column->order;
        $deltaStep = $destinationOrder - $targetIndex;

        if ($deltaStep === 0) {
            throw new ZeroDeltaStepException();
        }

        if ($deltaStep > 0) {
            $this->columnRepository->unshift(
                boardId: $column->board_id,
                fromOrder: $targetIndex + 1,
                toOrder: $targetIndex + $deltaStep
            );
        } else {
            $this->columnRepository->shift(
                boardId: $column->board_id,
                fromOrder: $destinationOrder,
                toOrder: $destinationOrder - $deltaStep - 1
            );
        }

        $this->columnRepository->swap($id, $destinationOrder);
    }
}
