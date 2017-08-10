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
        Schema::create('sw_partner_product', function (Blueprint $table) {
            $table->bigIncrements('partner_product_id');
            $table->bigInteger('partner_pulsa_id');
            $table->bigInteger('provider_id');
            $table->bigInteger('product_id');
            $table->string('partner_product_code');
            $table->string('partner_product_name');
            $table->string('active', 1)->default('Y');
            $table->string('active_datetime');
            $table->string('non_active_datetime');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->bigInteger('create_user_id')->unsigned();
            $table->string('update_datetime');
            $table->bigInteger('update_user_id')->unsigned();
            $table->timestamps();

            // $table->foreign('partner_pulsa_id')->references('partner_pulsa_id')->on('sw_partner_pulsa')->onDelete('cascade');
            // $table->foreign('provider_id')->references('provider_id')->on('sw_provider')->onDelete('cascade');
            // $table->foreign('product_id')->references('product_id')->on('sw_product')->onDelete('cascade');
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
