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
            $table->string('time_go', 50)->nullable();
            $table->string('date_go', 50)->nullable();
            $table->string('date_duKien', 50)->nullable();
            $table->string('hoSoDi', 255)->nullable();
            $table->string('hoSoVe', 255)->nullable();
            $table->boolean('allow')->default(false);
            $table->string('date_return', 50)->nullable();
            $table->integer('tra_km_current')->nullable();
            $table->integer('tra_fuel_current')->nullable();
            $table->string('tra_car_status', 255)->nullable();
            $table->boolean('tra_allow')->default(false);
            $table->boolean('request_tra')->default(false);
            $table->boolean('fuel_request')->default(false);
            $table->boolean('fuel_allow')->default(false);
            $table->enum('fuel_type', ['X', 'D'])->nullable();
            $table->integer('fuel_num')->default(0);
            $table->string('fuel_lyDo')->nullable();
            $table->boolean('lead_check')->default(0);
            $table->integer('id_user_check')->unsigned()->nullable();
            $table->foreign('id_user_check')->references('id')->on('users');
            $table->integer('id_lead_check')->unsigned()->nullable();
            $table->boolean('id_lead_check_status')->default(false);
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
