<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_data', function (Blueprint $table) {
            $table->id();
            $table->string('session_recordid')->nullable();
            $table->string('session_name')->nullable();
            $table->bigInteger('session_organization')->nullable();
            $table->string('session_method')->nullable();
            $table->string('session_disposition')->nullable();
            $table->string('session_records_edited')->nullable();
            $table->longText('session_notes')->nullable();
            $table->string('session_status')->nullable();
            $table->string('session_verification_status')->nullable();
            $table->string('session_edits')->nullable();
            $table->string('session_performed_by')->nullable();
            $table->timestamp('session_performed_at')->nullable();
            $table->timestamp('session_verify')->nullable();
            $table->string('session_start')->nullable();
            $table->string('session_end')->nullable();
            $table->string('session_duration')->nullable();
            $table->timestamp('session_start_datetime')->nullable();
            $table->string('session_end_datetime')->nullable();
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
        Schema::dropIfExists('session_data');
    }
}
