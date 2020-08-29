<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Output;
use Faker\Generator as Faker;

$factory->define(Output::class, function (Faker $faker) {
	$observation = $faker->sentence(4);
    return [
        'user_id' => rand(1, 10),
    	'staff_id' => rand(1, 20),
    	'branch_id' => rand(1, 6),
    	'voucher_type' => $faker->randomElement(['Boleta', 'Factura']),
    	'voucher_serie' => $faker->randomElement(['001', '002', '003']),
    	'voucher_number' => rand(1000000000, 9999999999),
    	'observation' => $faker->randomElement([null, $observation, null]),
    ];
});
