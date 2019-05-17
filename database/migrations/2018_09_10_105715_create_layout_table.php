<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo')->nullable();
            $table->string('site_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('sidebar_content')->nullable();
            $table->string('contact_text')->nullable();
            $table->string('contact_btn_label')->nullable();
            $table->string('contact_btn_link')->nullable();
            $table->text('footer')->nullable();
            $table->string('hearder_pdf')->nullable();
            $table->string('footer_pdf')->nullable();
            $table->string('footer_csv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layout');
    }
}
