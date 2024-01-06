<?php

namespace App\Observers;

use App\Events\BoardChangedEvent;
use App\Models\Card;

class CardObserver
{
    /**
     * Handle the Card "created" event.
     */
    public function created(Card $card): void
    {
        broadcast(new BoardChangedEvent($card->column->board))->toOthers();
    }

    /**
     * Handle the Card "updated" event.
     */
    public function updated(Card $card): void
    {
        broadcast(new BoardChangedEvent($card->column->board))->toOthers();
    }

    /**
     * Handle the Card "deleted" event.
     */
    public function deleted(Card $card): void
    {
        broadcast(new BoardChangedEvent($card->column->board))->toOthers();
    }

    /**
     * Handle the Card "restored" event.
     */
    public function restored(Card $card): void
    {
        //
    }

    /**
     * Handle the Card "force deleted" event.
     */
    public function forceDeleted(Card $card): void
    {
        //
    }
}
