<?php

namespace App\Policies;

use App\Enums\BoardPermission;
use App\Models\Column;
use App\Models\User;
use App\Util\Permissions;

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
        return Permissions::checkColumnPermission($user, $column, BoardPermission::READ_ONLY);
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
        return Permissions::checkColumnPermission($user, $column, BoardPermission::LIMITED_ACCESS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Column $column): bool
    {
        return Permissions::checkColumnPermission($user, $column, BoardPermission::LIMITED_ACCESS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Column $column): bool
    {
        return Permissions::checkColumnPermission($user, $column, BoardPermission::FULL_ACCESS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Column $column): bool
    {
        return Permissions::checkColumnPermission($user, $column, BoardPermission::FULL_ACCESS);
    }

    public function swap(User $user, Column $column): bool
    {
        return Permissions::checkColumnPermission($user, $column, BoardPermission::LIMITED_ACCESS);
    }

    public function createCard(User $user, Column $column): bool
    {
        return Permissions::checkColumnPermission($user, $column, BoardPermission::CARD_OPERATOR);
    }

}
