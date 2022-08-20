<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KtvBhpk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ktv_bhpk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_baogia')->unsigned();
            $table->foreign('id_baogia')->references('id')->on('baogia_bhpk');
            $table->integer('id_bhpk')->unsigned();
            $table->foreign('id_bhpk')->references('id')->on('baohiem_phukien');
            $table->integer('id_work')->unsigned();
            $table->foreign('id_work')->references('id')->on('users');
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
        Schema::dropIfExists('ktv_bhpk');
    }
}
