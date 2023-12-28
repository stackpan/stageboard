<?php

namespace App\Services\Impl;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use App\Repositories\BoardRepository;
use App\Services\BoardService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class BoardServiceImpl implements BoardService
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
    )
    {
        //
    }

    public function getAllByUser(User $user): Collection
    {
        return $this->boardRepository->getAllByUser($user);
    }

    /**
     * @throws Throwable
     */
    public function create(User $user, BoardDto $data): Board
    {
        return DB::transaction(fn () => $this->boardRepository->create($user, $data));
    }

    public function getById(string $id, ?array $columns = null): ?Board
    {
        return $this->boardRepository->getById($id, $columns);
    }

    public function getByAliasId(string $aliasId, ?array $columns = null, bool $withRelation = true): ?Board
    {
        return $this->boardRepository->getByAliasId($aliasId, $columns, $withRelation);
    }

    /**
     * @throws Throwable
     */
    public function update(Board $board, BoardDto $data): void
    {
        DB::transaction(fn () => $this->boardRepository->update($board, $data));
    }

    /**
     * @throws Throwable
     */
    public function delete(Board $board): void
    {
        DB::transaction(fn () => $this->boardRepository->delete($board));
    }

    /**
     * @throws Throwable
     */
    public function updateUserOpenedTime(Board $board, User $user): void
    {
        DB::transaction(fn () => $this->boardRepository->updateUserOpenedTime($board, $user));
    }
}
