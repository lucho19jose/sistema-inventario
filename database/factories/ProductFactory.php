<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
    	'category_id' => rand(1, 12),
        'name' => $faker->sentence(4),
        'description' => $faker->text(),
        'stock' => rand(10, 300),
    ];
});
