<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;
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
        return $this->checkOwner($user, $card) || $this->checkCollaborator($user, $card);
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
        return $this->checkOwner($user, $card) || $this->checkCollaborator($user, $card);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): bool
    {
        return $this->checkOwner($user, $card) || $this->checkCollaborator($user, $card);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Card $card): bool
    {
        return $this->checkOwner($user, $card);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Card $card): bool
    {
        return $this->checkOwner($user, $card);
    }

    public function move(User $user, Card $card): bool
    {
        return $this->checkOwner($user, $card) || $this->checkCollaborator($user, $card);
    }

    private function checkOwner(User $user, Card $card): bool
    {
        return $user->id === $card->column->board->owner_id;
    }

    private function checkCollaborator(User $user, Card $card): bool
    {
        return $card->column->board->users->find($user) !== null;
    }
}
