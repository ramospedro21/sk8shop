<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('title');
            $table->text('description')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('max_uses');
            $table->integer('type');
            $table->integer('limit_per_user');
            $table->integer('min_product_quantity');
            $table->integer('target');

            $table->decimal('reduction_amount', 13, 9);
            $table->decimal('min_value', 13, 9);

            $table->boolean('first_buy_only');
            $table->boolean('status');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
