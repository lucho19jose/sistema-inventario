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
    	factory(Product::class, 200)->create();

    	Product::create([
            'code' => '93A42',
    		'category_id' => '9',
    		'name' => 'Cuaderno A4 Justus 1x1 solido',
    		'description' => 'Tamaño: 21 CM X 29.7 CM (A4)',
            'stock' => '100',
    	]);
        Product::create([
            'code' => '13EE2',
            'category_id' => '9',
            'name' => 'Cuaderno A4 Justus 2x2 diseño',
            'description' => 'Tamaño: 21 CM X 29.7 CM (A4)',
            'stock' => '100',
        ]);
        Product::create([
            'code' => 'R34T2',
            'category_id' => '10',
            'name' => 'USB kingston 32 GB',
            'description' => 'Capacidad 32 GB., Tecnologia 3.0., Practico, Elegante y con tapa.',
            'stock' => '100',
        ]);
        Product::create([
            'code' => 'X3A42',
            'category_id' => '10',
            'name' => 'USB Philips 16 GB',
            'description' => 'Capacidad 16 GB., Tecnologia 2.0., Practico, Elegante y con tapa.',
            'stock' => '100',
        ]);

        Product::create([
            'code' => '61AA2',
            'category_id' => '11',
            'name' => 'Artesco Binder Clips 19 mm',
            'description' => 'BInder clips 3/4 (19 mm). Sujetadores elegantes. Material 100% inoxidable',
            'stock' => '100',
        ]);
        Product::create([
            'code' => '1YA4S',
            'category_id' => '11',
            'name' => 'Artesco Engrapador M-515',
            'description' => 'Engrapa hasta 22 hojas. disponible en colores azul y negro. cuerpo de plástico resistente. yunque giratorio para engrapado abierto y cerrado. para grapas 26/6 Artesco.',
            'stock' => '100',
        ]);
        Product::create([
            'code' => 'G3A19',
            'category_id' => '12',
            'name' => 'Artesco borrador blanco chico',
            'description' => 'Borrador blanco chico',
            'stock' => '100',
        ]);
        Product::create([
            'code' => '1EA12',
            'category_id' => '12',
            'name' => 'Artesco plumon acrilico acrimax 123 azul',
            'description' => 'Marcador acrimax a123 para pizarra blanca. trazo 1 – 3mm',
            'stock' => '100',
        ]);
    }
}
