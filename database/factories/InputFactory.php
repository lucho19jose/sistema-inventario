<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Input;
use Faker\Generator as Faker;

$factory->define(Input::class, function (Faker $faker) {
	$observation = $faker->sentence(4);
    return [
    	'user_id' => rand(1, 10),
    	'provider_id' => rand(1, 10),
    	'voucher_type' => $faker->randomElement(['Boleta', 'Factura']),
    	'voucher_serie' => $faker->randomElement(['0001', '0002', '0003']),
    	'voucher_number' => rand(100000000, 999999999),
    	'observation' => $faker->randomElement([null, $observation, null]),
    	'total' => rand(100, 9999),
    ];
});
