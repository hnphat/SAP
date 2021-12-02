<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Saleoffv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saleoffv2', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_hd')->unsigned();
            $table->foreign('id_hd')->references('id')->on('hop_dong');
            $table->integer('id_bh_pk_package')->unsigned();
            $table->foreign('id_bh_pk_package')->references('id')->on('packagev2');
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
        Schema::dropIfExists('saleoffv2');
    }
}
