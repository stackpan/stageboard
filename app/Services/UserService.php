<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserService
{
    public function getAll(int $limit = 10, ?User $exclude = null): Collection;

    public function search(string $keyword, int $limit = 6, ?User $exclude = null): Collection;
}
