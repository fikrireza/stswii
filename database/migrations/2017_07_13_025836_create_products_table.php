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
        Schema::create('sw_product', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->bigInteger('provider_id');
            $table->decimal('nominal', 9,0);
            // PULSA/DATA
            $table->string('type_product');
            $table->string('active', 1)->default('Y');
            $table->string('active_datetime');
            $table->string('non_active_datetime');
            $table->bigInteger('version')->unsigned();
            $table->string('create_datetime');
            $table->bigInteger('create_user_id')->unsigned()->nullable();
            $table->string('update_datetime');
            $table->bigInteger('update_user_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('provider_id')->references('provider_id')->on('sw_provider')->onDelete('cascade');
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
