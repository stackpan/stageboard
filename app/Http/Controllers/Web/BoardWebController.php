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

        $board = $this->boardService->create($user->id, $data);

        return to_route('page.board.show', $board->alias_id);
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
