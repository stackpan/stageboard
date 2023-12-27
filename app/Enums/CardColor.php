<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum CardColor: string
{
    use HasEnumValues;

    case STONE = '#f5f5f4';
    case RED = '#fee2e2';
    case AMBER = '#fef3c7';
    case LIME = '#ecfccb';
    case EMERALD = '#d1fae5';
    case CYAN = '#cffafe';
    case BLUE = '#dbeafe';
    case VIOLET = '#ede9fe';
    case FUCHSIA = '#fae8ff';
    case ROSE = '#ffe4e6';

}
