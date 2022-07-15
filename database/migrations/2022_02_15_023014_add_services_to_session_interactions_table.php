<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicesToSessionInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_interactions', function (Blueprint $table) {
            $table->longText('organization_services')->nullable();
            $table->longText('organization_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_interactions', function (Blueprint $table) {
            $table->dropColumn('organization_services');
            $table->dropColumn('organization_status');
        });
    }
}
