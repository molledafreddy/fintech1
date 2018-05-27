<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {

            $table->enum('status', ['pendiente por verificar','verificado','inactivo'])->nullable()->default('pendiente por verificar')->after('phone');
            $table->boolean('deleted')->nullable()->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {

            $table->dropColumn('status');
            $table->dropColumn('deleted');
        });
    }
}
