<?php

namespace App\Traits;

trait HasEnumNameCases
{
    public static function nameCases(): array
    {
        return array_column(self::cases(), 'name');
    }
}
