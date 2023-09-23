<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->longText('service_language')->nullable();
            $table->longText('service_interpretation')->nullable();
            $table->longText('eligibility_description')->nullable();
            $table->integer('minimum_age')->nullable();
            $table->integer('maximum_age')->nullable();
            $table->longText('service_alert')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('service_language');
            $table->dropColumn('service_interpretation');
            $table->dropColumn('eligibility_description');
            $table->dropColumn('minimum_age');
            $table->dropColumn('maximum_age');
            $table->dropColumn('service_alert');
        });
    }
}
