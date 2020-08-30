<?php

use Illuminate\Database\Seeder;
use App\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Branch::class, 5)->create();

    	Branch::create([
    		'name' => 'Raulins Lampa de Oro',
    		'address' => 'Pasaje Malvinas 282 frente al coliseo IPD Av. Peru 381 Andahuaylas',
            'city' => 'Andahuaylas'
    	]);
        Branch::create([
            'name' => 'Raulins Feria Dominical',
            'address' => 'Av. Peru 305 Andahuaylas',
            'city' => 'Andahuaylas'
        ]);
    }
}
