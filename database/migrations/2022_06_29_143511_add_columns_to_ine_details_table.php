<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ine_details', function (Blueprint $table) {
            $table->string('citizen_identifier')->nullable();
            $table->string('cic')->nullable();
            $table->string('ocr')->nullable();
            $table->string('model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ine_details', function (Blueprint $table) {
            $table->dropColumn('citizen_identifier');
        });
    }
};
