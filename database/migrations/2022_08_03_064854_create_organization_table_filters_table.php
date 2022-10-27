<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationTableFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_table_filters', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('filter_name')->nullable();
            $table->text('organization_tags')->nullable();
            $table->text('service_tags')->nullable();
            $table->text('status')->nullable();
            $table->string('bookmark_only')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
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
        Schema::dropIfExists('organization_table_filters');
    }
}
