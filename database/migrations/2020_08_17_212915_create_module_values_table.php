<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id');
            $table->string('name');
            $table->string('icon');
            $table->string('link');
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
        Schema::dropIfExists('module_values');
    }
}
