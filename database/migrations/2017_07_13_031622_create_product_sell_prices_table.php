<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSellPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_product_sell_price', function (Blueprint $table) {
            $table->bigIncrements('product_sell_price_id');
            $table->bigInteger('product_id');
            $table->decimal('gross_sell_price', 9,0);
            $table->string('flg_tax', 1)->default('N');
            $table->decimal('tax_percentage');
            $table->string('datetime_start');
            $table->string('datetime_end');
            $table->string('active', 1)->default('Y');
            $table->string('active_datetime');
            $table->string('non_active_datetime');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->bigInteger('create_user_id')->unsigned();
            $table->string('update_datetime');
            $table->bigInteger('update_user_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('sw_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amd_product_sell_prices');
    }
}
