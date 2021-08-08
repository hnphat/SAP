<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDVSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dv', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_guest_dv')->unsigned();
            $table->foreign('id_guest_dv')->references('id')->on('guest_dv');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->string('date_create', 10)->nullable();
            $table->string('soBaoGia', 100)->nullable();
            $table->string('noiDungSuaChua', 255)->nullable();
            $table->boolean('complete')->default(false);
            $table->string('ghiChu', 255)->nullable();
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
        Schema::dropIfExists('dv');
    }
}
