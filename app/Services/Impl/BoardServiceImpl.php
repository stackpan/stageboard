<?php

namespace App\Services\Impl;

use App\Dto\BoardDto;
use App\Models\Board;
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

    public function getAllByUserId(string $userId): Collection
    {
        return $this->boardRepository->getAllByUserId($userId);
    }

    public function create(string $userId, BoardDto $data): string
    {
        return $this->boardRepository->create($userId, $data);
    }

    public function get(string $id, ?array $columns = null): ?Board
    {
        return $this->boardRepository->get($id, $columns);
    }

    public function getByAliasId(string $aliasId, ?array $columns = null): ?Board
    {
        return $this->boardRepository->getByAliasId($aliasId, $columns);
    }

    public function update(string $id, BoardDto $data): void
    {
        $this->boardRepository->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->boardRepository->delete($id);
    }
}
