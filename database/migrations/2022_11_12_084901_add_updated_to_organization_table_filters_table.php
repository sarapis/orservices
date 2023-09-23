<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedToOrganizationTableFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_table_filters', function (Blueprint $table) {
            $table->integer('last_verified_by')->nullable();
            $table->integer('last_updated_by')->nullable();
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
            $table->dropColumn('last_verified_by');
            $table->dropColumn('last_updated_by');
        });
    }
}
