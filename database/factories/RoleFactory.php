<?php

use Faker\Generator as Faker;
use App\Role;
use App\Models\Company;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'guard_name' => 'web',
    ];
});
