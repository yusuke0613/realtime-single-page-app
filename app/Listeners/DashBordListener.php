<?php

namespace App\Listeners;

use App\Events\DashBordEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DashBordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DashBordEvent  $event
     * @return void
     */
    public function handle(DashBordEvent $event)
    {
        //
    }
}
