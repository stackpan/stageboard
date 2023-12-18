<?php

namespace App\Services\Impl;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use App\Repositories\BoardRepository;
use App\Services\BoardService;
use Illuminate\Database\Eloquent\Collection;

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

    public function create(User $user, BoardDto $data): Board
    {
        return $this->boardRepository->create($user, $data);
    }

    public function getById(string $id, ?array $columns = null): ?Board
    {
        return $this->boardRepository->getById($id, $columns);
    }

    public function getByAliasId(string $aliasId, ?array $columns = null): ?Board
    {
        return $this->boardRepository->getByAliasId($aliasId, $columns);
    }

    public function update(Board $board, BoardDto $data): void
    {
        $this->boardRepository->update($board, $data);
    }

    public function delete(Board $board): void
    {
        $this->boardRepository->delete($board);
    }

    public function updateUserOpenedTime(Board $board, User $user): void
    {
        $this->boardRepository->updateUserOpenedTime($board, $user);
    }
}
