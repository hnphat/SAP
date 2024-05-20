<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_type_guest')->unsigned();
            $table->foreign('id_type_guest')->references('id')->on('type_guest');
            $table->string('name',255);
            $table->string('phone', 20);
            $table->string('address',255);
            $table->string('daiDien',255)->nullable();
            $table->string('chucVu',255)->nullable();
            $table->string('mst',255)->nullable();
            $table->string('cmnd',255)->nullable();
            $table->string('ngayCap',255)->nullable();
            $table->string('noiCap',255)->nullable();
            $table->string('ngaySinh',255)->nullable();
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->string('nguon',50)->nullable();
            // ----------------------
            $table->boolean('lenHopDong')->default(false);
            $table->string('danhGia',10)->default('COLD');
            $table->string('xeQuanTam',255)->nullable();
            $table->string('cs1',255)->nullable();
            $table->string('cs2',255)->nullable();
            $table->string('cs3',255)->nullable();
            $table->string('cs4',255)->nullable();
            $table->string('cs5',255)->nullable();
            $table->string('cs6',255)->nullable();
            $table->string('mauSac',255)->nullable();
            $table->string('hinhThucMua',255)->nullable();
            $table->string('duKienMua',255)->nullable();
            $table->string('callEnd',255)->nullable();
            $table->string('datHen',255)->nullable();
            $table->string('lyDoChuaMua',255)->nullable();
            $table->string('lyDoLostSale',255)->nullable();
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
        Schema::dropIfExists('guest');
    }
}
