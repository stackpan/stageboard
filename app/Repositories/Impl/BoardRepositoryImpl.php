<?php

namespace App\Repositories\Impl;

use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\User;
use App\Repositories\BoardRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepositoryImpl implements BoardRepository
{
    public function getAllByUserId(string $userId): Collection
    {
        return Board::whereUserId($userId)
            ->with('user:id,name')
//            ->with('opened_at')
            ->get();
    }

    public function create(string $userId, BoardDto $data): string
    {
        $aliasId = $this->generateAliasId();

        $board = new Board;
        $board->user_id = $userId;
        $board->alias_id = $aliasId;
        $board->name = $data->name;
        $board->save();

        return $board->id;
    }

    public function get(string $id, ?array $columns): ?Board
    {
        $cols = $columns ?? '*';

        return Board::select($cols)
            ->whereId($id)
            ->with('user:id,name')
            ->with('columns.cards')
            ->firstOrFail();
    }

    public function update(string $id, BoardDto $data): void
    {
        Board::findOrFail($id)
            ->update([
                'name' => $data->name,
            ]);
    }

    public function delete(string $id): void
    {
        Board::findOrFail($id)
            ->delete();
    }

    private function generateAliasId(): string
    {
        return fake()->lexify('??????-????-??');
    }
}
