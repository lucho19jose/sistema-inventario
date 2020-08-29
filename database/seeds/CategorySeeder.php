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
    	factory(Category::class, 8)->create();

    	Category::create([
    		'name' => 'Cuadernos y Blocks',
    		'description' => 'Cuadernos y blocks',
    	]);
        Category::create([
            'name' => 'Tecnologia',
            'description' => 'Items tecnologicos',
        ]);
        Category::create([
            'name' => 'Oficina',
            'description' => 'Utililes de oficina',
        ]);
        Category::create([
            'name' => 'Escritura',
            'description' => 'Utililes de escritorio',
        ]);

    }
}
