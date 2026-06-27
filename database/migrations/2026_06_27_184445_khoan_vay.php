<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class XeBaoLanh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khoan_vay', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('soKhoangVay')->nullable();
            $table->string('noiDungVay')->nullable();
            $table->string('ngayNhanNo')->nullable();
            $table->float('laiSuat', 8, 2)->default(0);
            $table->integer('tienVay')->default(0);
            $table->string('ghiChu')->nullable();
            $table->string('nganHangVay')->nullable();
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
        Schema::dropIfExists('khoan_vay');
    }
}
