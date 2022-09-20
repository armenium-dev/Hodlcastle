<?php namespace App\Listeners;

use App\Models\Recipient;
use App\Events\EmailReportedEvent;
use Exception;
use App\Models\Result;

class EmailReported extends EmailBaseListener
{

    public function handle(EmailReportedEvent $event)
    {
        $this->event = $event;
        if (!$this->process())
            return;

        $eventModel = $this->createEvent(Result::TYPE_REPORT);

        $result_check = $this->resultCheck(Result::TYPE_REPORT);
//        $result_check->each(function ($item) {
//            $item->delete();
//        });
        $geo_info = geoInfo();

        if (!$result_check->count())
            $resultModel = $this->createResult($eventModel, Result::TYPE_REPORT, $geo_info);
    }
}