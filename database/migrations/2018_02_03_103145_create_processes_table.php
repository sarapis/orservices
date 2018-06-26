<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recordid');
            $table->string('name_process_annual')->nullable();
            $table->text('projects')->nullable();
            $table->string('vote_year')->nullable();
            $table->string('process_name')->nullable();
            $table->text('district_ward_name')->nullable();
            $table->string('voters')->nullable();
            $table->string('city')->nullable();
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
        Schema::drop('process');
    }
}
