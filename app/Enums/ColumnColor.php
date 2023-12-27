<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum ColumnColor: string
{
    use HasEnumValues;

    case RED = '#f87171';
    case AMBER = '#fbbf24';
    case LIME = '#a3e635';
    case EMERALD = '#34d399';
    case CYAN = '#22d3ee';
    case BLUE = '#60a5fa';
    case VIOLET = '#a78bfa';
    case FUCHSIA = '#e879f9';

}
