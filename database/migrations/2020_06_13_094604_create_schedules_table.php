<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('schedule_recordid')->nullable();
            $table->string('schedule_id', 45)->nullable();
            $table->text('schedule_services')->nullable();
            $table->string('schedule_locations')->nullable();
            $table->text('schedule_x_phones')->nullable();
            $table->string('schedule_days_of_week', 45)->nullable();
            $table->string('schedule_opens_at', 45)->nullable();
            $table->string('schedule_closes_at', 45)->nullable();
            $table->string('schedule_holiday', 45)->nullable();
            $table->string('schedule_start_date')->nullable();
            $table->string('schedule_end_date')->nullable();
            $table->text('address_locations')->nullable();
            $table->text('schedule_description')->nullable();
            $table->string('schedule_closed', 45)->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
