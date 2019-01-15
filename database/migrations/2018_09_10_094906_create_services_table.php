<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->bigInteger('service_recordid')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_alternate_name')->nullable();
            $table->bigInteger('service_organization')->nullable();
            $table->text('service_description')->nullable();
            $table->text('service_locations')->nullable();
            $table->text('service_url')->nullable();
            $table->text('service_email')->nullable();
            $table->string('service_status', 45)->nullable();
            $table->string('service_taxonomy')->nullable();
            $table->text('service_application_process')->nullable();
            $table->string('service_wait_time', 45)->nullable();
            $table->string('service_fees', 45)->nullable();
            $table->string('service_accreditations', 45)->nullable();
            $table->string('service_licenses', 45)->nullable();
            $table->text('service_phones')->nullable();
            $table->string('service_schedule', 45)->nullable();
            $table->string('service_contacts', 45)->nullable();
            $table->text('service_details')->nullable();
            $table->text('service_address')->nullable();
            $table->string('service_metadata', 45)->nullable();
            $table->string('flag', 45)->nullable();
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
