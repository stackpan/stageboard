<?php

namespace App\Dto;

class CreateBoardDto
{
    public function __construct(
        public readonly string $name,
    )
    {
        //
    }
}
