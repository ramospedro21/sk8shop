<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->decimal('width', 9, 2)->nullable()->change();
            $table->decimal('depth', 9, 2)->nullable()->change();
            $table->decimal('heigth', 9, 2)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->decimal('width', 13, 9)->nullable()->change();
            $table->decimal('depth', 13, 9)->nullable()->change();
            $table->decimal('heigth', 13, 9)->nullable()->change();

        });
    }
}
