<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('contact_recordid')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_organizations')->nullable();
            $table->string('contact_services')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_department')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phones')->nullable();
            $table->string('flag')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
