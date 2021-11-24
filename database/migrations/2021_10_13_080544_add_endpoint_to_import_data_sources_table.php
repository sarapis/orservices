<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEndpointToImportDataSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_data_sources', function (Blueprint $table) {
            $table->string('endpoint')->after('airtable_base_id')->nullable();
            $table->string('key')->after('endpoint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_data_sources', function (Blueprint $table) {
            $table->dropColumn('endpoint');
            $table->dropColumn('key');
        });
    }
}
