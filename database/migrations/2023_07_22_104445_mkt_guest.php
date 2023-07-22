<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MktGuest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mkt_guest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->integer('id_group_send')->unsigned()->nullable();
            $table->foreign('id_group_send')->references('id')->on('group');
            $table->integer('id_sale_recieve')->unsigned()->nullable();
            $table->foreign('id_sale_recieve')->references('id')->on('users');
            $table->integer('id_guest_temp')->unsigned()->nullable();
            $table->boolean('block')->default(false);
            $table->string('hoTen');
            $table->string('dienThoai');
            $table->string('nguonKH');
            $table->string('yeuCau');
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
        Schema::dropIfExists('mkt_guest');
    }
}
