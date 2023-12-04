<?php

namespace App\Http\Controllers\Api;

use App\Dto\ColumnDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateColumnRequest;
use App\Http\Requests\MoveColumnRequest;
use App\Http\Requests\UpdateColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Services\ColumnService;
use Illuminate\Http\JsonResponse;

class ColumnApiController extends Controller
{
    public function __construct(
        private readonly ColumnService $columnService,
    )
    {
        //
    }

    public function index(string $boardId): JsonResponse
    {
        $columns = $this->columnService->getAllByBoardId($boardId);

        return response()->json([
            'message' => 'Success.',
            'data' => ColumnResource::collection($columns),
        ]);
    }

    public function store(CreateColumnRequest $request, string $boardId): JsonResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            order: $validated['order'],
            color: $validated['color'] ?? null,
        );

        $columnId = $this->columnService->create($boardId, $data);

        return response()
            ->json([
                'message' => 'Column created successfully.',
                'data' => [
                    'column' => [
                        'id' => $columnId,
                    ]
                ]
            ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $column = $this->columnService->get($id);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new ColumnResource($column),
            ]);
    }

    public function update(UpdateColumnRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
            color: $validated['color'],
        );

        $this->columnService->update($id, $data);

        return response()
            ->json([
                'message' => 'Column updated successfully.',
            ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->columnService->delete($id);

        return response()
            ->json([
                'message' => 'Column was successfully deleted.',
            ]);
    }

    public function swap(MoveColumnRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $this->columnService->move($id, $validated['order']);

        return response()
            ->json([
                'message' => 'Column was successfully moved.',
            ]);
    }
}
