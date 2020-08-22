<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Staff;
use Faker\Generator as Faker;

$factory->define(Staff::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'dni' => rand(10000000, 99999999),
        'address' => $faker->address(),
        'phone' => rand(900000000, 999999999),
        'email' => $faker->email,
    ];
});
