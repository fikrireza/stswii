<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amd_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->string('product_name');
            $table->bigInteger('provider_id')->unsigned();
            $table->decimal('nominal', 9,0);
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
        Schema::dropIfExists('amd_products');
    }
}
