<?php

namespace App\Services\Impl;

use App\Enums\BoardPermission;
use App\Models\Board;
use App\Repositories\BoardRepository;
use App\Services\BoardCollaborationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

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

    /**
     * @throws Throwable
     */
    public function add(Board $board, string $userId): void
    {
        DB::transaction(fn () => $this->boardRepository->addCollaborator($board, $userId));
    }

    /**
     * @throws Throwable
     */
    public function remove(Board $board, string $userId): void
    {
        DB::transaction(fn () => $this->boardRepository->removeCollaborator($board, $userId));
    }

    /**
     * @throws Throwable
     */
    public function grantPermission(Board $board, string $userId, string $permission): void
    {
        DB::transaction(fn () => $this->boardRepository->grantCollaboratorPermission($board, $userId, $permission));
    }
}
