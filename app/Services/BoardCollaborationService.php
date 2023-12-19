<?php

namespace App\Services;

use App\Models\Board;
use Illuminate\Database\Eloquent\Collection;

interface BoardCollaborationService
{
    public function getCollaborators(Board $board): Collection;

    public function add(Board $board, string $userId): void;

    public function remove(Board $board, string $userId): void;
}
