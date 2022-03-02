<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DanhmucSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhmuc_sp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_nhom')->unsigned();
            $table->foreign('id_nhom')->references('id')->on('nhom_sp');
            $table->string('tenSanPham');
            $table->enum('donViTinh', ['cái', 'hộp', 'gói', 'bộ', 'bao', 'cây', 'cục', 'thùng', 'viên', 'cặp', 'kg', 'lít', 'chai', 'lọ']);
            $table->string('moTa')->nullable();
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
        Schema::dropIfExists('danhmuc_sp');
    }
}
