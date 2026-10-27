<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CaLam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ca_lam', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten_ca');
            $table->string('vao_sang')->nullable();
            $table->string('ra_sang')->nullable();
            $table->string('vao_chieu')->nullable();
            $table->string('ra_chieu')->nullable();
            $table->string('ghi_chu')->nullable();
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
        Schema::dropIfExists('ca_lam');
    }
}
