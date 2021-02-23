<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductStocksTableChangeCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('price', 9, 2)->change();
            $table->decimal('promote_price', 9, 2)->change();
            $table->decimal('factory_price', 9, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('price', 13, 9)->change();
            $table->decimal('promote_price', 13, 9)->change();
            $table->decimal('factory_price', 13, 9)->change();
        });
    }
}
