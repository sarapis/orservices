<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveLoginButtonToLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->integer('activate_login_button')->default(0);
            $table->integer('organization_share_button')->default(0);
            $table->integer('service_share_button')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->dropColumn('activate_login_button');
            $table->dropColumn('organization_share_button');
            $table->dropColumn('service_share_button');
        });
    }
}
