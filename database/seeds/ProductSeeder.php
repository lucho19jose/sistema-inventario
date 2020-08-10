<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Product::class, 300)->create();

    	Product::create([
    		'category_id' => '12',
    		'name' => 'Cuaderno A4 Justus',
    		'description' => null,
            'stock' => '100',
    	]);
    }
}
