<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_sale', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_type_car_detail')->unsigned();
            $table->foreign('id_type_car_detail')->references('id')->on('type_car_detail');
            $table->string('year',4);
            $table->string('vin', 100)->nullable();
            $table->string('frame', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('gear', 10)->nullable();
            $table->string('machine', 10)->nullable();
            $table->string('seat', 10)->nullable();
            $table->string('fuel', 10)->nullable();
            $table->boolean('exist')->default(true);
            $table->integer('cost')->default(0);
            $table->boolean('order')->default(false);
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
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
        Schema::dropIfExists('car_sale');
    }
}
