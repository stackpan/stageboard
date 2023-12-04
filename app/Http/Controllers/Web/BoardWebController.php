<?php

namespace App\Http\Controllers\Web;

use App\Dto\BoardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRequest;
use App\Services\BoardService;
use Illuminate\Http\RedirectResponse;

class BoardWebController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        //
    }

    public function store(BoardRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validated();

        $data = new BoardDto(
            name: $validated['name'],
        );

        $boardId = $this->boardService->create($user->id, $data);

        $boardAliasId = $this->boardService->get($boardId, ['id', 'alias_id']);

        return to_route(route('page.board.show', $boardAliasId));
    }

    public function update(BoardRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $data = new BoardDto(
            name: $validated['name'],
        );

        $this->boardService->update($id, $data);

        return back();
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->boardService->delete($id);

        return back();
    }
}
