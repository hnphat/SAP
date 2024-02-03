<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DrpCheckQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drp_check_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('drp_check')->unsigned();
            $table->foreign('drp_check')->references('id')->on('drp_check');
            $table->string('drp_question')->nullable();
            $table->float('diemToiDa', 8, 2)->default(0);
            $table->float('diemCham', 8, 2)->default(0);
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
        Schema::dropIfExists('drp_check_question');
    }
}
