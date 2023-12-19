<?php

namespace App\Services\Impl;

use App\Models\Board;
use App\Repositories\BoardRepository;
use App\Services\BoardCollaborationService;
use Illuminate\Database\Eloquent\Collection;

class BoardCollaborationServiceImpl implements BoardCollaborationService
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
    )
    {
        //
    }

    public function getCollaborators(Board $board): Collection
    {
        return $this->boardRepository->getCollaborators($board);
    }

    public function add(Board $board, string $userId): void
    {
        $this->boardRepository->addCollaborator($board, $userId);
    }

    public function remove(Board $board, string $userId): void
    {
        $this->boardRepository->removeCollaborator($board, $userId);
    }
}
