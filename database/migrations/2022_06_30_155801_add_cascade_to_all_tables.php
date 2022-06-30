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
        Schema::table('ine_results', function (Blueprint $table) {
            $table->dropForeign('ine_results_person_id_foreign');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
        Schema::table('ine_details', function (Blueprint $table) {
            $table->dropForeign('ine_details_person_id_foreign');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_person_id_foreign');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
        Schema::table('back_ine_details', function (Blueprint $table) {
            $table->dropForeign('back_ine_details_faceapi_person_id_foreign');
            $table->foreign('faceapi_person_id')->references('id')->on('faceapi_people')->onDelete('cascade');
        });
        Schema::table('faceapi_people', function (Blueprint $table) {
            $table->dropForeign('faceapi_people_person_id_foreign');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
        Schema::table('faceapi_faces', function (Blueprint $table) {
            $table->dropForeign('faceapi_faces_faceapi_person_id_foreign');
            $table->foreign('faceapi_person_id')->references('id')->on('faceapi_people')->onDelete('cascade');
        });
        Schema::table('faceapi_verify_results', function (Blueprint $table) {
            $table->dropForeign('faceapi_verify_results_faceapi_person_id_foreign');
            $table->foreign('faceapi_person_id')->references('id')->on('faceapi_people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
