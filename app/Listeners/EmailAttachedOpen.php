<?php namespace App\Listeners;

use App\Models\Campaign;
use App\Models\Result;
use App\Events\EmailAttachedEvent;

class EmailAttachedOpen extends EmailBaseListener
{

    /**
     * Handle the event.
     *
     * @param  EmailAttachedEvent  $event
     * @return void
     */
    public function handle(EmailAttachedEvent $event)
    {
        $this->event = $event;

        if (!$this->process())
            return;

        $eventModel = $this->createEvent(Result::TYPE_ATTACH);

        $result_check = $this->resultCheck(Result::TYPE_ATTACH);
//        $result_check->each(function ($item) {
//            $item->delete();
//        });
        $geo_info = geoInfo();

        if (!$result_check->count())
            $resultModel = $this->createResult($eventModel, Result::TYPE_ATTACH, $geo_info);
    }

}