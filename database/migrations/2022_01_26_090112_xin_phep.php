<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class XinPhep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_phep', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_phep')->unsigned();
            $table->foreign('id_phep')->references('id')->on('loai_phep');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('id_user_duyet')->unsigned();
            $table->foreign('id_user_duyet')->references('id')->on('users');
            $table->integer('ngay');
            $table->integer('thang');
            $table->integer('nam');
            $table->enum('buoi', ['SANG', 'CHIEU', 'CANGAY']);
            $table->string('lyDo');
            $table->boolean('user_duyet')->default(false);
            $table->boolean('duyet')->default(false);
            $table->string('vaoSang')->nullable();
            $table->string('raSang')->nullable();
            $table->string('vaoChieu')->nullable();
            $table->string('raChieu')->nullable();
            $table->integer('gioSang')->nullable();
            $table->integer('gioChieu')->nullable();
            $table->integer('treSang')->nullable();
            $table->integer('treChieu')->nullable();
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
        Schema::dropIfExists('xin_phep');
    }
}
