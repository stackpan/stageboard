<?php

namespace App\Services\Impl;

use App\Dto\CardDto;
use App\Models\Card;
use App\Models\Column;
use App\Repositories\CardRepository;
use App\Services\CardService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

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

    /**
     * @throws Throwable
     */
    public function create(Column $column, CardDto $data): string
    {
        return DB::transaction(fn () => $this->cardRepository->create($column, $data));
    }

    public function getById(string $id): ?Card
    {
        return $this->cardRepository->getById($id);
    }

    /**
     * @throws Throwable
     */
    public function update(Card $card, CardDto $data): void
    {
        DB::transaction(fn () => $this->cardRepository->update($card, $data));
    }

    /**
     * @throws Throwable
     */
    public function delete(Card $card): void
    {
        DB::transaction(fn () => $this->cardRepository->delete($card));
    }

    /**
     * @throws Throwable
     */
    public function moveToColumn(Card $card, string $columnId): void
    {
        DB::transaction(fn () => $this->cardRepository->moveToColumn($card, $columnId));
    }
}
