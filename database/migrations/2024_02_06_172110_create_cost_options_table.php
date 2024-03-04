<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cost_recordid')->nullable();
            $table->text('services')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->text('option')->nullable();
            $table->string('currency')->nullable();
            $table->double('amount')->nullable();
            $table->longText('amount_description')->nullable();
            $table->longText('attributes')->nullable();
            $table->integer('created_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cost_options');
    }
}
