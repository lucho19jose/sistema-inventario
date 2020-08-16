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

        Input::create([
            'user_id' => '10',
            'provider_id' => '10',
            'voucher_type' => 'Factura',
            'voucher_serie' => '0001',
            'voucher_number' => '100100399',
            'observation' => null,
            'total' => '100',
        ]);

        factory(InputProduct::class, 31)->create();
    }
}
