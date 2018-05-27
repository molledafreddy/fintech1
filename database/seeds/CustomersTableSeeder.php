<?php

use Illuminate\Database\Seeder;
use App\Customer;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Customer::create([
      	'name' => 'Fintech',
      	'address' => 'Ciudad Juarez',
      	'phone' => '2134565789',
      	'web_site' => 'www.fintech.com',
      	'rfc' => '12345678',
      	'city' => 'Ciudad Juarez',
        'cp_first_name' => 'Admin',
        'cp_last_name' => 'del Sistema',
        'cp_email' => 'miguel.jordan@ademia.io',
        'cp_phone' => '1234567',
        'active' => true,
        'status' => 'verificado'
      ]);
      // factory(Customer::class,10)->create();
    }
}
