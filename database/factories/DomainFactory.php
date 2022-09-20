<?php

use Faker\Generator as Faker;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Landing;
use App\Models\Domain;
use App\Models\Schedule;
use App\Models\Company;
use Carbon\Carbon;

$factory->define(Domain::class, function (Faker $faker) {
    $company = Company::inRandomOrder()->first();

    $email = $faker->email;
    $url = getDomainFromEmail($email);

    return [
        'name' => 'Test Domain',
        'url' => $url,
        'email' => $email,
        'company_id' => $company->id,
    ];
});