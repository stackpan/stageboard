<?php

namespace App\Observers;

use App\Events\BoardChangedEvent;
use App\Models\Board;

class BoardObserver
{
    /**
     * Handle the Board "created" event.
     */
    public function created(Board $board): void
    {
        //
    }

    /**
     * Handle the Board "updated" event.
     */
    public function updated(Board $board): void
    {
        broadcast(new BoardChangedEvent($board))->toOthers();
    }

    /**
     * Handle the Board "deleted" event.
     */
    public function deleted(Board $board): void
    {
        broadcast(new BoardChangedEvent($board))->toOthers();
    }

    /**
     * Handle the Board "restored" event.
     */
    public function restored(Board $board): void
    {
        //
    }

    /**
     * Handle the Board "force deleted" event.
     */
    public function forceDeleted(Board $board): void
    {
        //
    }
}
