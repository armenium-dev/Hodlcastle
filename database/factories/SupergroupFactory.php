<?php

use Faker\Generator as Faker;
use App\Models\Supergroup;
use App\Role;

$factory->define(Supergroup::class, function (Faker $faker) {
    $role = Role::whereName('captain')->first();
    $user = $role->users->first();

    return [
        'name' => $faker->word,
        'captain_id' => $user->id,
    ];
});
