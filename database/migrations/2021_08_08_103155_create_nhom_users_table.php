<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_user', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_nhom')->unsigned();
            $table->foreign('id_nhom')->references('id')->on('nhom');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->boolean('leader')->default(false);
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
        Schema::dropIfExists('nhom_user');
    }
}
