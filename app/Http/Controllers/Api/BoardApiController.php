<?php

namespace App\Http\Controllers\Api;

use App\Dto\BoardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRequest;
use App\Http\Resources\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;

class BoardApiController extends Controller
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

        $data = new BoardDto(
            name: $validated['name'],
        );

        $boardId = $this->boardService->create($user->id, $data);

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
        $board = $this->boardService->get($id);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new BoardResource($board)
            ]);
    }

    public function update(BoardRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $data = new BoardDto(
            name: $validated['name'],
        );

        $this->boardService->update($id, $data);

        return response()
            ->json([
                'message' => 'Board updated successfully.',
            ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->boardService->delete($id);

        return response()
            ->json([
                'message' => 'Board was successfully deleted.',
            ]);
    }
}
