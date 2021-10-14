<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReportNhap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_nhap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('report');
            $table->string('nhaCungCap')->nullable();
            $table->string('hanMuc')->nullable();
            $table->integer('soLuong')->nullable();
            $table->integer('tongTon')->nullable();
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
        Schema::dropIfExists('report_nhap');
    }
}
