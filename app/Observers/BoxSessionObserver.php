<?php

namespace App\Observers;

use App\Models\BoxSession;
use App\Models\Sale;

class BoxSessionObserver
{
    /**
     * Handle the BoxSession "created" event.
     */
    public function created(BoxSession $boxSession): void
    {
        //
    }

    /**
     * Handle the BoxSession "updated" event.
     */
    public function updated(BoxSession $boxSession): void
    {
        $total_sale = $boxSession->sales()->sum('total');

        $expected = $boxSession->opening_amount + $total_sale;
        $difference = $boxSession->closing_amount - $expected;

        $boxSession->expected_amount = $expected;
        $boxSession->difference = $difference;
        $boxSession->saveQuietly();
    }

    /**
     * Handle the BoxSession "deleted" event.
     */
    public function deleted(BoxSession $boxSession): void
    {
        //
    }

    /**
     * Handle the BoxSession "restored" event.
     */
    public function restored(BoxSession $boxSession): void
    {
        //
    }

    /**
     * Handle the BoxSession "force deleted" event.
     */
    public function forceDeleted(BoxSession $boxSession): void
    {
        //
    }
}
