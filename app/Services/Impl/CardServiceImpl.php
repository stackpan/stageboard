<?php

namespace App\Services\Impl;

use App\Dto\CardDto;
use App\Models\Card;
use App\Repositories\CardRepository;
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

    public function getAllByColumnId(string $columnId): Collection
    {
        return $this->cardRepository->getAllByColumnId($columnId);
    }

    public function create(string $columnId, CardDto $data): string
    {
        return $this->cardRepository->create($columnId, $data);
    }

    public function get(string $id): ?Card
    {
        return $this->cardRepository->get($id);
    }

    public function update(string $id, CardDto $data): void
    {
        $this->cardRepository->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->cardRepository->delete($id);
    }

    public function moveToColumn(string $id, string $columnId): void
    {
        $this->cardRepository->moveToColumn($id, $columnId);
    }
}