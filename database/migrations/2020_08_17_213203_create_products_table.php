<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            $table->string('slug');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            $table->integer('installments')->nullable()->default(1);

            $table->decimal('width', 13, 9)->nullable();
            $table->decimal('depth', 13, 9)->nullable();
            $table->decimal('heigth', 13, 9)->nullable();

            $table->boolean('enabled');

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
        Schema::dropIfExists('products');
    }
}
