<?php

namespace App\Observers;

use App\Models\Movement;

class MovemenObserver
{
    /**
     * Handle the Movement "created" event.
     */
    public function created(Movement $movement): void
    {
        //
    }

    /**
     * Handle the Movement "updated" event.
     */
    public function updated(Movement $movement): void
    {
        //
    }

    /**
     * Handle the Movement "deleted" event.
     */
    public function deleted(Movement $movement): void
    {
        //
    }

    /**
     * Handle the Movement "restored" event.
     */
    public function restored(Movement $movement): void
    {
        //
    }

    /**
     * Handle the Movement "force deleted" event.
     */
    public function forceDeleted(Movement $movement): void
    {
        //
    }
}
