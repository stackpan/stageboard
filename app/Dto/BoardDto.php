<?php

namespace App\Dto;

class BoardDto {
    public function __construct(
        private readonly string $name,
    )
    {
        //
    }
    
    public function name(): string
    {
        return $this->name;    
    }
}
