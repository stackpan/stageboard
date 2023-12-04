<?php

namespace App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Models\Board;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\BoardService;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        //
    }

    public function show(Request $request, string $aliasId): Response
    {
        $board = $this->boardService->getByAliasId($aliasId, ['id', 'name']);

        return Inertia::render('Board', [
            'id' => $board->id,
            'name' => $board->name,
        ]);
    }
}
