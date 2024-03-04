<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('location_type')->nullable();
            $table->string('location_url')->nullable();
            $table->string('external_identifier')->nullable();
            $table->string('external_identifier_type')->nullable();
            $table->string('location_languages')->nullable();
            $table->string('accessesibility_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('location_type');
            $table->dropColumn('location_url');
            $table->dropColumn('external_identifier');
            $table->dropColumn('external_identifier_type');
            $table->dropColumn('location_languages');
            $table->dropColumn('accessesibility_url');
        });
    }
}
