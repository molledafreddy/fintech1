<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'rfc' => 12345567,
            'nacionality' => 'Mexicana',
            'name' => 'Admin',
            'email' => 'miguel.jordan@ademia.io',
            'password' => bcrypt('admin1234'),
            'role' => 'administrador'
        ]);
    	// factory(User::class,10)->create();
    }
}
