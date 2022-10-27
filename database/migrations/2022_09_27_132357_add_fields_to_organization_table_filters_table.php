<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrganizationTableFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_table_filters', function (Blueprint $table) {
            $table->timestamp('start_verified')->nullable();
            $table->timestamp('end_verified')->nullable();
            $table->timestamp('start_updated')->nullable();
            $table->timestamp('end_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_table_filters', function (Blueprint $table) {
            $table->dropColumn('start_verified');
            $table->dropColumn('end_verified');
            $table->dropColumn('start_updated');
            $table->dropColumn('end_updated');
        });
    }
}
