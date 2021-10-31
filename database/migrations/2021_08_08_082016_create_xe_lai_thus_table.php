<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeLaiThusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xe_lai_thu', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 100)->nullable();
            $table->string('number_car', 50)->nullable();
            $table->string('mau', 50)->nullable();
            $table->enum('status', ['DSC', 'DSD', 'T'])->default('T');
            $table->integer('id_user_use')->unsigned()->nullable();
            $table->foreign('id_user_use')->references('id')->on('users');
            $table->string('duKien',255)->nullable();
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
        Schema::dropIfExists('xe_lai_thu');
    }
}
