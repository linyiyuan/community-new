<?php

namespace App\Listeners;

use App\Events\LoginListen;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginLogListen
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
     * @param  LoginListen  $event
     * @return void
     */
    public function handle(LoginListen $event)
    {
        //
    }
}
