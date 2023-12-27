<?php

namespace App\Enums;

use App\Traits\HasEnumNameCases;

enum BoardPermission: string
{
    use HasEnumNameCases;

    case FULL_ACCESS = 'FULL_ACCESS';

    case LIMITED_ACCESS  = 'LIMITED_ACCESS';

    case CARD_OPERATOR  = 'CARD_OPERATOR';

    case LIMITED_CARD_OPERATOR = 'LIMITED_CARD_OPERATOR';

    case READ_ONLY = 'READ_ONLY';

    public function level(): int
    {
        return match ($this) {
            BoardPermission::FULL_ACCESS => 0,
            BoardPermission::LIMITED_ACCESS => 1,
            BoardPermission::CARD_OPERATOR => 2,
            BoardPermission::LIMITED_CARD_OPERATOR => 3,
            BoardPermission::READ_ONLY => 4,
        };
    }

}
