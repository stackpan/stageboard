<?php

namespace App\Http\Controllers\Web;

use App\Dto\CardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCardRequest;
use App\Http\Requests\MoveCardRequest;
use App\Http\Requests\UpdateCardRequest;
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
        //
    }

    public function store(CreateCardRequest $request, string $columnId): RedirectResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'] ?? null,
        );

        $this->cardService->create($columnId, $data);

        return back();
    }

    public function update(UpdateCardRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $data = new CardDto(
            body: $validated['body'],
            color: $validated['color'],
        );

        $this->cardService->update($id, $data);

        return back();
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->cardService->delete($id);

        return back();
    }

    public function move(MoveCardRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $this->cardService->moveToColumn($id, $validated['columnId']);

        return back();
    }
}
