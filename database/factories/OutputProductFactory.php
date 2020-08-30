<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OutputProduct;
use Faker\Generator as Faker;

$factory->define(OutputProduct::class, function (Faker $faker) {
    return [
        'product_id' => rand(1, 200),
    	'output_id' => rand(1, 30),
        'quantity' => rand(1, 30),
    ];
});
