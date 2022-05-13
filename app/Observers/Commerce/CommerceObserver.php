<?php

namespace App\Observers\Commerce;

use App\Models\Commerce;
use Illuminate\Support\Facades\Log;

class CommerceObserver
{
    /**
     * Handle the Commerce "created" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function created(Commerce $commerce)
    {
        Log::info($commerce);
    }

    /**
     * Handle the Commerce "updated" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function updated(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "deleted" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function deleted(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "restored" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function restored(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "force deleted" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function forceDeleted(Commerce $commerce)
    {
        //
    }
}
