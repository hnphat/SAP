<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CapHoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cap_hoa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('khachHang');
            $table->string('dongXe');
            $table->string('num')->unique();
            $table->string('gioGiaoXe');
            $table->string('ngayGiaoXe');
            $table->string('ghiChu')->nullable();
            $table->boolean('duyet')->default(false);
            $table->string('ngayGiaoHoa')->nullable();
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
        Schema::dropIfExists('cap_hoa');
    }
}
