<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuyenXemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quyen_xem', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_tai_lieu')->unsigned();
            $table->foreign('id_tai_lieu')->references('id')->on('tai_lieu');
            $table->integer('id_nhom')->unsigned();
            $table->foreign('id_nhom')->references('id')->on('nhom');
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
        Schema::dropIfExists('quyen_xem');
    }
}
