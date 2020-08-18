<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new \DateTime();

        DB::table('users')->insert([
            'id' => 1,
            'user_type_id' => 1,
            'name' => 'Administrador',
            'email' => 'administrador@sk8shop.com.br',
            'password' =>  bcrypt('123456'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
