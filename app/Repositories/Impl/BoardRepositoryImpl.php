<?php

namespace App\Repositories\Impl;

use App\Enums\BoardPermission;
use App\Models\User;
use App\Dto\BoardDto;
use App\Models\Board;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Repositories\BoardRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepositoryImpl implements BoardRepository
{
    public function getAllByUser(User $user): Collection
    {
        return Board::whereOwnerId($user->id)
            ->orWhereRelation('users', 'user_id', '=',$user->id)
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
        $board->users()->updateExistingPivot($user->id, ['opened_at' => now()]);
    }

    private function generateAliasId(): string
    {
        return fake()->lexify('??????-????-??');
    }

    public function getCollaborators(Board $board): Collection
    {
        return $board->users()->get();
    }

    public function addCollaborator(Board $board, string $userId, BoardPermission $permission = BoardPermission::LIMITED_ACCESS): void
    {
        $board->users()->attach($userId, [
            'permission' => $permission
        ]);
    }

    public function removeCollaborator(Board $board, string $userId): void
    {
        $board->users()->detach($userId);
    }
}
