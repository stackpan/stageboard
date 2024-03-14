<?php

namespace App\Http\Controllers\Web\Page;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Enums\BoardPermission;
use App\Services\BoardService;
use App\Events\BoardOpenedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Http\Resources\ColumnResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\ColumnCollection;
use App\Services\BoardCollaborationService;

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
            'columns' => new ColumnCollection($board->columns),
            'permission' => $board->getUserPermission($request->user())
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

    public function showPublic(Request $request, string $aliasId): Response | RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('web.page.board.show', ['boardAlias' => $aliasId]);
        }

        $board = $this->boardService->getByAliasId($aliasId);

        if (!$board->is_public) {
            return redirect()->route('web.page.board.show', ['boardAlias' => $aliasId]);
        }

        return Inertia::render('Board/Show', [
            'board' => new BoardResource($board, false),
            'columns' => new ColumnCollection($board->columns),
            'permission' => BoardPermission::READ_ONLY,
        ]);
    }
}
