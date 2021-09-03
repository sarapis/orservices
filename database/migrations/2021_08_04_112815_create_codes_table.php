<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('code_system')->nullable();
            $table->string('resource')->nullable();
            $table->string('resource_element')->nullable();
            $table->string('category')->nullable();
            $table->longText('description')->nullable();
            $table->enum('is_panel_code', ['yes', 'no'])->nullable()->default('no');
            $table->enum('is_multiselect', ['yes', 'no'])->nullable()->default('no');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('codes');
    }
}
