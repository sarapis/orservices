<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('taxonomy_recordid')->nullable();
            $table->string('taxonomy_name')->nullable();
            $table->bigInteger('taxonomy_parent_name')->nullable();
            $table->string('taxonomy_vocabulary')->nullable();
            $table->string('taxonomy_x_description')->nullable();
            $table->string('taxonomy_x_notes')->nullable();
            $table->text('taxonomy_services')->nullable();
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
        Schema::dropIfExists('taxonomy');
    }
}
