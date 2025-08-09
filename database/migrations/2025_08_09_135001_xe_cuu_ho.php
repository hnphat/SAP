<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class XeCuuHo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xe_cuu_ho', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('khachHang');
            $table->string('sdt')->nullable();
            $table->string('yeuCau');
            $table->string('hinhThuc');
            $table->string('baoGia');
            $table->string('diaDiemDi');
            $table->string('thoiGianDi');
            $table->string('thoiGianVe');
            $table->string('doanhThu')->nullable();
            $table->string('ghiChu')->nullable();
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
        Schema::dropIfExists('xe_cuu_ho');
    }
}
