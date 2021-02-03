<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductStocksTableAddColPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {

            $table->decimal('price', 13, 9);
            $table->decimal('promote_price', 13, 9);
            $table->decimal('factory_price', 13, 9);
            $table->integer('reserved');
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
            $table->dropColumn('price');
            $table->dropColumn('promote_price');
            $table->dropColumn('factory_price');
            $table->dropColumn('reserved');
        });
    }
}
