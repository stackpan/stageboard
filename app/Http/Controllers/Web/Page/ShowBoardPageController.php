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

class ShowBoardPageController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
        private readonly BoardCollaborationService $boardCollaborationService
    )
    {
        //
    }

    public function __invoke(Request $request, string $aliasId): Response
    {
        $board = $this->boardService->getByAliasId($aliasId);
        $this->authorize('view', $board);

        $collaborators = $this->boardCollaborationService->getCollaborators($board);

        BoardOpenedEvent::dispatch($board, $request->user());

        return Inertia::render('BoardPage', [
            'board' => new BoardResource($board, false),
            'columns' => new ColumnCollection($board->columns),
            'collaborators' => new UserCollection($collaborators)
        ]);
    }
}
