<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempServiceToTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->bigInteger('temp_service_recordid')->nullable()->after('status');
            $table->bigInteger('temp_organization_recordid')->nullable()->after('temp_service_recordid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('temp_service_recordid');
            $table->dropColumn('temp_organization_recordid');
        });
    }
}
