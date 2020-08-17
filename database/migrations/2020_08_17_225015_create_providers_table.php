<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tax_document_type');
            $table->string('tax_document_number');
            $table->string('name');
            $table->string('phone');
            $table->string('site')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('street');
            $table->string('street_number');
            $table->string('complement')->nullable();
            $table->string('zipcode');
            $table->string('state');
            $table->string('country');
            
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
        Schema::dropIfExists('providers');
    }
}
