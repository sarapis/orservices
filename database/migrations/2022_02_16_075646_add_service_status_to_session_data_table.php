<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceStatusToSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_data', function (Blueprint $table) {
            $table->string('session_service')->nullable()->after('session_organization');
            $table->string('service_status')->nullable()->after('session_service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_data', function (Blueprint $table) {
            $table->dropColumn('session_service');
            $table->dropColumn('service_status');
        });
    }
}
