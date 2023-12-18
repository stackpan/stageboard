<?php

namespace App\Repositories;

use App\Dto\ColumnDto;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnRepository
{
    public function getAllByBoard(Board $board): Collection;

    public function getCountByBoard(Board $board): int;

    public function create(Board $board, ColumnDto $data): string;

    public function getById(string $id, ?bool $nullable = false, ?bool $withRelation = true): ?Column;

    public function update(Column $column, ColumnDto $data): void;

    public function delete(Column $column): void;

    public function shift(string $boardId, int $fromOrder, ?int $toOrder = null): void;

    public function unshift(string $boardId, int $fromOrder, ?int $toOrder = null): void;

    public function swap(Column $column, int $toOrder): void;
}
