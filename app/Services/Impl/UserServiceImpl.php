<?php

namespace App\Services\Impl;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class UserServiceImpl implements UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
        //
    }

    public function getAll(int $limit = 10, ?User $exclude = null): Collection
    {
        return $this->userRepository->getAll($limit, $exclude);
    }

    public function search(string $keyword, int $limit = 6, ?User $exclude = null): Collection
    {
        return $this->userRepository->search($keyword, $limit, $exclude);
    }
}
