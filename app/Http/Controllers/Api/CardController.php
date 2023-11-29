<?php

namespace App\Http\Controllers\Api;

use App\Dto\CardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CardRequest;
use App\Http\Requests\Api\MoveCardRequest;
use App\Http\Resources\CardResource;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
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

    public function store(CardRequest $request, string $columnId): JsonResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
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

    public function show(string $columnId, string $cardId): JsonResponse
    {
        $card = $this->cardService->get($cardId);

        return response()
            ->json([
                'message' => 'Success.',
                'data' => new CardResource($card),
            ]);
    }

    public function update(CardRequest $request, string $columnId, string $cardId): JsonResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
        );

        $this->cardService->update($cardId, $data);

        return response()
            ->json([
                'message' => 'Card updated successfully.',
            ]);
    }

    public function destroy(string $columnId, string $cardId): JsonResponse
    {
        $this->cardService->delete($cardId);

        return response()
            ->json([
                'message' => 'Card was successfully deleted.',
            ]);
    }
    
    public function move(MoveCardRequest $request, string $columnId, string $cardId): JsonResponse
    {
        $validated = $request->validated();

        $this->cardService->moveToColumn($cardId, $validated['column_id']);

        return response()
            ->json([
                'message' => 'Card was successfully moved.',
            ]);
    }
}
