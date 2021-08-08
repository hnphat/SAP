<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestDvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_dv', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->string('date_create',10)->nullable();
            $table->string('name',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('address',100)->nullable();
            $table->string('number_car',50)->nullable();
            $table->string('vin', 100)->nullable();
            $table->string('frame', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->integer('km')->default(0);
            $table->string('user_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_dv');
    }
}
