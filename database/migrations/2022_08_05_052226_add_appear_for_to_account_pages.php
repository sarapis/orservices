<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppearForToAccountPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_pages', function (Blueprint $table) {
            $table->longText('appear_for')->nullable()->after('sidebar_widget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_pages', function (Blueprint $table) {
            $table->dropColumn('appear_for');
        });
    }
}
