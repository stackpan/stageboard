<?php

namespace App\Http\Controllers\Web;

use App\Dto\CardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\MoveCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Column;
use App\Services\CardService;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CardWebController extends Controller
{
    public function __construct(
        private readonly CardService $cardService,
    )
    {
        $this->authorizeResource(Card::class, 'card');
    }

    public function show(Card $card): CardResource
    {
        return new CardResource($card);
    }

    public function store(CreateCardRequest $request, Column $column): RedirectResponse
    {
        $this->authorize('createCard', $column);
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'] ?? null,
        );

        $this->cardService->create($column, $data);

        return back();
    }

    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'],
        );

        $this->cardService->update($card, $data);

        return back();
    }

    public function destroy(Card $card): RedirectResponse
    {
        $this->cardService->delete($card);

        return back();
    }

    public function move(MoveCardRequest $request, Card $card): RedirectResponse
    {
        $this->authorize('move', $card);
        $validated = $request->validated();

        $this->cardService->moveToColumn($card, $validated['columnId']);

        return back();
    }
}
