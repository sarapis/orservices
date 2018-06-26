<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisctrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recordid');
            $table->string('name')->nullable();
            $table->text('projects')->nullable();
            $table->string('active_pb')->nullable();
            $table->text('processes_annual')->nullable();
            $table->string('contact_district')->nullable();
            $table->string('cityCouncilDistrict')->nullable();
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
        Schema::drop('district');
    }
}
