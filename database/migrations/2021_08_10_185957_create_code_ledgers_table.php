<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('rating')->nullable();
            $table->bigInteger('service_recordid')->nullable();
            $table->bigInteger('organization_recordid')->nullable();
            $table->string('SDOH_code')->nullable();
            $table->string('resource')->nullable();
            $table->string('description')->nullable();
            $table->string('code_type')->nullable();
            $table->string('code')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('code_ledgers');
    }
}
