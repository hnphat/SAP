<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeCarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_car_detail', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_type_car')->unsigned();
            $table->foreign('id_type_car')->references('id')->on('type_car');
            $table->string('name', 100);
            $table->string('gear', 10)->nullable();
            $table->string('machine', 10)->nullable();
            $table->string('seat', 10)->nullable();
            $table->string('fuel', 10)->nullable();
            $table->integer('giaVon')->nullable();
            $table->boolean('isShow')->default(true);
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
        Schema::dropIfExists('type_car_detail');
    }
}
