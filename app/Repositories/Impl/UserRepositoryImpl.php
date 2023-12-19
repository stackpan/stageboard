<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserRepositoryImpl implements UserRepository
{

    public function getAll(int $limit = 10, ?User $exclude = null): Collection
    {
        $query = User::select();

        if (!is_null($exclude)) {
            $query->whereNot('id', $exclude->id);
        }

        return $query->limit($limit)->get();
    }

    public function search(string $keyword, int $limit = 6, ?User $exclude = null): Collection
    {
        $query = User::whereFullText(['name', 'email'], $keyword);

        if (!is_null($exclude)) {
            $query->whereNot('id', $exclude->id);
        }

        return $query->limit($limit)->get();
    }
}
