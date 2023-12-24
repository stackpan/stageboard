<?php

namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
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
        $user = $request->user();

        $boards = $this->boardService->getAllByUser($user);

        return Inertia::render('Home', [
            'boards' => BoardResource::collection($boards)
        ]);
    }
}
