<?php

namespace App\Dto;

class ColumnDto {
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?int $order = null,
        public readonly ?string $color = null,
    )
    {
        //
    }
}
