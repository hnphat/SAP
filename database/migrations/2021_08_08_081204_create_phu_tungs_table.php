<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhuTungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phu_tung', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_dv')->unsigned();
            $table->foreign('id_dv')->references('id')->on('dv');
            $table->string('name', 255)->nullable();
            $table->integer('cost')->nullable();
            $table->integer('thue')->default(0);
            $table->integer('id_loai_phu_tung')->unsigned();
            $table->foreign('id_loai_phu_tung')->references('id')->on('loai_phu_tung');
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
        Schema::dropIfExists('phu_tung');
    }
}
