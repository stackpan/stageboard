<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
        //
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->query('q');
        $result = is_null($q) ? [] : $this->userService->search($q);

        return response()->json([
            'users' => UserResource::collection($result),
        ]);
    }
}
