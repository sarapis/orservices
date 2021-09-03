<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_recordid')->nullable();
            $table->string('service_name')->nullable();
            $table->text('service_alternate_name')->nullable();
            $table->string('service_organization')->nullable();
            $table->text('service_description')->nullable();
            $table->text('service_locations')->nullable();
            $table->text('service_url')->nullable();
            $table->text('service_program')->nullable();
            $table->text('service_email')->nullable();
            $table->string('service_status')->nullable();
            $table->string('service_taxonomy')->nullable();
            $table->text('service_application_process')->nullable();
            $table->string('service_wait_time')->nullable();
            $table->string('service_fees')->nullable();
            $table->string('service_accreditations')->nullable();
            $table->string('service_licenses')->nullable();
            $table->text('service_phones')->nullable();
            $table->text('service_schedule')->nullable();
            $table->text('service_contacts')->nullable();
            $table->text('service_details')->nullable();
            $table->text('service_address')->nullable();
            $table->text('service_airs_taxonomy_x')->nullable();
            $table->string('service_metadata')->nullable();
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
        Schema::dropIfExists('services');
    }
}
