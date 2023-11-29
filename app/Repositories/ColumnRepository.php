<?php

namespace App\Repositories;

use App\Dto\ColumnDto;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface ColumnRepository
{
    public function getAllByBoardId(string $boardId): Collection;

    public function getCountByBoardId(string $boardId): int;

    public function create(string $boardId, ColumnDto $data): string;

    public function get(string $id, ?bool $nullable = false): ?Column;

    public function update(string $id, ColumnDto $data): void;

    public function delete(string $id): void;

    public function shift(string $boardId, int $fromOrder, ?int $toOrder = null): void;

    public function unshift(string $boardId, int $fromOrder, ?int $toOrder = null): void;

    public function move(string $id, int $toOrder): void;

}