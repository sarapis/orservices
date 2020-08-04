<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('interaction_recordid')->nullable();
            $table->string('interaction_session')->nullable();
            $table->string('interaction_method')->nullable();
            $table->string('interaction_disposition')->nullable();
            $table->string('interaction_notes')->nullable();
            $table->string('interaction_records_edited')->nullable();
            $table->string('interaction_timestamp')->nullable();
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
        Schema::dropIfExists('session_interactions');
    }
}
