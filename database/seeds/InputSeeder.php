<?php

use Illuminate\Database\Seeder;
use App\Input;
use App\InputProduct;

class InputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Input::class, 30)->create();

        factory(InputProduct::class, 100)->create();
    }
}
