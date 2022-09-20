<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\Event;

$factory->define(Event::class, function (Faker $faker) {
    $campaign = Campaign::inRandomOrder()->first();

    return [
        'campaign_id' => $campaign->id,
        'email' => $faker->email,
        'message' => $faker->word,
    ];
});
