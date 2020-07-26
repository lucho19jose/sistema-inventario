<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName(),
        'RUC' => rand(10000000, 99999999),
        'address' => $faker->address(),
        'phone' => rand(900000000, 999999999),
        'contact' => rand(900000000, 999999999),
        'email' => $faker->email,
    ];
});
