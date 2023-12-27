<?php

namespace App\Policies;

use App\Enums\BoardPermission;
use App\Models\Board;
use App\Models\User;
use App\Util\Permissions;
use Illuminate\Auth\Access\Response;

class BoardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::READ_ONLY);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::FULL_ACCESS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::FULL_ACCESS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::FULL_ACCESS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::FULL_ACCESS);
    }

    public function manageCollaborator(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::FULL_ACCESS);
    }

    public function createColumn(User $user, Board $board): bool
    {
        return Permissions::checkBoardPermission($user, $board, BoardPermission::LIMITED_ACCESS);
    }
}
