<?php

namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Http\Resources\ColumnResource;
use App\Services\BoardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowBoardPageController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        //
    }

    public function __invoke(Request $request, string $aliasId): Response
    {
        $board = $this->boardService->getByAliasId($aliasId);
        $this->boardService->updateUserOpenedAt($board->id, $request->user()->id);

        $columns = $board->columns->sortBy('order');

        return Inertia::render('Board/Show', [
            'board' => new BoardResource($board, false),
            'columns' => ColumnResource::collection($columns)
        ]);
    }
}
