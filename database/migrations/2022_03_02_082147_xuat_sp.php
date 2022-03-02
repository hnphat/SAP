<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class XuatSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xuat_sp', function (Blueprint $table) {
            $table->integer('id_danhmuc')->unsigned();
            $table->foreign('id_danhmuc')->references('id')->on('danhmuc_sp');
            $table->integer('id_xuat')->unsigned();
            $table->foreign('id_xuat')->references('id')->on('phieu_xuat')->onDelete('cascade');
            $table->integer('soLuong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xuat_sp');
    }
}
