<?php

namespace App\Services\Impl;

use App\Models\Board;
use App\Models\User;
use App\Repositories\BoardRepository;
use App\Services\BoardCollaborationService;

class BoardCollaborationServiceImpl implements BoardCollaborationService
{
    public function __construct(
        private readonly BoardRepository $boardRepository,
    )
    {
        //
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
