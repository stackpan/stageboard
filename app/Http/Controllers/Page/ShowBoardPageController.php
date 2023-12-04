<?php

namespace App\Http\Controllers\Page;

use App\Http\Resources\BoardResource;
use Inertia\Inertia;
use App\Models\Board;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\BoardService;
use App\Http\Controllers\Controller;

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

        return Inertia::render('Board', [
            'board' => new BoardResource($board),
        ]);
    }
}
