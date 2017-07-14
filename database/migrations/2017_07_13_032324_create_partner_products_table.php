<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amd_partner_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('partner_pulsa_id')->unsigned();
            $table->bigInteger('provider_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->string('partner_product_code');
            $table->string('partner_product_name');
            $table->boolean('active')->default(false);
            $table->dateTime('active_datetime')->nullable();
            $table->dateTime('non_active_datetime')->nullable();
            $table->bigInteger('version')->unsigned()->nullable();
            $table->bigInteger('create_user_id')->unsigned()->nullable();
            $table->bigInteger('update_user_id')->unsigned()->nullable();
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
        Schema::dropIfExists('amd_partner_products');
    }
}
