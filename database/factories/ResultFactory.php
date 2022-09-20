<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\Result;
use App\Models\Event;
use App\Models\Recipient;

$factory->define(Result::class, function (Faker $faker) {
    $campaign = Campaign::inRandomOrder()->first();
    $event = Event::inRandomOrder()->first();
    $recipient = Recipient::inRandomOrder()->first();

    return [
        'campaign_id' => $campaign->id,
        'email' => $faker->email,
        'status' => 1,
        'customer_id' => 0,
        'redirect_id' => 0,
        'ip' => '',
        'lat' => '',
        'lng' => '',
        'reported' => 1,
        'send_date' => date('Y-m-d'),
        'recipient_id' => $recipient->id,
        'event_id' => $event->id,
        'sent' => 0,
        'click' => 0,
        'open' => 0,
        'type_id' => 1,
        'user_agent' => getBrowser(),
    ];
});
