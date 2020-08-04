<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('address_recordid')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2', 45)->nullable();
            $table->string('address_city', 45)->nullable();
            $table->string('address_state_province', 45)->nullable();
            $table->string('address_postal_code', 45)->nullable();
            $table->string('address_region', 45)->nullable();
            $table->string('address_country', 45)->nullable();
            $table->string('address_attention', 45)->nullable();
            $table->string('address_type')->nullable();
            $table->text('address_locations')->nullable();
            $table->text('address_services')->nullable();
            $table->text('address_organization')->nullable();
            $table->string('flag', 45)->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
