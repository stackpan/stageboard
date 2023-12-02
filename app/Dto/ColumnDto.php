<?php

namespace App\Dto;

use App\Enums\Color;

class ColumnDto {
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $order = null,
        public readonly ?Color $color = null,
    )
    {
        //
    }
}
