<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraXesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tra_xe', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_user_pay')->unsigned();
            $table->foreign('id_user_pay')->references('id')->on('users');
            $table->integer('id_xe_lai_thu')->unsigned();
            $table->foreign('id_xe_lai_thu')->references('id')->on('xe_lai_thu');
            $table->integer('km_current')->nullable();
            $table->integer('fuel_current')->nullable();
            $table->string('car_status', 255)->nullable();
            $table->string('date_return', 50)->nullable();
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
        Schema::dropIfExists('tra_xe');
    }
}
