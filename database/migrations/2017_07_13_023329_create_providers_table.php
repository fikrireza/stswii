<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_provider', function (Blueprint $table) {
            $table->bigIncrements('provider_id');
            $table->string('provider_code')->unique();
            $table->string('provider_name');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->integer('create_user_id')->unsigned();
            $table->string('update_datetime');
            $table->integer('update_user_id')->unsigned();
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
        Schema::dropIfExists('amd_providers');
    }
}
