<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
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
        return $this->checkOwner($user, $board) || $this->checkCollaborator($user, $board);
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
        return $this->checkOwner($user, $board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Board $board): bool
    {
        return $this->checkOwner($user, $board);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Board $board): bool
    {
        return $this->checkOwner($user, $board);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Board $board): bool
    {
        return $this->checkOwner($user, $board);
    }

    public function createColumn(User $user, Board $board): bool
    {
        return $this->checkOwner($user, $board) || $this->checkCollaborator($user, $board);
    }

    private function checkOwner(User $user, Board $board): bool
    {
        return $user->id === $board->owner_id;
    }

    private function checkCollaborator(User $user, Board $board): bool
    {
        return $board->users->find($user) !== null;
    }
}
