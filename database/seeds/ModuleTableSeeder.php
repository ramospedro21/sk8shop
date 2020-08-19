<?php

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $now = date('Y-m-d H:i:s');
        
        DB::table('modules')->insert([
            'id' => 1,
            'name' => 'Gêrencia',
            'icon' => 'fas fa-brain',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('modules')->insert([
            'id' => 2,
            'name' => 'Produtos',
            'icon' => 'fas fa-chess-rook',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        DB::table('modules')->insert([
            'id' => 3,
            'name' => 'Vendas',
            'icon' => 'fas fa-clipboard-check',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
