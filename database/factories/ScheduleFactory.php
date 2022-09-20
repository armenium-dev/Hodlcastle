<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Landing;
use App\Models\Domain;
use App\Models\Schedule;
use App\Models\Company;
use Carbon\Carbon;

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        'email_template_id' => EmailTemplate::inRandomOrder()->first()->id,
        'landing_id' => Landing::inRandomOrder()->first()->id,
        'domain_id' => Domain::inRandomOrder()->first()->id,
    ];
});

$factory->state(Schedule::class, 'active', function (Faker $faker) {
    return [
        'schedule_start' => Carbon::parse('-1 week'),
        'schedule_end' => Carbon::parse('+1 week'),
    ];
});

$factory->state(Schedule::class, 'ended', function (Faker $faker) {
    return [
        'schedule_start' => Carbon::parse('-2 week'),
        'schedule_end' => Carbon::parse('-1 week'),
    ];
});