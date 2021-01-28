<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('taxonomy_recordid')->nullable();
            $table->string('taxonomy_name')->nullable();
            $table->string('taxonomy_parent_name')->nullable();
            $table->string('taxonomy_grandparent_name')->nullable();
            $table->string('category_logo')->nullable();
            $table->string('category_logo_white')->nullable();
            $table->string('taxonomy_vocabulary')->nullable();
            $table->string('taxonomy_x_description')->nullable();
            $table->string('taxonomy_x_notes')->nullable();
            $table->string('exclude_vocabulary')->nullable();
            $table->text('taxonomy_services')->nullable();
            $table->string('taxonomy_parent_recordid')->nullable();
            $table->string('taxonomy_facet')->nullable();
            $table->string('category_id')->nullable();
            $table->string('taxonomy_id')->nullable();
            $table->string('flag')->nullable();
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
        Schema::dropIfExists('taxonomies');
    }
}
