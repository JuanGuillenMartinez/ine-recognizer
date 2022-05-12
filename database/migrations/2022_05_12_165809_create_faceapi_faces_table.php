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
        Schema::create('faceapi_faces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faceapi_person_id')->constrained()->cascadeOnDelete();
            $table->string('url_image');
            $table->string('persisted_face_id');
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
        Schema::dropIfExists('faceapi_faces');
    }
};
