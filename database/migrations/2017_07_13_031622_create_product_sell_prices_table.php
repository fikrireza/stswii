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
        Schema::create('amd_product_sell_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('gross_sell_price', 9,0);
            $table->boolean('flg_tax')->default(false);
            $table->string('tax_percentage');
            $table->dateTime('datetime_start');
            $table->dateTime('datetime_end');
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
        Schema::dropIfExists('amd_product_sell_prices');
    }
}
