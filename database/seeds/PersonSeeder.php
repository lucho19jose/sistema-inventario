<?php

use Illuminate\Database\Seeder;
use App\Person;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Person::class, 50)->create();

    	Person::create([
    		'first_name' => 'Yefer Antoni',
    		'last_name' => 'Espinoza Velasque',
    		'DNI' => '70445570',
    		'address' => 'Av. Andahuaylas',
    		'phone' => '900111987',
    		'email' => 'yeferanto96@gmail.com',
    	]);
    }
}
