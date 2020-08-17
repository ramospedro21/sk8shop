<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('birthdate');
            
            $table->string('tax_document_type');
            $table->string('tax_document_number');

            $table->string('identity_document_number')->nullable();
            $table->string('identity_document_type')->nullabe();

            $table->integer('phone_country_code');
            $table->string('phone_area_code');
            $table->string('phone_number');

            $table->string('gender');
            $table->string('gateway_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
