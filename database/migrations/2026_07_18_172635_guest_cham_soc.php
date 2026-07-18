<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuestChamSoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_cham_soc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->string('ngayChamSoc');
            $table->string('tenKhachHang');
            $table->string('dienThoai')->unique();
            $table->enum('phanLoai', ['DAMUA', 'CHUAMUA', 'KHONGMUA'])->default('DAMUA');
            $table->boolean('coTheBanBaoHiem')->default(false);
            $table->string('loaiBaoHiem')->nullable();
            $table->string('thongTinXe')->nullable();
            $table->string('donViBaoHiem')->nullable();
            $table->string('ngayMua')->nullable();
            $table->string('ngayHetHan')->nullable();
            $table->string('ketQuaChamSoc');
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
        Schema::dropIfExists('guest_cham_soc');
    }
}
