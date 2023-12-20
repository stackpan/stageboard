<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBoardCollaboratorRequest;
use App\Http\Requests\RemoveBoardCollaboratorRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Board;
use App\Services\BoardCollaborationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BoardCollaboratorController extends Controller
{
    public function __construct(
        private readonly BoardCollaborationService $boardCollaborationService,
    )
    {
        //
    }

    public function show(Board $board): UserCollection
    {
        $this->authorize('view', $board);
        $collaborators = $this->boardCollaborationService->getCollaborators($board);

        return new UserCollection($collaborators);
    }

    public function store(CreateBoardCollaboratorRequest $request, Board $board): RedirectResponse
    {
        $this->authorize('manageCollaborator', $board);
        $validated = $request->validated();

        $this->boardCollaborationService->add($board, $validated['userId']);

        return back();
    }

    public function destroy(RemoveBoardCollaboratorRequest $request, Board $board): RedirectResponse
    {
        $this->authorize('manageCollaborator', $board);
        $validated = $request->validated();

        $this->boardCollaborationService->remove($board, $validated['userId']);

        return back();
    }
}
