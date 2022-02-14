<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TangCa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tang_ca', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_user_duyet')->unsigned();
            $table->foreign('id_user_duyet')->references('id')->on('users');
            $table->integer('ngay');
            $table->integer('thang');
            $table->integer('nam');
            $table->string('lyDo');
            $table->boolean('user_duyet')->default(false);
            $table->boolean('duyet')->default(false);
            $table->string('time1')->nullable();
            $table->string('time2')->nullable();
            $table->float('heSo', 8, 2)->nullable();
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
        Schema::dropIfExists('tang_ca');
    }
}
