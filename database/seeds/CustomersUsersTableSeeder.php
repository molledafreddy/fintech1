<?php

use Illuminate\Database\Seeder;
use App\CustomerUser;
use App\File;

class CustomersUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $file = factory(File::class)->create(['name' => 'archivo de prueba',
        // 'status' =>'no_processed',]);
        factory(Customeruser::class)->create([
            'customer_id' => 1,
            'user_id'=> 1, 
            'bank_id' => 1,
            'phone' => 526567041301, 
            'acconunt_number' =>11111111111,
            'biweekly_salary' => 56567.88,
            'acconunt_clabe'=>100000000000000000,
            'status'=>'ready',
            'acceptance_terms' => 'no',
            'file_id' => null,

        ]);
        // factory(CustomerUser::class,10)->create();

    }
}
