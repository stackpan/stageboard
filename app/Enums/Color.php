<?php

namespace App\Enums;

enum Color: string
{
    case STONE = 'stone';
    case RED = 'red';
    case AMBER = 'amber';
    case LIME = 'lime';
    case EMERALD = 'emerald';
    case CYAN = 'cyan';
    case BLUE = 'blue';
    case VIOLET = 'violet';
    case FUCHSIA = 'fuchsia';
    case ROSE = 'rose';
    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}