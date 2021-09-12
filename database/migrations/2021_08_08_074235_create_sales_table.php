<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_guest')->unsigned();
            $table->foreign('id_guest')->references('id')->on('guest');
            $table->integer('id_car_sale')->unsigned()->nullable();
            $table->foreign('id_car_sale')->references('id')->on('car_sale');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->boolean('complete')->default(false);
            $table->boolean('admin_check')->default(false);
            $table->boolean('lead_sale_check')->default(false);
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
        Schema::dropIfExists('sale');
    }
}
