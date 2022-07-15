<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadSettingsToLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->string('display_download_menu')->nullable();
            $table->string('display_download_pdf')->nullable();
            $table->string('display_download_csv')->nullable();
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
            $table->dropColumn('display_download_menu');
            $table->dropColumn('display_download_pdf');
            $table->dropColumn('display_download_csv');
        });
    }
}
