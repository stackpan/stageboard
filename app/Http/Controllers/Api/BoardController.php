<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BoardRequest;
use App\Http\Resources\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;

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

        $boardId = $this->boardService->create($validated, $user->id);

        return response()
            ->json([
                'message' => 'Board created successfully.',
                'data' => [
                    'board' => [
                        'id' => $boardId,
                    ]
                ]
            ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $board = $this->boardService->getById($id);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new BoardResource($board)
            ]);
    }

    public function update(BoardRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $this->boardService->updateById($id, $validated);

        return response()
            ->json([
                'message' => 'Board updated successfully.',
            ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->boardService->deleteById($id);

        return response()
            ->json([
                'message' => 'Board was successfully deleted.',
            ]);
    }
}
