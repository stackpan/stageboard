<?php

namespace App\Services\Impl;

use App\Dto\ColumnDto;
use App\Exceptions\ApplicationError\ZeroDeltaStepException;
use App\Models\Board;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use App\Services\ColumnService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

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

    /**
     * @throws Throwable
     */
    public function create(Board $board, ColumnDto $data): string
    {
        return DB::transaction(function () use ($board, $data) {
            $this->columnRepository->shift($board->id, $data->order);
            return $this->columnRepository->create($board, $data);
        });
    }

    public function getById(string $id): ?Column
    {
        return $this->columnRepository->getById($id);
    }

    /**
     * @throws Throwable
     */
    public function update(Column $column, ColumnDto $data): void
    {
        DB::transaction(fn () => $this->columnRepository->update($column, $data));
    }

    /**
     * @throws Throwable
     */
    public function delete(Column $column): void
    {
        DB::transaction(fn () => $this->columnRepository->delete($column));
    }

    /**
     * @throws ZeroDeltaStepException
     * @throws Throwable
     */
    public function swap(Column $column, int $destinationOrder): void
    {
        DB::transaction(function () use ($column, $destinationOrder) {
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
        });
    }
}
