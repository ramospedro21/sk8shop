<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVariantsTableChangeWeightCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->decimal('weight', 9, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->decimal('weight', 13, 9)->change();
        });
    }
}
