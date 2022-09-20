<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'jdavidbakr\MailTracker\Events\EmailSentEvent' => [
            'App\Listeners\EmailSent',
        ],
        'jdavidbakr\MailTracker\Events\ViewEmailEvent' => [
            'App\Listeners\EmailViewed',
        ],
        'jdavidbakr\MailTracker\Events\LinkClickedEvent' => [
            'App\Listeners\EmailClicked',
        ],
        'App\Events\EmailReportedEvent' => [
            'App\Listeners\EmailReported',
        ],
        'App\Events\SoftLimitExceededEvent' => [
            'App\Listeners\SoftLimitExceeded',
        ],
        'App\Events\EmailAttachedEvent' => [
	        'App\Listeners\EmailAttachedOpen',
        ],
        'App\Events\SmsSentEvent' => [
	        'App\Listeners\SmsSent',
        ],
        'App\Events\SmsEvent' => [
	        'App\Listeners\SmsClicked',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
