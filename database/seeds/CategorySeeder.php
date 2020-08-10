<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Category::class, 11)->create();

    	Category::create([
    		'name' => 'Cuadernos',
    		'description' => null,
    	]);

    }
}
