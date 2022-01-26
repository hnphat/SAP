<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoaiPhep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_phep', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tenPhep', 255);
            $table->string('maPhep', 10)->nullable();
            $table->enum('loaiPhep', ['COLUONG', 'KHONGLUONG', 'PHEPNAM', 'QCC']);
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
        Schema::dropIfExists('loai_phep');
    }
}
