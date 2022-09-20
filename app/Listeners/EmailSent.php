<?php namespace App\Listeners;

use App\Models\Result;
use jdavidbakr\MailTracker\Events\EmailSentEvent;

class EmailSent extends EmailBaseListener
{

    /**
     * Handle the event.
     *
     * @param  EmailSentEvent  $event
     * @return void
     */
    public function handle(EmailSentEvent $event)
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