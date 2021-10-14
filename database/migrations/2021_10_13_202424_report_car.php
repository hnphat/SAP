<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReportCar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_car', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('report');
            $table->integer('soLuong');
            $table->string('dongXe');
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
        Schema::dropIfExists('report_car');
    }
}
