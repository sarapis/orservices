<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOidToCodeSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SQLite reserves "oid" as a rowid alias; only the in-memory test database skips it.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('code_systems', function (Blueprint $table) {
            $table->string('oid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('code_systems', function (Blueprint $table) {
            $table->dropColumn('oid');
        });
    }
}
