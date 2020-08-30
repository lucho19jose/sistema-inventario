<?php

use Illuminate\Database\Seeder;
use App\Output;
use App\OutputProduct;

class OutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Output::class, 30)->create();

        factory(OutputProduct::class, 100)->create();
    }
}
