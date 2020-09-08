<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'staff_id' => '21',
        	'email' => 'yeferanto96@gmail.com',
        	'email_verified_at' => now(),
        	'password' =>bcrypt('unajma1512'),
        	'remember_token' => Str::random(10),
        ]);

        User::create([
            'staff_id' => '20',
            'email' => 'responsable_almacen@gmail.com',
            'email_verified_at' => now(),
            'password' =>bcrypt('unajma1512'),
            'remember_token' => Str::random(10),
        ]);

        factory(User::class, 8)->create();

    }
}
