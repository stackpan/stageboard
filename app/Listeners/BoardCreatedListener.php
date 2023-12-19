<?php

namespace App\Listeners;

use App\Events\BoardCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BoardCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BoardCreated $event): void
    {
        $event->board->users()->attach($event->board->owner_id);
    }
}
