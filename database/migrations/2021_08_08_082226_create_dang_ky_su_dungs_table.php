<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDangKySuDungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dang_ky_su_dung', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_user_reg')->unsigned();
            $table->foreign('id_user_reg')->references('id')->on('users');
            $table->integer('id_xe_lai_thu')->unsigned();
            $table->foreign('id_xe_lai_thu')->references('id')->on('xe_lai_thu');
            $table->string('lyDo', 255)->nullable();
            $table->integer('km_current')->nullable();
            $table->integer('fuel_current')->nullable();
            $table->string('car_status', 255)->nullable();
            $table->string('date_go', 50)->nullable();
            $table->string('date_return', 50)->nullable();
            $table->boolean('allow')->default(false);
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
        Schema::dropIfExists('dang_ky_su_dung');
    }
}
