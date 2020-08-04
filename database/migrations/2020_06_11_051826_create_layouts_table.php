<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('site_name')->nullable();
            $table->string('tagline')->nullable();
            $table->longText('sidebar_content')->nullable();
            $table->longText('sidebar_content_part_1')->nullable();
            $table->string('part_1_image')->nullable();
            $table->longText('sidebar_content_part_2')->nullable();
            $table->string('part_2_image')->nullable();
            $table->longText('sidebar_content_part_3')->nullable();
            $table->string('part_3_image')->nullable();
            $table->string('banner_text1')->nullable();
            $table->string('banner_text2')->nullable();
            $table->string('contact_text')->nullable();
            $table->string('contact_btn_label')->nullable();
            $table->string('contact_btn_link')->nullable();
            $table->text('footer')->nullable();
            $table->string('homepage_background')->nullable();
            $table->string('header_pdf')->nullable();
            $table->string('footer_pdf')->nullable();
            $table->string('footer_csv')->nullable();
            $table->string('logo_active')->nullable();
            $table->string('title_active')->nullable();
            $table->string('about_active')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('button_hover_color')->nullable();
            $table->string('title_link_color')->nullable();
            $table->string('top_menu_color')->nullable();
            $table->integer('meta_filter_activate')->default(0);
            $table->string('meta_filter_on_label')->nullable();
            $table->string('meta_filter_off_label')->nullable();
            $table->longText('top_background')->nullable();
            $table->longText('bottom_background')->nullable();
            $table->string('bottom_section_active')->nullable();
            $table->longText('login_content')->nullable();
            $table->longText('register_content')->nullable();
            $table->integer('activate_login_home')->default(0);
            $table->string('home_page_style')->nullable();
            $table->integer('activate_religions')->default(0);
            $table->integer('activate_languages')->default(0);
            $table->integer('activate_organization_types')->default(0);
            $table->integer('activate_contact_types')->default(0);
            $table->integer('activate_facility_types')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layouts');
    }
}
