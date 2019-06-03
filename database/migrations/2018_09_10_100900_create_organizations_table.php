<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('organization_recordid')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_alternate_name', 45)->nullable();
            $table->string('organization_logo_x')->nullable();
            $table->string('organization_x_uid', 45)->nullable();
            $table->longText('organization_description')->nullable();
            $table->string('organization_email', 500)->nullable();
            $table->string('organization_forms_x_filename')->nullable();
            $table->string('organization_forms_x_url')->nullable();
            $table->string('organization_url')->nullable();
            $table->string('organization_status_x')->nullable();
            $table->string('organization_status_sort')->nullable();
            $table->string('organization_legal_status', 45)->nullable();
            $table->string('organization_tax_status', 45)->nullable();
            $table->string('organization_tax_id', 45)->nullable();
            $table->string('organization_year_incorporated', 45)->nullable();
            $table->text('organization_services')->nullable();
            $table->text('organization_phones')->nullable();
            $table->text('organization_locations')->nullable();
            $table->bigInteger('organization_contact')->nullable();
            $table->string('organization_details')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
