<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentToDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('details', function (Blueprint $table) {
            $table->string('parent')->nullable()->after('detail_type');
            $table->string('language')->nullable()->after('parent');
            $table->longText('notes')->nullable()->after('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('details', function (Blueprint $table) {
            $table->dropColumn('parent');
            $table->dropColumn('language');
            $table->dropColumn('notes');
        });
    }
}
