<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomePageController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService
    )
    {
        //
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $user = auth()->user();

        $boards = $this->boardService->getAllByUserId($user->id);

        return Inertia::render('Home', [
            'data' => new BoardCollection($boards)
        ]);
    }
}
