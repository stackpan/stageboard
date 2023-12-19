<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBoardCollaboratorRequest;
use App\Http\Requests\RemoveBoardCollaboratorRequest;
use App\Models\Board;
use App\Services\BoardCollaborationService;
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

    /**
     * Store a newly created resource in storage.
     */
    public function add(CreateBoardCollaboratorRequest $request, Board $board): RedirectResponse
    {
        $this->authorize('manageCollaborator', $board);
        $validated = $request->validated();

        $this->boardCollaborationService->add($board, $validated['userId']);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(RemoveBoardCollaboratorRequest $request, Board $board): RedirectResponse
    {
        $this->authorize('manageCollaborator', $board);
        $validated = $request->validated();

        $this->boardCollaborationService->remove($board, $validated['userId']);

        return back();
    }
}
