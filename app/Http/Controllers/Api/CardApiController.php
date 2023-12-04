<?php

namespace App\Http\Controllers\Api;

use App\Dto\CardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\MoveCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;

class CardApiController extends Controller
{
    public function __construct(
        private readonly CardService $cardService,
    )
    {
        //
    }

    public function index(string $columnId): JsonResponse
    {
        $cards = $this->cardService->getAllByColumnId($columnId);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => CardResource::collection($cards),
            ]);
    }

    public function store(CreateCardRequest $request, string $columnId): JsonResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'] ?? null,
        );

        $id = $this->cardService->create($columnId, $data);

        return response()
            ->json([
                'message' => 'Card created successfully.',
                'data' => [
                    'card' => [
                        'id' => $id,
                    ]
                ],
            ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $card = $this->cardService->get($id);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new CardResource($card),
            ]);
    }

    public function update(UpdateCardRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'],
        );

        $this->cardService->update($id, $data);

        return response()
            ->json([
                'message' => 'Card updated successfully.',
            ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->cardService->delete($id);

        return response()
            ->json([
                'message' => 'Card was successfully deleted.',
            ]);
    }

    public function move(MoveCardRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $this->cardService->moveToColumn($id, $validated['column_id']);

        return response()
            ->json([
                'message' => 'Card was successfully moved.',
            ]);
    }
}
