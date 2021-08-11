<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeNghiCapXangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_nghi_cap_xang', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('name', 100)->nullable();
            $table->string('vin', 100)->nullable();
            $table->string('number_car', 50)->nullable();
            $table->string('lyDo', 255)->nullable();
            $table->integer('soLit')->default(5);
            $table->boolean('active')->default(false);
            $table->string('ngayCap', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('de_nghi_cap_xang');
    }
}
