<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderPrefixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_provider_prefix', function (Blueprint $table) {
            $table->bigIncrements('provider_prefix_id');
            $table->bigInteger('provider_id');
            $table->string('prefix');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->bigInteger('create_user_id')->unsigned();
            $table->string('update_datetime');
            $table->bigInteger('update_user_id')->unsigned();
            $table->timestamps();

            $table->unique(['provider_id', 'prefix']);
            // $table->foreign('provider_id')->references('provider_id')->on('sw_provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amd_provider_prefixes');
    }
}
