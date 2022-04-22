<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuestDichvu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_dichvu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->string('hoTen');
            $table->string('dienThoai');
            $table->string('mst')->nullable();
            $table->string('diaChi');
            $table->string('bienSo')->nullable();
            $table->string('soKhung')->nullable();
            $table->string('soMay')->nullable();
            $table->string('thongTinXe')->nullable();
            $table->string('taiXe')->nullable();
            $table->string('dienThoaiTaiXe')->nullbale();
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
        Schema::dropIfExists('guest_đichvu');
    }
}
