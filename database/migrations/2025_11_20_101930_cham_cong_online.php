<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChamCongOnline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cham_cong_online', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('buoichamcong')->unsigned()->nullable();
            $table->integer('loaichamcong')->unsigned()->nullable();
            $table->string('thoigianchamcong')->nullable();
            $table->string('hinhanh')->nullable();
            $table->string('ghichu')->nullable();
            $table->boolean('isXoa')->default(0);
            $table->boolean('isApprove')->default(0);
            $table->integer('typeApprove')->unsigned()->default(0);
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
        Schema::dropIfExists('table');
    }
}
