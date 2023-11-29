<?php

namespace App\Repositories;

use App\Dto\CardDto;
use App\Models\Card;
use Illuminate\Database\Eloquent\Collection;

interface CardRepository
{
    public function getAllByColumnId(string $columnId): Collection;

    public function create(string $columnId, CardDto $data): string;

    public function get(string $id): ?Card;

    public function update(string $id, CardDto $data): void;

    public function delete(string $id): void;
    
    public function moveToColumn(string $id, string $columnId): void;
}
