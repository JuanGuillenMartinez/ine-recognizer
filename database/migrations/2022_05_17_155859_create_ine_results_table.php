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
        Schema::create('ine_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained();
            $table->string('url_image');
            $table->string('faceId');
            $table->string('top');
            $table->string('left');
            $table->string('width');
            $table->string('height');
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
        Schema::dropIfExists('ine_results');
    }
};
