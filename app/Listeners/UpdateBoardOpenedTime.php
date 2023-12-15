<?php

namespace App\Listeners;

use App\Events\BoardOpenedEvent;
use App\Models\Board;
use App\Services\BoardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBoardOpenedTime
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly BoardService $boardService,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BoardOpenedEvent $event): void
    {
        $this->boardService->updateUserOpenedTime($event->board->id, $event->user->id);
    }
}
