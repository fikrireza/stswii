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
        Schema::create('sw_partner_pulsa', function (Blueprint $table) {
            $table->bigIncrements('partner_pulsa_id');
            $table->string('partner_pulsa_code')->unique();
            $table->text('description');
            $table->string('partner_pulsa_name');
            // DEPOSIT/DENOM/TERMIN
            $table->string('type_top');
            $table->integer('payment_termin')->unsigned()->default(0);

            $table->string('active', 1)->default('Y');
            $table->string('active_datetime');
            $table->string('non_active_datetime');
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
        Schema::dropIfExists('amd_partner_pulsas');
    }
}
