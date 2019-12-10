<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->bigInteger('detail_recordid')->nullable();
            $table->text('detail_value')->nullable();
            $table->text('detail_type')->nullable();
            $table->text('detail_description')->nullable();
            $table->text('detail_organizations')->nullable();
            $table->text('detail_services')->nullable();
            $table->text('detail_locations')->nullable();
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
        Schema::dropIfExists('details');
    }
}
