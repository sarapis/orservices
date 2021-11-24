<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeCategoryTableToHelptextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helptexts', function (Blueprint $table) {
            $table->longText('code_category')->nullable()->after('service_activities');
            $table->longText('sdoh_code_helptext')->nullable()->after('code_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helptexts', function (Blueprint $table) {
            $table->dropColumn('code_category');
            $table->dropColumn('sdoh_code_helptext');
        });
    }
}
