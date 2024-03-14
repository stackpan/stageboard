<?php

namespace App\Http\Controllers\Web;

use App\Dto\CreateBoardDto;
use App\Dto\UpdateBoardDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
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

    public function show(Board $board): BoardResource
    {
        return new BoardResource($board);
    }

    public function store(CreateBoardRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $data = new CreateBoardDto(
            name: $validated['name'],
        );

        $board = $this->boardService->create($user, $data);

        return to_route('web.page.board.show', $board->alias_id);
    }

    public function update(UpdateBoardRequest $request, Board $board): RedirectResponse
    {
        $validated = $request->validated();

        $data = new UpdateBoardDto(
            name: $validated['name'],
            isPublic: $validated['isPublic']
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
