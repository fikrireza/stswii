<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerPulsasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amd_partner_pulsas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parner_pulsa_code');
            $table->text('description');
            $table->string('partner_pulsa_name');
            $table->boolean('flg_need_deposit')->default(false);
            $table->integer('payment_termin')->unsigned();
            $table->boolean('active')->default(false);
            $table->dateTime('active_datetime');
            $table->dateTime('non_active_datetime');
            $table->bigInteger('version')->unsigned();
            $table->bigInteger('create_user_id')->unsigned();
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
        Schema::dropIfExists('amd_partner_pulsas');
    }
}
