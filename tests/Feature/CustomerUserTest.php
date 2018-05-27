<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\CustomerUser;
use App\File;
use App\Customer;
use App\Bank;

class CustomerUserTest extends TestCase
{
	use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
    	$user = factory(User::class)->create();
    	
    	$customer = factory(Customer::class)->create([
    		'admin_id' => $user->id
    	]);

    	$file = factory(File::class)->create([
    		'user_id' => $user->id,
    	]);

    	$otherFile = factory(File::class)->create([
    		'user_id' => $user->id,
    	]);

    	$bank = factory(Bank::class)->create();

    	$customerUser = factory(CustomerUser::class)->create([
    		'user_id' => $user->id,
    		'customer_id' => $customer->id,
    		'bank_id' => $bank->id,
    		'file_id' => $file->id,
    		'acconunt_clabe' => 100,
    	]);

    	$otherCustomerUsers = factory(CustomerUser::class, 5)->create([
    		'user_id' => $user->id,
    		'customer_id' => $customer->id,
    		'file_id' => $otherFile->id,
    		'bank_id' => $bank->id,
    		'acconunt_clabe' => 100,
    	]);

    	

        $this->actingAs($user)
        	->get('/api/v1/userFile?file='. $file->id)
        	
        	->assertSuccessful()
        	->assertSee('"file_id":' . $file->id)
        	->assertDontSee('"file_id":' . $otherFile->id);
    }
}
