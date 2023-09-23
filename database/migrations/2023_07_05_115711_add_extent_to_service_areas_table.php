<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtentToServiceAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_areas', function (Blueprint $table) {
            $table->string('extent')->nullable();
            $table->string('extent_type')->nullable();
            $table->string('uri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_areas', function (Blueprint $table) {
            $table->dropColumn('extent');
            $table->dropColumn('extent_type');
            $table->dropColumn('uri');
        });
    }
}
