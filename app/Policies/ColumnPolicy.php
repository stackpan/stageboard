<?php

namespace App\Policies;

use App\Models\Column;
use App\Models\User;

class ColumnPolicy
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
    public function view(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column) || $this->checkCollaborator($user, $column);
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
    public function update(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column) || $this->checkCollaborator($user, $column);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column) || $this->checkCollaborator($user, $column);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column);
    }

    public function swap(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column) || $this->checkCollaborator($user, $column);
    }

    public function createCard(User $user, Column $column): bool
    {
        return $this->checkOwner($user, $column) || $this->checkCollaborator($user, $column);
    }

    private function checkOwner(User $user, Column $column): bool
    {
        return $user->id === $column->board->owner_id;
    }

    private function checkCollaborator(User $user, Column $column): bool
    {
        return $column->board->users->find($user) !== null;
    }
}
