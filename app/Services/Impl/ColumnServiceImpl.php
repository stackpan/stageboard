<?php

namespace App\Services\Impl;

use App\Dto\ColumnDto;
use App\Exceptions\ApplicationError\NotEmptyBoardException;
use App\Exceptions\ApplicationError\ZeroDeltaStepException;
use App\Models\Board;
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

    public function getAllByBoard(Board $board): Collection
    {
        return $this->columnRepository->getAllByBoard($board);
    }

    public function create(Board $board, ColumnDto $data): string
    {
        $this->columnRepository->shift($board->id, $data->order);
        return $this->columnRepository->create($board, $data);
    }

    /**
     * @throws NotEmptyBoardException
     */
    public function generate(Board $board): void
    {
        $count = $this->columnRepository->countByBoard($board);
        if ($count > 0) {
            throw new NotEmptyBoardException();
        }

        $this->columnRepository->generate($board);
    }

    public function getById(string $id): ?Column
    {
        return $this->columnRepository->getById($id);
    }

    public function update(Column $column, ColumnDto $data): void
    {
        $this->columnRepository->update($column, $data);
    }

    public function delete(Column $column): void
    {
        $this->columnRepository->delete($column);
    }
    public function swap(Column $column, int $destinationOrder): void
    {
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

        $this->columnRepository->swap($column, $destinationOrder);
    }
}
