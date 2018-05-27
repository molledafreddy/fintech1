<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
            $table->string('web_site')->nullable()->after('address');
            //$table->string('rfc',40)->nullable()->after('web_site');
            //$table->string('billing_address')->nullable()->after('rfc');
            $table->string('city')->nullable()->after('web_site');
            $table->string('cp_first_name')->nullable()->after('city');
            $table->string('cp_last_name')->nullable()->after('cp_first_name');
            $table->string('cp_email')->nullable()->after('cp_last_name');
            $table->string('cp_phone')->nullable()->after('cp_email');
            $table->boolean('active')->nullable()->default(false)->after('cp_phone');
            //La empresa tendra un único administrador
            $table->unsignedInteger('admin_id')->nullable()->after('active');
            $table->foreign('admin_id')->references('id')->on('users');
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
            //$table->string('fiscal')->change();
            $table->string('address')->change();
            $table->dropColumn('web_site');
            $table->dropColumn('rfc');
            //$table->dropColumn('billing_address');
            $table->dropColumn('city');
            $table->dropColumn('cp_first_name');
            $table->dropColumn('cp_last_name');
            $table->dropColumn('cp_email');
            $table->dropColumn('cp_phone');
            $table->dropColumn('active');
            $table->dropColumn('admin_id');
        });
    }
}
