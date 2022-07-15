<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrationMessageHelptextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helptexts', function (Blueprint $table) {
            $table->longText('registration_message')->after('sdoh_code_helptext')->nullable();
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
            $table->dropColumn('registration_message');
        });
    }
}
