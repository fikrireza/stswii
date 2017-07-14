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
        Schema::create('amd_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider_code');
            $table->string('provider_name');
            $table->bigInteger('version')->unsigned()->nullable();
            $table->integer('create_user_id')->unsigned()->nullable();
            $table->integer('update_user_id')->unsigned()->nullable();
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
