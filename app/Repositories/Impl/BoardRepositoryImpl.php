<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Dto\BoardDto;
use App\Models\Board;
use App\Models\UserBoard;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use App\Repositories\BoardRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepositoryImpl implements BoardRepository
{
    public function getAllByUser(User $user): Collection
    {
        return Board::whereOwnerId($user->id)
            ->with('owner:id,name')
            ->with(['users' => fn (BelongsToMany $query) => $query
                ->select('id')
                ->whereUserId($user->id)
                ->withPivot('opened_at')
            ])
            ->get();
    }

    public function create(User $user, BoardDto $data): Board
    {
        $aliasId = $this->generateAliasId();

        $board = new Board;
        $board->owner_id = $user->id;
        $board->alias_id = $aliasId;
        $board->name = $data->name;
        $board->save();

        return $board;
    }

    public function getById(string $id, ?array $columns): ?Board
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

    public function update(Board $board, BoardDto $data): void
    {
        $board->update([
                'name' => $data->name,
            ]);
    }

    public function delete(Board $board): void
    {
        $board->delete();
    }

    public function updateUserOpenedTime(Board $board, User $user): void
    {
        $userBoard = UserBoard::whereBoardId($board->id)
            ->whereUserId($user->id)
            ->first();

        if ($userBoard === null) {
            $pivot = new UserBoard;
            $pivot->board_id = $board->id;
            $pivot->user_id = $user->id;
            $pivot->save();

            return;
        }

        UserBoard::whereBoardId($board->id)
            ->whereUserId($user->id)
            ->update([
                'opened_at' => now(),
            ]);
    }

    private function generateAliasId(): string
    {
        return fake()->lexify('??????-????-??');
    }
}
