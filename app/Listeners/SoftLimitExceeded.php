<?php namespace App\Listeners;

use App\Events\SoftLimitExceededEvent;
use App\Role;
use Illuminate\Support\Facades\Mail;

class SoftLimitExceeded
{

    /**
     * Handle the event.
     *
     * @param  SoftLimitExceededEvent  $event
     * @return void
     */
    public function handle(SoftLimitExceededEvent $event)
    {
        $data = $event->company->toArray();
        $captains = Role::findByName('captain')->users;
//dd($captains->pluck('email'));
        Mail::send('emails.company.soft_limit_exceeded', $data, function ($message) use ($captains, $event) {
//            foreach ($captains as $captain) {
//                $message->to($captain->email);
//            }
            $message->to($captains->first()->email);
            $message->cc($captains->pluck('email'));

            $message->subject($event->company->name . ' soft limit of ' . $event->company->soft_limit . 'exceeded');

            $message->getHeaders()->addTextHeader('X-No-Track', str_random(10));
        });
    }
}