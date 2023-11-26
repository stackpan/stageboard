<?php

namespace App\Http\Controllers\Api;

use App\Dto\ColumnDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateColumnRequest;
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
            nextColumnId: $validated['next_column_id'] ?? null,
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

    public function show(string $boardId, string $columnId)
    {
        //
    }

    public function update(Request $request,  string $boardId, string $columnId)
    {
        //
    }

    public function destroy(string $boardId, string $columnId)
    {
        //
    }
    
    public function move(Request $request, string $boardId, string $columnId) {
        //
    }
}
