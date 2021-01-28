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
            $table->string('name')->nullable();
            $table->longText('services')->nullable();
            $table->string('phones')->nullable();
            $table->longText('locations')->nullable();
            $table->string('weekday')->nullable();
            $table->string('byday')->nullable();
            $table->string('opens_at')->nullable();
            $table->string('opens')->nullable();
            $table->string('closes_at')->nullable();
            $table->string('closes')->nullable();
            $table->string('dtstart')->nullable();
            $table->string('until')->nullable();
            $table->string('special')->nullable();
            $table->string('closed')->nullable();
            $table->string('service_at_location')->nullable();
            $table->string('freq')->nullable();
            $table->string('valid_from')->nullable();
            $table->string('valid_to')->nullable();
            $table->string('wkst')->nullable();
            $table->string('interval')->nullable();
            $table->string('count')->nullable();
            $table->string('byweekno')->nullable();
            $table->string('bymonthday')->nullable();
            $table->string('byyearday')->nullable();
            $table->string('description')->nullable();
            $table->string('timezone')->nullable();
            $table->string('schedule_services')->nullable();
            $table->string('schedule_locations')->nullable();
            $table->string('schedule_holiday')->nullable();
            $table->string('schedule_closed')->nullable();
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
