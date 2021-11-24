<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('filter')->nullable();
            $table->string('type')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('organization_tags')->nullable();
            $table->string('key')->nullable();
            // $table->enum('auto_sync', ['0', '1'])->nullable()->default('0');
            // $table->dateTime('last_sync')->nullable();
            // $table->string('hours')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('full_path_name')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('export_configurations');
    }
}
