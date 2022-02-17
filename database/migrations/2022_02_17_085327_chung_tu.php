<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChungTu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chung_tu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gio');
            $table->string('ngay');
            $table->string('noiDung');
            $table->string('slug');
            $table->string('soLuong')->nullable();
            $table->string('nguoiYeuCau')->nullable();
            $table->string('boPhan')->nullable();
            $table->string('ghiChu')->nullable();
            $table->string('url')->nullable();
            $table->boolean('allow')->default(false);
            $table->integer('user_create')->unsigned();
            $table->foreign('user_create')->references('id')->on('users');
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
        Schema::dropIfExists('chung_tu');
    }
}
