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
        Schema::create('back_ine_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faceapi_person_id')->constrained();
            $table->string('citizen_identifier')->nullable();
            $table->string('cic')->nullable();
            $table->string('ocr')->nullable();
            $table->string('model')->nullable();
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
        Schema::dropIfExists('back_ine_results');
    }
};
