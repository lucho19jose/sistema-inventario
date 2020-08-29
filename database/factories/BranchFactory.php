<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Branch;
use Faker\Generator as Faker;

$factory->define(Branch::class, function (Faker $faker) {
	return [
		'name' => $faker->sentence(3),
		'address' => $faker->address,
		'city' => $faker->city,
	];
});
