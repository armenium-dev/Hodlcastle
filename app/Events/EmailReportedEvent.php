<?php namespace App\Events;

use Illuminate\Queue\SerializesModels;
use jdavidbakr\MailTracker\Model\SentEmail;

class EmailReportedEvent
{
    use SerializesModels;

    public $sent_email;

    /**
     * Create a new event instance.
     *
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct(SentEmail $sent_email)
    {
        $this->sent_email = $sent_email;
    }
}