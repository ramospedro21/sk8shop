<?php

use Illuminate\Database\Seeder;

class ModuleValueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('module_values')->insert([

            //MÓDULO DE GERENCIA
            [ 'id' => 1, 'module_id' => 1, 'name' => 'Estoques', 'icon' => 'fas fa-cubes', 'link' => '/painel/stocks', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 2, 'module_id' => 1, 'name' => 'Fornecedores', 'icon' => 'fas fa-industry', 'link' => '/painel/factories', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 3, 'module_id' => 1, 'name' => 'Compras', 'icon' => 'fas fa-file-invoice-dollar', 'link' => '/painel/purchases', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 4, 'module_id' => 1, 'name' => 'Usuários', 'icon' => 'fas fa-users', 'link' => '/painel/users', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 5, 'module_id' => 1, 'name' => 'Tipo de usuários', 'icon' => 'fas fa-user', 'link' => '/painel/user_type', 'created_at' => Carbon\Carbon::now() ],

            //MÓDULO DE PRODUTOS
            [ 'id' => 6, 'module_id' => 2, 'name' => 'Central de Opções', 'icon' => 'fas fa-cogs', 'link' => '/painel/options', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 7, 'module_id' => 2, 'name' => 'Categorias', 'icon' => 'fas fa-tags', 'link' => '/painel/categories', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 8, 'module_id' => 2, 'name' => 'Produtos', 'icon' => 'fas fa-chess-rook', 'link' => '/painel/products', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 9, 'module_id' => 2, 'name' => 'Embalagens', 'icon' => 'fas fa-box-open', 'link' => '/painel/boxes', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 10, 'module_id' => 2, 'name' => 'Cupons de Desconto', 'icon' => 'fas fa-ticket-alt', 'link' => '/painel/coupons', 'created_at' => Carbon\Carbon::now() ],
           
            //MÓDULO DE VENDAS
            [ 'id' => 11, 'module_id' => 3, 'name' => 'Clientes', 'icon' => 'fas fa-users', 'link' => '/painel/clients', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 12, 'module_id' => 3, 'name' => 'Orçamentos', 'icon' => 'fas fa-clipboard-list', 'link' => '/painel/budgets', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 13, 'module_id' => 3, 'name' => 'Pedidos', 'icon' => 'fas fa-wallet', 'link' => '/painel/orders', 'created_at' => Carbon\Carbon::now() ],
            [ 'id' => 14, 'module_id' => 3, 'name' => 'Lojas', 'icon' => 'fas fa-user-check', 'link' => '/painel/resellers', 'created_at' => Carbon\Carbon::now() ],
        
        ]);
    }
}
