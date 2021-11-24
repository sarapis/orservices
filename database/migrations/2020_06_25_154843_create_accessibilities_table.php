<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessibilities', function (Blueprint $table) {
            $table->id();
            $table->string('accessibility_recordid')->nullable();
            $table->string('accessibility_location')->nullable();
            $table->string('accessibility')->nullable();
            $table->longText('accessibility_details')->nullable();
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
        Schema::dropIfExists('accessibilities');
    }
}
