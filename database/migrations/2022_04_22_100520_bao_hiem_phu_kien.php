<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaoHiemPhuKien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baohiem_phukien', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->boolean('isPK')->default(true);
            $table->string('ma')->unique();
            $table->string('noiDung');
            $table->string('dvt');
            $table->integer('donGia')->default(0);
            $table->integer('giaVon')->default(0);
            $table->integer('congKTV')->default(0);
            $table->string('loai');
            $table->integer('loaiXe')->nullable();
            $table->string('hinhAnh')->nullable();
            $table->boolean('isShow')->default(true);
            $table->string('thoigian')->nullable();
            $table->string('baohanh')->nullable();
            $table->string('nhacungcap')->nullable();
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
        Schema::dropIfExists('baohiem_phukien');
    }
}
