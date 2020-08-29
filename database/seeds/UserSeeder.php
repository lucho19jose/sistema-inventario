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
        factory(User::class, 9)->create();

        User::create([
        	'staff_id' => '21',
        	'email' => 'yeferanto96@gmail.com',
        	'email_verified_at' => now(),
        	'password' =>bcrypt('unajma1512'),
        	'remember_token' => Str::random(10),
        ]);

        /*Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'special' => 'all-access'
        ]);*/
    }
}
