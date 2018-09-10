<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('contact_recordid');
            $table->string('contact_name')->nullable();
            $table->string('contact_organizations')->nullable();
            $table->string('contact_services')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_department')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phones')->nullable();
            $table->string('flag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
