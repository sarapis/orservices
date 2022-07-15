<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceTagsToExportConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_configurations', function (Blueprint $table) {
            $table->longText('service_tags')->nullable()->after('organization_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_configurations', function (Blueprint $table) {
            $table->dropColumn('service_tags');
        });
    }
}
