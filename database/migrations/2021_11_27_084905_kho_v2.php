<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KhoV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kho_v2', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_type_car_detail')->unsigned();
            $table->foreign('id_type_car_detail')->references('id')->on('type_car_detail');
            $table->string('year',4);
            $table->string('vin', 100)->nullable();
            $table->string('frame', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('gear', 10)->nullable();
            $table->string('machine', 10)->nullable();
            $table->string('seat', 10)->nullable();
            $table->string('fuel', 10)->nullable();
            $table->enum('type', ['ORDER', 'P/O', 'MAP', 'HD', 'STORE', 'AGENT'])->nullable();
            $table->string('soDonHang', 50)->nullable();
            $table->string('soBaoLanh', 50)->nullable();
            $table->string('ngayDat', 10)->nullable();
            $table->string('ngayNhanXe', 10)->nullable();
            $table->string('nganHang', 50)->nullable();
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
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
        Schema::dropIfExists('kho_v2');
    }
}