<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NhapSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhap_sp', function (Blueprint $table) {
            $table->integer('id_danhmuc')->unsigned();
            $table->foreign('id_danhmuc')->references('id')->on('danhmuc_sp');
            $table->integer('id_nhap')->unsigned();
            $table->foreign('id_nhap')->references('id')->on('phieu_nhap')->onDelete('cascade');
            $table->integer('soLuong');
            $table->integer('donGia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhap_sp');
    }
}
