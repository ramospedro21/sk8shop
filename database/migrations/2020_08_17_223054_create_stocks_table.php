<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('street');
            $table->string('street_number');
            $table->string('complement')->nullable();
            $table->string('zipcode');
            $table->string('state');
            $table->string('country');
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
        Schema::dropIfExists('stocks');
    }
}
