<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Xenhanno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xe_nhan_no', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_khoanvay')->unsigned();
            $table->foreign('id_khoanvay')->references('id')->on('khoan_vay');
            $table->string('maXe')->nullable();
            $table->string('vin')->nullable();
            $table->string('tenXe')->nullable();
            $table->string('soBaoLanh')->nullable();
            $table->integer('tienBaoLanh')->default(0);
            $table->string('ngayHanThanhToan')->default(0);
            $table->boolean('isSapNhanNo')->default(0);
            $table->string('ngayNhanNo')->default(0);
            $table->boolean('isNhanNo')->default(0);
            $table->boolean('isDaThanhToan')->default(0);
            $table->integer('tienThanhToan')->default(0);
            $table->boolean('isCoKhachMua')->default(0);
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
        Schema::dropIfExists('xe_nhan_no');
    }
}
