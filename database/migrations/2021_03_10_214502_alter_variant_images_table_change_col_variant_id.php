<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVariantImagesTableChangeColVariantId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variant_images', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variant_images', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->change();
        });
    }
}
