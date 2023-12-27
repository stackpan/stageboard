<?php

namespace App\Util;

use App\Enums\BoardPermission;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\User;

class Permissions
{
    public static function checkBoardPermission(User $user, Board $board, BoardPermission $requirement): bool
    {
        $permission = $board->getUserPermission($user);

        if (is_null($permission)) return false;

        return $permission->level() <= $requirement->level();
    }

    public static function checkColumnPermission(User $user, Column $column, BoardPermission $requirement): bool
    {
        return Permissions::checkBoardPermission($user, $column->board, $requirement);
    }

    public static function checkCardPermission(User $user, Card $card, BoardPermission $requirement): bool
    {
        return Permissions::checkBoardPermission($user, $card->column->board, $requirement);
    }
}
