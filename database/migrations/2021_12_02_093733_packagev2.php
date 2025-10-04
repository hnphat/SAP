<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Packagev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packagev2', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->integer('cost')->default(0);
            $table->enum('type', ['free', 'pay', 'cost'])->default('free');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->boolean('cost_tang')->default(false);
            $table->boolean('free_kem')->default(true);
            $table->integer('mapk')->nullable;
            $table->string('mode', 255)->nullable;
            $table->boolean('isLanDau')->default(true);
            $table->boolean('isDuyetLanSau')->default(false);
            $table->boolean('isHuy')->default(true);
            $table->string('lyDoHuy', 255)->nullable;
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
        Schema::dropIfExists('packagev2');
    }
}
