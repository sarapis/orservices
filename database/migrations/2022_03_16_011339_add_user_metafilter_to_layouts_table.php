<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserMetafilterToLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->string('user_metafilter_option')->nullable()->after('show_registration_message');
            $table->string('default_label')->nullable()->after('user_metafilter_option');
            $table->string('hide_organizations_with_no_filtered_services')->nullable()->after('default_label');
            $table->string('top_menu_link_hover_background_color')->nullable()->after('hide_organizations_with_no_filtered_services');
            $table->string('site_title_active')->nullable()->after('top_menu_link_hover_background_color');
            $table->string('submenu_highlight_color')->nullable()->after('site_title_active');
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
            $table->dropColumn('user_metafilter_option');
            $table->dropColumn('hide_organizations_with_no_filtered_services');
            $table->dropColumn('default_label');
            $table->dropColumn('top_menu_link_hover_background_color');
            $table->dropColumn('site_title_active');
            $table->dropColumn('submenu_highlight_color');
        });
    }
}
