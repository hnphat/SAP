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
            $table->string('ngayTao')->nullable();
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('report');
            $table->integer('soLuong');
            $table->integer('dongXe')->unsigned();
            $table->foreign('dongXe')->references('id')->on('type_car');
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
