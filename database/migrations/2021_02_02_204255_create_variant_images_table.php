<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('product_id');
            $table->text('url');
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variant_images');
    }
}
