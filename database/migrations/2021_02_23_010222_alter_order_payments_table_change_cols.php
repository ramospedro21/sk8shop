<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderPaymentsTableChangeCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->decimal('shipping_price', 9, 2)->change();
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
        Schema::table('order_payments', function (Blueprint $table) {
            $table->decimal('shipping_price', 13, 9)->change();
            $table->decimal('amount', 13, 9)->change();
        });
    }
}
