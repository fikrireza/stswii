<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerProductPurchPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amd_partner_product_purch_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parner_product_id')->unsigned();
            $table->decimal('gross_purch_price', 9,0);
            $table->boolean('flg_tax')->default(false);
            $table->string('tax_percentage')->nullable();
            $table->dateTime('datetime_start');
            $table->dateTime('datetime_end');
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
        Schema::dropIfExists('amd_partner_product_purch_prices');
    }
}
