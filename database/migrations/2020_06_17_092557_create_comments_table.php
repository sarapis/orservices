<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comments_recordid')->nullable();
            $table->string('comments_content')->nullable();
            $table->string('comments_user')->nullable();
            $table->string('comments_organization')->nullable();
            $table->string('comments_contact')->nullable();
            $table->string('comments_location')->nullable();
            $table->string('comments_user_firstname')->nullable();
            $table->string('comments_user_lastname')->nullable();
            $table->string('comments_datetime')->nullable();
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
        Schema::dropIfExists('comments');
    }
}
