<?php

namespace App\Services;

use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardService
{
    public function getAllByUserId(string $userId): Collection;

    public function create(string $userId, BoardDto $data): Board;

    public function get(string $id, ?array $columns = null): ?Board;

    public function getByAliasId(string $aliasId, ?array $columns = null): ?Board;

    public function update(string $id, BoardDto $data): void;

    public function delete(string $id): void;
}
