<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_recordid')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_organization')->nullable();
            $table->string('location_alternate_name')->nullable();
            $table->string('location_transportation', 45)->nullable();
            $table->double('location_latitude')->nullable();
            $table->double('location_longitude')->nullable();
            $table->text('location_description')->nullable();
            $table->text('location_services')->nullable();
            $table->text('location_phones')->nullable();
            $table->longText('location_details', 45)->nullable();
            $table->longText('location_schedule')->nullable();
            $table->text('location_address')->nullable();
            $table->string('flag', 45)->nullable();
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
        Schema::dropIfExists('locations');
    }
}
