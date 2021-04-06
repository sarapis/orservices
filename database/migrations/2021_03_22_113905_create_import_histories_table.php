<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_histories', function (Blueprint $table) {
            $table->id();
            $table->string('source_name')->nullable();
            $table->string('import_type')->nullable();
            $table->enum('auto_sync', [0, 1])->default(0);
            $table->enum('status', ['In-progress', 'Completed', 'Error'])->default('In-progress');
            $table->longText('error_message')->nullable();
            $table->integer('sync_by')->nullable();
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
        Schema::dropIfExists('import_histories');
    }
}
