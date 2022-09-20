<?php

use Faker\Generator as Faker;
use App\Models\Landing;
use App\Models\Company;

$factory->define(Landing::class, function (Faker $faker) {
    return [
        'name' => $faker->domainWord,
        'company_id' => Company::inRandomOrder()->first()->id,
        'content' => $faker->text,
    ];
});
