<?php

namespace App\Services;

use App\Dto\CardDto;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Database\Eloquent\Collection;

interface CardService
{
    public function getAllByColumn(Column $column): Collection;

    public function create(Column $column, CardDto $data): string;

    public function getById(string $id): ?Card;

    public function update(Card $card, CardDto $data): void;

    public function delete(Card $card): void;

    public function moveToColumn(Card $card, string $columnId): void;
}
