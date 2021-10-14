<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReportWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_work', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('report');
            $table->string('tenCongViec')->nullable();
            $table->integer('tienDo')->nullable();
            $table->enum('type', ['cv','drp', 'ksnb', 'dt'])->nullable();
            $table->string('deadLine')->nullable();
            $table->string('ketQua')->nullable();
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
        Schema::dropIfExists('report_work');
    }
}
