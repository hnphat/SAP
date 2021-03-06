<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReportWorkV2 extends Migration
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
            $table->integer('user_tao')->unsigned();
            $table->foreign('user_tao')->references('id')->on('users');
            $table->string('ngayTao');
            $table->string('tenCongViec');
            $table->integer('tienDo')->default(0);
            $table->integer('user_nhan')->unsigned()->nullable();
            $table->foreign('user_nhan')->references('id')->on('users');
            $table->string('ngayStart');
            $table->string('ngayEnd');
            $table->string('ketQua')->nullable();
            $table->string('ghiChu')->nullable();
            $table->string('requestWork')->nullable();
            $table->boolean('apply')->nullable();
            $table->boolean('acceptApply')->default(false);
            $table->string('replyWork')->nullable();
            $table->boolean('isPersonal')->default(true);
            $table->boolean('isReport')->default(false);
            $table->boolean('isReportPush')->default(false);
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
