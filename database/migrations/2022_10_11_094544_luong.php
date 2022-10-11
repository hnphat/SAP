<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Luong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luong', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manv', 10);
            $table->integer('thang');
            $table->integer('nam');
            $table->integer('dot1')->default(0);
            $table->integer('dot2')->default(0);
            $table->integer('thue')->default(0);
            $table->integer('thucLanh')->default(0);
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
        Schema::dropIfExists('luong');
    }
}
