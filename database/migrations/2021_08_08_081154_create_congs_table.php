<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_dv')->unsigned();
            $table->foreign('id_dv')->references('id')->on('dv');
            $table->string('name', 255)->nullable();
            $table->integer('cost')->nullable();
            $table->integer('thue')->default(10);
            $table->integer('id_loai_cong')->unsigned();
            $table->foreign('id_loai_cong')->references('id')->on('loai_cong');
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
        Schema::dropIfExists('cong');
    }
}
