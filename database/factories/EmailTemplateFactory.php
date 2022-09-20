<?php

use Faker\Generator as Faker;
use App\Models\Company;
use App\Models\EmailTemplate;

$factory->define(EmailTemplate::class, function (Faker $faker) {
    $company = Company::inRandomOrder()->first();

    return [
        'company_id' => $company->id,
        'name' => $faker->word,
        'subject' => $faker->word,
        //'link_name' => $faker->word,
        'text' => $faker->sentences(100, true),
    ];
});