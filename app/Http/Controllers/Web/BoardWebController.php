<?php

namespace App\Http\Controllers\Web;

use App\Dto\BoardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRequest;
use App\Models\Board;
use App\Services\BoardService;
use Illuminate\Http\RedirectResponse;

class BoardWebController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        $this->authorizeResource(Board::class, 'board');
    }

    public function store(BoardRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $data = new BoardDto(
            name: $validated['name'],
        );

        $board = $this->boardService->create($user, $data);

        return to_route('web.page.board.show', $board->alias_id);
    }

    public function update(BoardRequest $request, Board $board): RedirectResponse
    {
        $validated = $request->validated();

        $data = new BoardDto(
            name: $validated['name'],
        );

        $this->boardService->update($board, $data);

        return back();
    }

    public function destroy(Board $board): RedirectResponse
    {
        $this->boardService->delete($board);

        return back();
    }
}
