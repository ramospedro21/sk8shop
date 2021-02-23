<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderProductsTableChangeCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->decimal('price', 9, 2)->change();
            $table->decimal('discount', 9, 2)->nullable()->change();
            $table->decimal('amount', 9, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->decimal('price', 13, 9)->change();
            $table->decimal('discount', 13, 9)->nullable()->change();
            $table->decimal('amount', 13, 9)->change();
        });
    }
}
