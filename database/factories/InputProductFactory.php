<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\InputProduct;
use Faker\Generator as Faker;

$factory->define(InputProduct::class, function (Faker $faker) {
    return [
    	'product_id' => rand(1, 300),
    	'input_id' => rand(1, 31),
        'quantity' => rand(10, 20),
    ];
});
