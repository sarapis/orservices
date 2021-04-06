<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportDataSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->nullable();
            $table->string('format')->nullable();
            $table->string('import_type')->nullable();
            $table->string('source_file')->nullable();
            $table->string('airtable_api_key')->nullable();
            $table->string('airtable_base_id')->nullable();
            $table->string('mode')->nullable();
            $table->enum('auto_sync', [0, 1])->default(0);
            $table->integer('sync_hours')->nullable();
            $table->dateTime('last_imports')->nullable();
            $table->string('organization_tags')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_data_sources');
    }
}
