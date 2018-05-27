<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('bank_id');
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->string('phone');
            $table->decimal('biweekly_salary', 15);
            $table->string('acconunt_number');
            $table->string('acconunt_clabe');
            $table->enum('status', ['pending','verifying', 'ready','error']);
            $table->enum('acceptance_terms', ['si','no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_user');
    }
}
