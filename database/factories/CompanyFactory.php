<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Landing;
use App\Models\Domain;
use App\Models\Schedule;
use App\Models\Company;
use Carbon\Carbon;

$factory->define(Company::class, function (Faker $faker) {

    return [
        'name' => 'Test Company',
    ];
});