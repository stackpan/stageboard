<?php

namespace App\Enums;

enum CardColor: string
{
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

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
