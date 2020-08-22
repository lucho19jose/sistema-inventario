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
    		'name' => 'Raulins',
    		'address' => 'Pasaje Malvinas 282 frente al coliseo IPD Av. Peru 381 Andahuaylas',
    	]);
    }
}
