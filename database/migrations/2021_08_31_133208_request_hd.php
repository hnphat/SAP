<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequestHd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_hd', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('car_detail_id')->unsigned();
            $table->foreign('car_detail_id')->references('id')->on('type_car_detail');
            $table->string('color', 20);
            $table->integer('tamUng')->nullable();
            $table->integer('giaXe')->nullable();
            $table->boolean('admin_check')->default(false);
            $table->integer('sale_id')->unsigned()->nullable();
            $table->integer('guest_id')->unsigned();
            $table->foreign('guest_id')->references('id')->on('guest');
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
        Schema::dropIfExists('request_hd');
    }
}
