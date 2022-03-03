<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Phieuxuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_xuat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ngay', 2);
            $table->string('thang', 2);
            $table->string('nam', 4);
            $table->integer('id_user_xuat')->unsigned();
            $table->foreign('id_user_xuat')->references('id')->on('users');
            $table->integer('id_user_duyet')->unsigned()->nullable();
            $table->foreign('id_user_duyet')->references('id')->on('users');
            $table->boolean('duyet')->default(false);
            $table->string('noiDungXuat');
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
        Schema::dropIfExists('phieu_xuat');
    }
}
