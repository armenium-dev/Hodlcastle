<?php

use Faker\Generator as Faker;
use App\Permission;
use App\Models\Company;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'guard_name' => 'web',
    ];
});
