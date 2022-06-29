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
            $table->string('issued_date')->nullable();
            $table->string('issued_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ine_results', function (Blueprint $table) {
            $table->dropColumn('issued_date');
            $table->dropColumn('issued_by');
        });
    }
};
