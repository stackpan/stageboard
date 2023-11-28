<?php

namespace App\Http\Controllers\Api;

use App\Dto\ColumnDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateColumnRequest;
use App\Http\Requests\Api\MoveColumnRequest;
use App\Http\Requests\Api\UpdateColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Services\ColumnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
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

    public function show(string $boardId, string $columnId): JsonResponse
    {
        $column = $this->columnService->get($columnId);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new ColumnResource($column),
            ]);
    }

    public function update(UpdateColumnRequest $request,  string $boardId, string $columnId): JsonResponse
    {
        $validated = $request->validated();

        $data = new ColumnDto(
            name: $validated['name'],
        );

        $this->columnService->update($columnId, $data);

        return response()
            ->json([
                'message' => 'Column updated successfully.',
            ]);
    }

    public function destroy(string $boardId, string $columnId): JsonResponse
    {
        $this->columnService->delete($columnId);

        return response()
            ->json([
                'message' => 'Column was successfully deleted.',
            ]);
    }
    
    public function move(MoveColumnRequest $request, string $boardId, string $columnId): JsonResponse
    {
        $validated = $request->validated();

        $this->columnService->move($boardId, $columnId, $validated['order']);

        return response()
            ->json([
                'message' => 'Column was successfully moved.',
            ]);
    }
}
