<?php

namespace App\Http\Controllers\Web\Page;

use App\Events\BoardOpenedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Http\Resources\ColumnCollection;
use App\Http\Resources\ColumnResource;
use App\Http\Resources\UserCollection;
use App\Services\BoardCollaborationService;
use App\Services\BoardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoardPageController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
        private readonly BoardCollaborationService $boardCollaborationService
    )
    {
        //
    }

    public function show(Request $request, string $aliasId): Response
    {
        $board = $this->boardService->getByAliasId($aliasId);
        $this->authorize('view', $board);

        BoardOpenedEvent::dispatch($board, $request->user());

        return Inertia::render('Board/Show', [
            'board' => new BoardResource($board, false),
            'columns' => new ColumnCollection($board->columns)
        ]);
    }

    public function edit(string $aliasId): Response
    {
        $board = $this->boardService->getByAliasId($aliasId);
        $this->authorize('update', $board);

        $collaborators = $this->boardCollaborationService->getCollaborators($board);

        return Inertia::render('Board/Settings', [
            'board' => new BoardResource($board),
            'collaborators' => new UserCollection($collaborators),
        ]);
    }
}
