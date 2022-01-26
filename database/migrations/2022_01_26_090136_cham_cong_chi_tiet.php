<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChamCongChiTiet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cham_cong_chi_tiet', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('ngay');
            $table->integer('thang');
            $table->integer('nam');
            $table->string('vaoSang')->nullable();
            $table->string('raSang')->nullable();
            $table->string('vaoChieu')->nullable();
            $table->string('raChieu')->nullable();
            $table->float('gioSang', 8, 2)->nullable();
            $table->float('gioChieu', 8, 2)->nullable();
            // $table->integer('gioSang')->nullable();
            // $table->integer('gioChieu')->nullable();
            $table->integer('treSang')->nullable();
            $table->integer('treChieu')->nullable();
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
        Schema::dropIfExists('cham_cong_chi_tiet');
    }
}
