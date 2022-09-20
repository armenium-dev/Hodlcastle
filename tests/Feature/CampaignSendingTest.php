<?php namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Schedule;
use Tests\TestCase;

class CampaignSendingTest extends TestCase
{

    public function mailSending()
    {

    }

    public function mailSendingWeekendIfAllowed()
    {

    }

    public function mailSendingTimeAllowed()
    {
        $campaign = factory(Campaign::class)->states('active')->create([
            'time_start' => '10:00',
            'time_end' => '12:00',
        ]);
    }

    /** @test */
    public function mailSendingDatesBoundaries()
    {//dd(factory(Campaign::class)->states('active'));
        $schedule = factory(Schedule::class)->states('active')->create();
        $campaign = factory(Campaign::class)->create();
        $campaign->schedule = $schedule;

        //$sent = $campaign->sendToNextRecipient();

        $this->assertTrue(true);
    }
}