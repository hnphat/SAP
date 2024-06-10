<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LichSuKhoHC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kho_hc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ngay');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->text('noiDung');
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
        Schema::dropIfExists('kho_hc');
    }
}
