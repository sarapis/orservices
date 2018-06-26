<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table) {
                $table->increments('id');
                $table->string('recordid');
                $table->string('project_id')->nullable();
                $table->string('project_title')->nullable();
                $table->text('project_description')->nullable();
                $table->string('project_status')->nullable();
                $table->integer('project_status_flag')->nullable();
                $table->string('status_date_updated')->nullable();
                $table->string('process_id')->nullable();
                $table->string('source_ballot_link')->nullable();
                $table->string('district_ward_name')->nullable();
                $table->string('win_text')->nullable();
                $table->string('win_calculate')->nullable();
                $table->string('votes')->nullable();
                $table->string('vote_date')->nullable();
                $table->string('pb_cycle')->nullable();
                $table->string('cost_text')->nullable();
                $table->integer('cost_num')->nullable();
                $table->string('category_topic_committee_raw')->nullable();
                $table->string('category_type_topic_standardize')->nullable();
                $table->text('project_location_raw')->nullable();
                $table->string('project_address_clean')->nullable();
                $table->string('location_city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('full_address')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->string('neighborhood')->nullable();
                $table->string('census_tract_or_local_id')->nullable();
                $table->string('bin')->nullable();
                $table->string('borough_code')->nullable();
                $table->string('name_dept_agency_cbo')->nullable();
                $table->string('agency_code')->nullable();
                $table->string('agency_project_code')->nullable();
                $table->string('project_budget_type')->nullable();
                $table->string('flag')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
