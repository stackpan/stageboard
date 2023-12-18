<?php

namespace App\Services\Impl;

use App\Dto\CardDto;
use App\Models\Card;
use App\Models\Column;
use App\Repositories\CardRepository;
use App\Repositories\ColumnRepository;
use App\Services\CardService;
use Illuminate\Database\Eloquent\Collection;

class CardServiceImpl implements CardService
{
    public function __construct(
        private readonly CardRepository $cardRepository,
    )
    {
        //
    }

    public function getAllByColumn(Column $column): Collection
    {
        return $this->cardRepository->getAllByColumn($column);
    }

    public function create(Column $column, CardDto $data): string
    {
        return $this->cardRepository->create($column, $data);
    }

    public function getById(string $id): ?Card
    {
        return $this->cardRepository->getById($id);
    }

    public function update(Card $card, CardDto $data): void
    {
        $this->cardRepository->update($card, $data);
    }

    public function delete(Card $card): void
    {
        $this->cardRepository->delete($card);
    }

    public function moveToColumn(Card $card, string $columnId): void
    {
        $this->cardRepository->moveToColumn($card, $columnId);
    }
}
