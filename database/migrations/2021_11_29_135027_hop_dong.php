<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HopDong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hop_dong', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_guest')->unsigned();
            $table->foreign('id_guest')->references('id')->on('guest');
            $table->integer('id_car_sale')->unsigned()->nullable();
            $table->foreign('id_car_sale')->references('id')->on('type_car_detail');
            $table->integer('id_car_kho')->unsigned()->nullable();
            $table->foreign('id_car_kho')->references('id')->on('kho_v2');
            $table->integer('id_user_create')->unsigned();
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->boolean('requestCheck')->default(false);
            $table->boolean('admin_check')->default(false);
            $table->boolean('lead_check')->default(false);
            $table->boolean('isTienMat')->default(true);
            $table->string('lyDoCancel')->nullable();
            $table->string('code')->nullable();
            $table->boolean('lead_check_cancel')->default(false);
            $table->boolean('requestCancel')->default(false);
            $table->string('lyDoEdit')->nullable();
            $table->boolean('requestEditHD')->default(false);
            $table->boolean('lead_check_edit')->default(false);
            $table->integer('tienCoc')->default(0);
            $table->integer('giaXe')->default(0);
            $table->integer('giaNiemYet')->default(0);
            $table->integer('hoaHongMoiGioi')->default(0);
            $table->string('hoTen')->nullable();
            $table->string('CMND2')->nullable();
            $table->string('dienThoai')->nullable();
            $table->string('mau', 20)->nullable();
            $table->boolean('hdWait')->default(false);
            $table->boolean('hdDaiLy')->default(false);
            $table->integer('htvSupport')->default(0);
            $table->boolean('isGiaVon')->default(true);
            $table->integer('giaVon')->default(0);
            $table->string('nguonKH')->nullable();
            $table->integer('phiVanChuyen')->default(0);
            // Bổ sung thêm tính năng cho PKT
            $table->integer('hoaHongSale')->default(0);   
            $table->integer('magiamgia')->default(0); 
            $table->string('dinhKem')->nullable();        
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
        Schema::dropIfExists('hop_dong');
    }
}
