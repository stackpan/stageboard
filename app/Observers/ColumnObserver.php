<?php

namespace App\Observers;

use App\Events\BoardChangedEvent;
use App\Models\Column;

class ColumnObserver
{
    /**
     * Handle the Column "created" event.
     */
    public function created(Column $column): void
    {
        broadcast(new BoardChangedEvent($column->board))->toOthers();
    }

    /**
     * Handle the Column "updated" event.
     */
    public function updated(Column $column): void
    {
        broadcast(new BoardChangedEvent($column->board))->toOthers();
    }

    /**
     * Handle the Column "deleted" event.
     */
    public function deleted(Column $column): void
    {
        broadcast(new BoardChangedEvent($column->board))->toOthers();
    }

    /**
     * Handle the Column "restored" event.
     */
    public function restored(Column $column): void
    {
        //
    }

    /**
     * Handle the Column "force deleted" event.
     */
    public function forceDeleted(Column $column): void
    {
        //
    }
}
