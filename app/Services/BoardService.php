<?php

namespace App\Services;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface BoardService
{
    public function getAllByUser(User $user): Collection;

    public function create(User $user, BoardDto $data): Board;

    public function getById(string $id, ?array $columns = null): ?Board;

    public function getByAliasId(string $aliasId, ?array $columns = null): ?Board;

    public function update(Board $board, BoardDto $data): void;

    public function delete(Board $board): void;

    public function updateUserOpenedTime(Board $board, User $user): void;
}
