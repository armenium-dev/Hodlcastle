<?php

use Faker\Generator as Faker;
use App\Models\Group;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->domainWord,
        'company_id' => \App\Models\Company::inRandomOrder()->first()->id,
    ];
});

$factory->state(Group::class, 'test', function () {
    return [
        'name' => TEST_GROUP_NAME,
    ];
});
