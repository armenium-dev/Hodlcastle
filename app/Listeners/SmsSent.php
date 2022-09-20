<?php namespace App\Listeners;

use App\Models\Result;
use jdavidbakr\MailTracker\Events\EmailSentEvent;
use App\Events\SmsSentEvent;

class SmsSent extends SmsBaseListener
{

    /**
     * Handle the event.
     *
     * @param  SmsSentEvent  $event
     * @return void
     */
    public function handle(SmsSentEvent $event)
    {
        $this->event = $event;
        if (!$this->process())
            return;

        $eventModel = $this->createEvent(Result::TYPE_SENT);

        $result_check = $this->resultCheck(Result::TYPE_SENT);

        if (!$result_check->count())
            $resultModel = $this->createResult($eventModel, Result::TYPE_SENT);
    }
}