<?php namespace App\Listeners;

use App\Models\Campaign;
use App\Models\Result;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;

class EmailClicked extends EmailBaseListener
{

    /**
     * Handle the event.
     *
     * @param  LinkClickedEvent  $event
     * @return void
     */
    public function handle(LinkClickedEvent $event)
    {
        $this->event = $event;

        if (!$this->process())
            return;

        $eventModel = $this->createEvent(Result::TYPE_CLICK);

        $result_check = $this->resultCheck(Result::TYPE_CLICK);
//        $result_check->each(function ($item) {
//            $item->delete();
//        });
        $geo_info = geoInfo();

        if (!$result_check->count())
            $resultModel = $this->createResult($eventModel, Result::TYPE_CLICK, $geo_info);
    }

}