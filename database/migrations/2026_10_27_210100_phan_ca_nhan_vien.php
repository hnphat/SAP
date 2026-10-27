<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhanCaNhanVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phan_ca_nhan_vien', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned()->unique();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->integer('id_ca_lam')->unsigned();
            $table->foreign('id_ca_lam')->references('id')->on('ca_lam')->onDelete('cascade');
            $table->string('ghi_chu')->nullable();
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
        Schema::dropIfExists('phan_ca_nhan_vien');
    }
}
