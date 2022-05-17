<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DanhGia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ngay');
            $table->integer('thang');
            $table->integer('nam');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('soBaoGia');
            $table->integer('diemVeSinh')->nullable();
            $table->boolean('veSinhLai')->nullable();
            $table->string('chuKy')->nullable();
            $table->boolean('isWrong')->default(false);
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
        Schema::dropIfExists('danh_gia');
    }
}
