<?php

namespace Tests\Feature;

use App\{User, Customer, Bank};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_login()
    {
        $user = factory(User::class)->create([
            'name' => 'Juan Palencia',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertSuccessful();

    }

    function test_if_user_can_be_created()
    {
        //Having
        $user = factory(User::class)->create();

        $customer = factory(Customer::class)->create();

        $bank = factory(Bank::class)->create();

        //When
        $this->actingAs($user);

        $this->post('api/v1/user', [
            'dni' => 562666,
            'name' => 'Juan Perez',
            'nacionality' => 'Venezuela',
            'email' => 'jperez@gmail.com',
            'role' => 'user',
            'customer_id' => $customer->id,
            'bank_id' => $bank->id,
            'phone' => '55555',
            'acconunt_number' => '123665215',
            'monthly_salary' => 5000,
        ])
        //Then        
            ->assertSuccessful()
            ->assertJson(['message' => 'El empleado se ha creado con exito']);

        $this->assertDatabaseHas('users',[
            'dni' => 562666,
            'name' => 'Juan Perez',
            'nacionality' => 'Venezuela',
            'email' => 'jperez@gmail.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('customer_user',[
            'customer_id' => $customer->id,
            'bank_id' => $bank->id,
            'phone' => '55555',
            'acconunt_number' => '123665215',
            'monthly_salary' => 5000, 
        ]);        
    }    
}
