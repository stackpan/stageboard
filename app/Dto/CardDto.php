<?php

namespace App\Dto;

class CardDto
{
    public function __construct(
        public readonly string $body,
    )
    {
        //
    }
}