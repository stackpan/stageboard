<?php

namespace App\Services;

use App\Dto\CreateBoardDto;
use App\Dto\UpdateBoardDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface BoardService
{
    public function getAllByUser(User $user): Collection;

    public function create(User $user, CreateBoardDto $data): Board;

    public function getById(string $id, ?array $columns = null): ?Board;

    public function getByAliasId(string $aliasId, ?array $columns = null, bool $withRelation = true): ?Board;

    public function update(Board $board, UpdateBoardDto $data): void;

    public function delete(Board $board): void;

    public function updateUserOpenedTime(Board $board, User $user): void;
}
