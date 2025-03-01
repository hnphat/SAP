<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class XacNhanCong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xac_nhan_cong', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->integer('thang');
            $table->integer('nam');
            $table->float('phepNam', 8, 2)->nullable();
            $table->float('ngayCong', 8, 2)->nullable();
            $table->float('tangCa', 8, 2)->nullable(); // default
            // Add for detail
            $table->float('tangCa100', 8, 2)->nullable();
            $table->float('tangCa150', 8, 2)->nullable();
            $table->float('tangCa200', 8, 2)->nullable();
            $table->float('tangCa300', 8, 2)->nullable();
            // --------------
            $table->integer('tongTre')->nullable();
            $table->integer('khongPhep')->nullable();
            $table->integer('khongPhepNgay')->nullable();
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
        Schema::dropIfExists('xac_nhan_cong');
    }
}
