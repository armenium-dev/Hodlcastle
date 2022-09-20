<?php namespace App\Listeners;

use App\Models\Result;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;

class EmailViewed extends EmailBaseListener
{

    /**
     * Handle the event.
     *
     * @param  ViewEmailEvent  $event
     * @return void
     */
    public function handle(ViewEmailEvent $event)
    {
        $this->event = $event;
        if (!$this->process())
            return;

        $eventModel = $this->createEvent(Result::TYPE_OPEN);

        $result_check = $this->resultCheck(Result::TYPE_OPEN);
        $geo_info = geoInfo();

        if (!$result_check->count())
            $resultModel = $this->createResult($eventModel, Result::TYPE_OPEN, $geo_info);
    }

}