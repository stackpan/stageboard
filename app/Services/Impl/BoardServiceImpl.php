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
    
    public function create(array $validated, string $userId): string
    {
        $data = new BoardDto(name: $validated['name']);
        
        return $this->boardRepository->create($data, $userId);
    }
    
    public function getById(string $id): ?Board
    {
        return $this->boardRepository->getById($id);
    }
    
    public function updateById(string $id, array $validated): void
    {
        $data = new BoardDto(name: $validated['name']);
        
        $this->boardRepository->updateById($id, $data);
    }
    
    public function deleteById(string $id): void
    {
        $this->boardRepository->deleteById($id);
    }
}