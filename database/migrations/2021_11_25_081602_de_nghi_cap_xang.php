<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeNghiCapXang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_nghi_cap_xang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->boolean('fuel_allow')->default(false);
            $table->enum('fuel_type', ['X', 'D'])->nullable();
            $table->integer('fuel_num')->default(0);
            $table->string('fuel_car')->nullable();
            $table->string('fuel_guest')->nullable();
            $table->string('fuel_frame')->nullable();
            $table->string('fuel_lyDo')->nullable();
            $table->string('fuel_ghiChu')->nullable();
            $table->integer('fuel_km')->nullable();
            $table->integer('lead_id')->nullable();
            $table->boolean('lead_check')->default(0);
            $table->string('duongDi')->nullable();
            $table->boolean('printed')->default(0);
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
        Schema::dropIfExists('de_nghi_cap_xang');
    }
}
