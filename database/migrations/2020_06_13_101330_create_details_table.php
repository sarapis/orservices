<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('detail_recordid')->nullable();
            $table->text('detail_value')->nullable();
            $table->text('detail_type')->nullable();
            $table->text('detail_description')->nullable();
            $table->text('detail_organizations')->nullable();
            $table->text('detail_services')->nullable();
            $table->text('detail_locations')->nullable();
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
        Schema::dropIfExists('details');
    }
}
