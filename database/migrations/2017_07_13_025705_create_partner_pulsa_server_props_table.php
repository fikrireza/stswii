<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerPulsaServerPropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_partner_pulsa_server_properties', function (Blueprint $table) {
            $table->bigIncrements('partner_pulsa_id');
            $table->string('server_url')->unique();
            $table->string('api_key');
            $table->string('api_secret');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->bigInteger('create_user_id')->unsigned();
            $table->string('update_datetime');
            $table->bigInteger('update_user_id')->unsigned();
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
        Schema::dropIfExists('amd_partner_pulsa_server_props');
    }
}
