<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChitietBhpk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chitiet_bhpk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_baogia')->unsigned();
            $table->foreign('id_baogia')->references('id')->on('baogia_bhpk');
            $table->integer('id_baohiem_phukien')->unsigned();
            $table->foreign('id_baohiem_phukien')->references('id')->on('baohiem_phukien');
            $table->integer('soLuong');
            $table->integer('donGia');
            $table->integer('thanhTien');
            $table->integer('chietKhau')->nullable();
            $table->integer('isTang')->default(false);
            $table->integer('id_user_work')->unsigned()->nullable();
            $table->foreign('id_user_work')->references('id')->on('users');
            $table->integer('id_user_work_two')->unsigned()->nullable();
            $table->foreign('id_user_work_two')->references('id')->on('users');
            $table->integer('tiLe')->default(10);
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
        Schema::dropIfExists('chitiet_bhpk');
    }
}
