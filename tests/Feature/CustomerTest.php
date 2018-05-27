<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Customer;

class CustomerTest extends TestCase
{

	use DatabaseTransactions;


    function test_if_customer_can_be_paginated()
    {
    	//Having
    	$user = factory(User::class)->create();

        $customers = factory(Customer::class, 16)->create();

        //When
        $this->actingAs($user);

        $this->get('api/v1/customer')
        //Then        
        	->assertSuccessful()
        	->assertSee($customers[0]->name)
        	->assertDontSee($customers[15]->name);

    }

    function test_if_customer_can_be_created()
    {
    	//Having
    	$user = factory(User::class)->create();

        //When
        $this->actingAs($user);

        $this->post('api/v1/customer', [
	        'name'    => 'Ademia',
	       	'fiscal'  => '123456',
	       	'address' => 'Ciudad Juarez, Mexico',
	       	'phone'   => '55555555',
        ])
        //Then        
        	->assertSuccessful()
        	->assertJson(['message' => 'La empresa se ha creado con exito']);

        $this->assertDatabaseHas('customers',[
	        'name'    => 'Ademia',
	       	'fiscal'  => '123456',
	       	'address' => 'Ciudad Juarez, Mexico',
	       	'phone'   => '55555555',
        ]);
    }

    function test_if_customer_can_be_update()
    {
    	//Having
    	$user = factory(User::class)->create();

    	$customer = factory(Customer::class)->create();

    	$name = $customer->name;

        //When
        $this->actingAs($user);

        $this->put('api/v1/customer/' . $customer->id, [
	        'name'    => 'Ademia',
	       	'fiscal'  => '123456',
	       	'address' => 'Ciudad Juarez, Mexico',
	       	'phone'   => '55555555',
        ])
        //Then        
        	->assertSuccessful()
        	->assertJson(['message' => 'La empresa se ha actualizada con exito']);

        $this->assertDatabaseHas('customers',[
	        'name'    => 'Ademia',
	       	'fiscal'  => '123456',
	       	'address' => 'Ciudad Juarez, Mexico',
	       	'phone'   => '55555555',
        ]);

        $this->assertDatabaseMissing('customers',[
	        'name'    => $name,
        ]);        
    }    
}
