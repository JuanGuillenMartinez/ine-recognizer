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
        Schema::table('people', function(Blueprint $table) {
            $table->string('ine_url', 1000)->change();
        });
        Schema::table('faceapi_faces', function(Blueprint $table) {
            $table->string('url_image', 1000)->change();
        });
        Schema::table('faceapi_verify_results', function(Blueprint $table) {
            $table->string('url_image', 1000)->change();
        });
        Schema::table('ine_results', function(Blueprint $table) {
            $table->string('url_image', 1000)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function(Blueprint $table) {
            $table->string('ine_url', 255)->change();
        });
        Schema::table('faceapi_faces', function(Blueprint $table) {
            $table->string('url_image', 255)->change();
        });
        Schema::table('faceapi_verify_results', function(Blueprint $table) {
            $table->string('url_image', 255)->change();
        });
        Schema::table('ine_results', function(Blueprint $table) {
            $table->string('url_image', 255)->change();
        });
    }
};
