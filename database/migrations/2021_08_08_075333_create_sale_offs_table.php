<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_off', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_sale')->unsigned();
            $table->foreign('id_sale')->references('id')->on('sale');
            $table->integer('id_bh_pk_package')->unsigned();
            $table->foreign('id_bh_pk_package')->references('id')->on('bh_pk_package');
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
        Schema::dropIfExists('sale_off');
    }
}
