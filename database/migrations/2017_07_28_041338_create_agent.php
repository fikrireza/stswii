<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_agent', function(Blueprint $table){
          $table->bigIncrements('agent_id');
          $table->string('agent_name');
          $table->string('phone_number');
          $table->string('address');
          $table->string('city');
          $table->string('channel_user_id');
          $table->string('channel_chat_id');
          $table->string('paloma_member_code');
          $table->string('client_id');
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
        //
    }
}
