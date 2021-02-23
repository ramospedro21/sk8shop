<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCouponsTableChangeCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('reduction_amount', 9, 2)->change();
            $table->decimal('min_value', 9, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('reduction_amount', 13, 9)->change();
            $table->decimal('min_value', 13, 9)->change();
        });
    }
}
