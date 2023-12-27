<?php

namespace App\Policies;

use App\Enums\BoardPermission;
use App\Models\Card;
use App\Models\User;
use App\Util\Permissions;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class CardPolicy
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
    public function view(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::READ_ONLY);
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
    public function update(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::CARD_OPERATOR);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::CARD_OPERATOR);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::FULL_ACCESS);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::FULL_ACCESS);
    }

    public function move(User $user, Card $card): bool
    {
        return Permissions::checkCardPermission($user, $card, BoardPermission::LIMITED_CARD_OPERATOR);
    }

}
