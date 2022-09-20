<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\Schedule;
use App\Models\Company;
use Carbon\Carbon;

$factory->define(Campaign::class, function (Faker $faker) {
    $schedule = Schedule::inRandomOrder()->first();

    return [
        'name' => 'Test Campaign',
        'schedule_id' => $schedule ? $schedule->id : 0,
        'company_id' => Company::inRandomOrder()->first()->id,
    ];
});