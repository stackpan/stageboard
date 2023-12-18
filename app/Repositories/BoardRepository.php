<?php

namespace App\Repositories;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface BoardRepository
{
    public function getAllByUser(User $user): Collection;

    public function create(User $user, BoardDto $data): Board;

    public function getById(string $id, ?array $columns): ?Board;

    public function getByAliasId(string $aliasId, ?array $columns): ?Board;

    public function update(Board $board, BoardDto $data): void;

    public function delete(Board $board): void;

    public function updateUserOpenedTime(Board $board, User $user): void;
}
