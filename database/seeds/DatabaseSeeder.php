<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ModuleTableSeeder::class);
        $this->call(ModuleValueTableSeeder::class);
        $this->call(UserTypeTableSeeder::class);
        $this->call(UserAccessTableSeeder::class);
    }
}
