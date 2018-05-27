<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Order;
use App\User;
use App\Customer;
use App\CustomerUser;

class ReportTest extends TestCase
{
    protected $user;
    protected $customer;

	use DatabaseTransactions;
    public function setUp(){
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->customer = factory(Customer::class)->create([
            'name' => 'Ademia Venezuela'
        ]);
    }

    /**
     * @test
     */
    public function if_customer_is_listed_with_one_transaction_by_user()
    {
    	$orders = factory(Order::class, 10)->create();

        $this->actingAs($this->user)
        	->get('/api/v1/customer-report')
        	->assertStatus(200)
        	->assertSee($orders[0]->customer_users->customer->name)
            ->assertSee(strval($orders[0]->amount));
    }

    /**
     * @test
     */
    public function if_customer_is_listed()
    {
        $customerUser = factory(CustomerUser::class, 3)->create([
            'customer_id' => $this->customer->id,
        ]);

        $order1 = factory(Order::class, 2)->create([
            'customer_user_id' => $customerUser[0]->id,
            'amount' => 1000
        ]);

        $order2 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[1]->id,
            'amount' => 1500
        ]);

        $order3 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[2]->id,
            'amount' => 2000
        ]);

        $this->actingAs($this->user)
            ->get('/api/v1/customer-report')
            ->assertStatus(200)
            ->assertSee('Ademia Venezuela')
            ->assertSee('4')
            ->assertSee(strval(5500));
    }

    /**
     * @test
     */
    public function if_customer_can_be_filtered_by_date()
    {
        $customerUser = factory(CustomerUser::class, 3)->create([
            'customer_id' => $this->customer->id,
        ]);

        $order1 = factory(Order::class, 2)->create([
            'customer_user_id' => $customerUser[0]->id,
            'amount' => 1000,
            'created_at' => '2018-01-10',
        ]);

        $order2 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[1]->id,
            'amount' => 1500,
            'created_at' => '2018-01-11',
        ]);

        $order3 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[2]->id,
            'amount' => 2000,
            'created_at' => '2018-01-12',
        ]);

        $this->actingAs($this->user)
            ->get('/api/v1/customer-report?from=2018-01-10&to=2018-01-12')
            ->assertStatus(200)
            ->assertSee('Ademia Venezuela')
            ->assertSee('3')
            ->assertSee(strval(3500));
    }


 	public function if_customer_is_listed()
    {
        $customerUser = factory(CustomerUser::class, 3)->create([
            'customer_id' => $this->customer->id,
        ]);

        $order1 = factory(Order::class, 2)->create([
            'customer_user_id' => $customerUser[0]->id,
            'amount' => 1000
        ]);

        $order2 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[1]->id,
            'amount' => 1500
        ]);

        $order3 = factory(Order::class)->create([
            'customer_user_id' => $customerUser[2]->id,
            'amount' => 2000
        ]);

        $this->actingAs($this->user)
            ->get('/api/v1/customer-report')
            ->assertStatus(200)
            ->assertSee('Ademia Venezuela')
            ->assertSee('4')
            ->assertSee(strval(5500));
    }



}

