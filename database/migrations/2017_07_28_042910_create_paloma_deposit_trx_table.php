<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalomaDepositTrxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sw_paloma_deposit_trx', function(Blueprint $table){
          $table->bigIncrements('paloma_deposit_trx_id');
          $table->bigInteger('tenant_id')->unsigned();
          $table->bigInteger('ou_id')->unsigned();
          $table->bigInteger('doc_type_id')->unsigned();
          $table->string('doc_no');
          $table->string('doc_date');
          $table->string('partner_code');
          $table->decimal('deposit_amount');
          $table->string('status', 1)->default('D');
          $table->bigInteger('confirmed_user_id');
          $table->string('confirmed_datetime');

          $table->bigInteger('version')->unsigned();
          $table->string('create_datetime');
          $table->bigInteger('create_user_id')->unsigned();
          $table->string('update_datetime');
          $table->bigInteger('update_user_id')->unsigned();
          $table->timestamps();

          $table->unique(['doc_no', 'doc_date', 'partner_code', 'deposit_amount']);
          $table->foreign('confirmed_user_id')->references('id')->on('sw_users')->onDelete('cascade');
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
