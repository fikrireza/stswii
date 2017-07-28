<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_pos', function(Blueprint $table){
          $table->bigIncrements('pos_id');
          $table->bigInteger('doc_type_id')->unsigned();
          $table->string('doc_no');
          $table->string('purchase_datetime');
          $table->bigInteger('agent_id')->unsigned();
          $table->bigInteger('product_id')->unsigned();
          $table->bigInteger('partner_product_id')->unsigned();
          $table->string('receiver_phone_number');

          $table->decimal('gross_sell_price', 9,0);
          $table->decimal('sell_flg_tax_ammount', 9,0);
          $table->decimal('sell_tax_percentage');

          $table->decimal('gross_purch_price', 9,0);
          $table->decimal('purch_flg_tax_ammount', 9,0);
          $table->decimal('purch_tax_percentage', 9,0);
          $table->string('status', 1);
          $table->text('status_remark');

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
