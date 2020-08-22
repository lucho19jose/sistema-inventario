<?php

use Illuminate\Database\Seeder;
use App\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Staff::class, 50)->create();

    	Staff::create([
    		'first_name' => 'Yefer Antoni',
    		'last_name' => 'Espinoza Velasque',
    		'dni' => '70445570',
    		'address' => 'Av. Andahuaylas',
    		'phone' => '900111987',
    		'email' => 'yeferanto96@gmail.com',
    	]);
    }
}
