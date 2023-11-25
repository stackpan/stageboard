<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BoardRequest;
use App\Http\Resources\BoardDetailsResource;
use App\Http\Resources\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        //
    }

    public function index(): JsonResponse
    {
        $user = auth()->user();

        $boards = $this->boardService->getAllByUserId($user->id);

        return response()->json([
            'message' => 'Success.',
            'data' => BoardResource::collection($boards),
        ]);
    }

    public function store(BoardRequest $request): JsonResponse
    {
        $user = auth()->user();

        $validated = $request->validated();

        $this->boardService->create($validated, $user->id);

        return response()
            ->json([
                'message' => 'Board created successfully.',
            ])
            ->setStatusCode(201);
    }

    public function show(string $id): JsonResponse
    {
        if (!$board = $this->boardService->getById($id)) {
            return response()
                ->json([
                    'message' => 'Board not found.'
                ])
                ->setStatusCode(404);
        }

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new BoardDetailsResource($board)
            ]);
    }

    public function update(BoardRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $status = $this->boardService->updateById($id, $validated);

        if (!$status) {
            return response()
                ->json([
                    'message' => 'Board not found.'
                ])
                ->setStatusCode(404);
        }

        return response()
            ->json([
                'message' => 'Board updated successfully.',
            ]);
    }

    public function destroy(string $id)
    {
        $status = $this->boardService->deleteById($id);

        if (!$status) {
            return response()
                ->json([
                    'message' => 'Board not found.'
                ])
                ->setStatusCode(404);
        }

        return response()
            ->json([
                'message' => 'Board was successfully deleted.',
            ]);
    }
}
