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
            $table->string('date_identifier')->nullable()->change();
            $table->string('owner_identifier')->nullable()->change();
            $table->string('credential_identifier')->nullable()->change();
            $table->string('vertical_number')->nullable()->after('credential_identifier');
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
            $table->string('date_identifier')->nullable(false)->change();
            $table->string('owner_identifier')->nullable(false)->change();
            $table->string('credential_identifier')->nullable(false)->change();
            $table->dropColumn('vertical_number');
        });
    }
};
