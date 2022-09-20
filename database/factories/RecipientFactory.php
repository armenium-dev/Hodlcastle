<?php

use Faker\Generator as Faker;
use App\Models\Recipient;

$factory->define(Recipient::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'position' => $faker->jobTitle,
        'department' => '',
    ];
});

$factory->state(Recipient::class, 'test', function () {
    return [
        'first_name' => RECIPIENT_NAME,
        'last_name' => 'Last test',
        'email' => RECIPIENT_EMAIL,
    ];
});