<?php

namespace App\Dto;

use App\Enums\Color;

class CardDto
{
    public function __construct(
        public readonly ?string $body = null,
        public readonly ?string $color = null,
    )
    {
        //
    }
}