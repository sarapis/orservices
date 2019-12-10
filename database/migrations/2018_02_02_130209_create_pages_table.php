<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('pages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->text('body');
            $table->string('files');
            $table->timestamps();
            $table->softDeletes();
        });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }

}
