<?php

namespace App\Services;

use App\Models\Board;
use App\Models\User;

interface BoardCollaborationService
{
    public function add(Board $board, string $userId): void;

    public function remove(Board $board, string $userId): void;
}
