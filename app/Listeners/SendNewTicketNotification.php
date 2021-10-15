<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewTicketNotificaticon
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admin = User::whereHas('roles', function ($query){
                $query->where('id', 1);
        })->get();
        Notification::send($admin, new NewTicketNotificaticon($event->user));
    }
}
