<?php

namespace App\Dto;

class UpdateBoardDto
{
    public function __construct(
        public readonly string $name,
        public readonly bool $isPublic
    )
    {
        //
    }
}
