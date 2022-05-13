<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NoiDungHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noi_dung_hop', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_hop')->unsigned();
            $table->foreign('id_hop')->references('id')->on('hop_tuan');
            $table->string('noiDungHop');
            $table->text('ketLuan')->nullable();
            $table->enum('status', ['DONE', 'PROCESS', 'NEW']);
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
        Schema::dropIfExists('noi_dung_hop');
    }
}
