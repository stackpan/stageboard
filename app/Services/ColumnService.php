<?php

namespace App\Services;

use App\Dto\ColumnDto;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnService
{
    public function getAllByBoard(Board $board): Collection;

    public function create(Board $board, ColumnDto $data): string;

    public function generate(Board $board): void;

    public function getById(string $id): ?Column;

    public function update(Column $column, ColumnDto $data): void;

    public function delete(Column $column): void;

    public function swap(Column $column, int $destinationOrder): void;
}
