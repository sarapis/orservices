<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('organization_recordid')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_alternate_name')->nullable();
            $table->string('organization_logo_x')->nullable();
            $table->string('organization_x_uid')->nullable();
            $table->longText('organization_description')->nullable();
            $table->text('organization_email')->nullable();
            $table->string('organization_forms_x_filename')->nullable();
            $table->string('organization_forms_x_url')->nullable();
            $table->string('organization_url')->nullable();
            $table->string('organization_status_x')->nullable();
            $table->string('organization_status_sort')->nullable();
            $table->string('organization_legal_status')->nullable();
            $table->string('organization_tax_status')->nullable();
            $table->string('organization_tax_id')->nullable();
            $table->string('organization_year_incorporated')->nullable();
            $table->text('organization_services')->nullable();
            $table->text('organization_phones')->nullable();
            $table->text('organization_locations')->nullable();
            $table->text('organization_contact')->nullable();
            $table->longText('organization_details')->nullable();
            $table->string('organization_tag')->nullable();
            $table->text('organization_airs_taxonomy_x')->nullable();
            $table->string('flag')->nullable();
            $table->string('organization_website_rating')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
