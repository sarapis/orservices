<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('schedule_recordid')->nullable();
            $table->string('schedule_id', 45)->nullable();
            $table->string('schedule_services', 45)->nullable();
            $table->string('schedule_locations')->nullable();
            $table->string('schedule_x_phones', 45)->nullable();
            $table->string('schedule_days_of_week', 45)->nullable();
            $table->string('schedule_opens_at', 45)->nullable();
            $table->string('schedule_closes_at', 45)->nullable();
            $table->string('schedule_holiday', 45)->nullable();
            $table->string('schedule_start_date')->nullable();
            $table->string('schedule_end_date')->nullable();
            $table->text('address_locations')->nullable();
            $table->string('schedule_closed', 45)->nullable();
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
        Schema::dropIfExists('schedule');
    }
}
