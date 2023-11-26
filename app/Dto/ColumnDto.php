<?php

namespace App\Dto;

class ColumnDto {
    public function __construct(
        private readonly ?string $name = null,
        private readonly ?string $nextColumnId = null,
    )
    {
        //
    }
    
    public function name(): ?string
    {
        return $this->name;    
    }
    
    public function nextColumnId(): ?string
    {
        return $this->nextColumnId;
    }
}
