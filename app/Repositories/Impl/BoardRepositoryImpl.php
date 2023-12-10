<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\UserBoard;
use Illuminate\Support\Arr;
use App\Repositories\BoardRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepositoryImpl implements BoardRepository
{
    public function getAllByUserId(string $userId): Collection
    {
        return Board::whereOwnerId($userId)
            ->with('owner:id,name')
//            ->with('opened_at')
            ->get();
    }

    public function create(string $userId, BoardDto $data): Board
    {
        $aliasId = $this->generateAliasId();

        $board = new Board;
        $board->owner_id = $userId;
        $board->alias_id = $aliasId;
        $board->name = $data->name;
        $board->save();

        return $board;
    }

    public function get(string $id, ?array $columns): ?Board
    {
        $cols = $columns ?? '*';

        return Board::select($cols)
            ->whereId($id)
            ->with('owner:id,name')
            ->with('columns.cards')
            ->firstOrFail();
    }

    public function getByAliasId(string $aliasId, ?array $columns): ?Board
    {
        $cols = !is_null($columns) ? [...$columns, 'alias_id'] : '*';

        return Board::select($cols)
            ->whereAliasId($aliasId)
            ->with('owner:id,name')
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

    public function updateUserOpenedAt(string $id, string $userId): void
    {
        $userBoard = UserBoard::whereBoardId($id)
            ->whereUserId($userId)
            ->first();

        if ($userBoard === null) {
            $pivot = new UserBoard;
            $pivot->board_id = $id;
            $pivot->user_id = $userId;
            $pivot->opened_at = now();
            $pivot->save();

            return;
        }

        UserBoard::whereBoardId($id)
            ->whereUserId($userId)
            ->update([
                'opened_at' => now(),
            ]);
    }

    private function generateAliasId(): string
    {
        return fake()->lexify('??????-????-??');
    }
}
