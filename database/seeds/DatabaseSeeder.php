<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(CustomersUsersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(CreditsTableSeeder::class);

    }
}
