<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxonomiesRecordidToTaxonomyTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxonomy_types', function (Blueprint $table) {
            $table->bigInteger('taxonomy_type_recordid')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxonomy_types', function (Blueprint $table) {
            $table->dropColumn('taxonomy_type_recordid');
        });
    }
}
