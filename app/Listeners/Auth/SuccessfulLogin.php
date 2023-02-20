<?php

namespace App\Listeners\Auth;

use App\Models\AccountActivity;

class SuccessfulLogin
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
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        AccountActivity::create([
            'action' => AccountActivity::ACTION_LOGIN,
            'user_id' => $event->user->id,
            'company_id' => $event->user->company_id,
            'ip_address' => request()->ip()
        ]);

        $event->user->update(['attempts_login' => 0]);
    }
}
