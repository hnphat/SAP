<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaogiaBhpk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baogia_bhpk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->boolean('isPKD')->default(false);
            $table->boolean('isBaoHiem')->default(false);
            $table->string('hopDongKD')->nullable();
            $table->string('soHopDongKD')->nullable();
            $table->string('nvKD')->nullable();
            $table->integer('saler')->nullable();
            $table->string('thoiGianVao');
            $table->string('ngayVao');
            $table->string('thoiGianHoanThanh');
            $table->string('ngayHoanThanh');
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
            $table->string('yeuCau');
            $table->boolean('inProcess')->default(false);
            $table->boolean('isDone')->default(false);
            $table->string('lyDoHuy')->nullable();
            $table->boolean('isCancel')->default(false);
            $table->boolean('trangThaiThu')->default(false);
            $table->string('ngayThu', 10)->nullable();
            $table->integer('doanhThu')->default(0);
            $table->integer('tienCoc')->default(0);
            $table->integer('tang')->default(0);
            $table->integer('chietKhau')->default(0);
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
        Schema::dropIfExists('baogia_bhpk');
    }
}
