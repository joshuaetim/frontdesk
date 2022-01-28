<?php

namespace App\Providers;

use App\Models\Staff;
use App\Models\Visitor;
use App\Notifications\VisitorLogged as NotificationsVisitorLogged;
use App\Providers\VisitorLogged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVisitorNotification implements ShouldQueue
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
     * @param  \App\Providers\VisitorLogged  $event
     * @return void
     */
    public function handle(VisitorLogged $event)
    {
        $staff = Staff::find($event->staffId);

        if(!$staff) { return; }

        $staff->notify(new NotificationsVisitorLogged($event->visitorId));
    }
}
