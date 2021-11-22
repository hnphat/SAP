<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Report extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['pkd', 'pdv', 'mkt', 'ketoan', 'xuong', 'cskh', 'hcns', 'it', 'ptdl'])->nullable();
            $table->string('ngayReport')->nullable();
            $table->string('timeReport')->nullable();
            $table->integer('user_report')->unsigned();
            $table->foreign('user_report')->references('id')->on('users');
            //------------------ PKD
            $table->integer('doanhSoThang')->nullable();
            $table->float('thiPhanThang', 8, 2)->nullable();
            //------------------ PDV
            $table->integer('luotXeDV')->nullable();
            $table->integer('doanhThuDV')->nullable();
//            $table->integer('thiPhanThang')->nullable();
            $table->integer('xuatHoaDon')->nullable();
            $table->integer('xuatNgoaiTinh')->nullable();
            $table->integer('xuatTrongTinh')->nullable();
            $table->integer('hdHuy')->nullable();
            $table->integer('hdDaiLy')->nullable();
            $table->integer('ctInternet')->nullable();
            $table->integer('ctShowroom')->nullable();
            $table->integer('ctHotline')->nullable();
            $table->integer('ctSuKien')->nullable();
            $table->integer('ctBLD')->nullable();
            $table->integer('saleInternet')->nullable();
            $table->integer('saleMoiGioi')->nullable();
            $table->integer('saleThiTruong')->nullable();
            $table->integer('khShowRoom')->nullable();
            //------------------ PDV
            $table->integer('baoDuong')->nullable();
            $table->integer('suaChua')->nullable();
            $table->integer('Dong')->nullable();
            $table->integer('Son')->nullable();
            $table->integer('congBaoDuong')->nullable();
            $table->integer('congSuaChuaChung')->nullable();
            $table->integer('congDong')->nullable();
            $table->integer('congSon')->nullable();
            $table->integer('dtPhuTung')->nullable();
            $table->integer('dtDauNhot')->nullable();
            $table->integer('dtPhuTungBan')->nullable();
            $table->integer('dtDauNhotBan')->nullable();
            $table->integer('phuTungMua')->nullable();
            $table->integer('dauNhotMua')->nullable();
            //------------- XUONG
            $table->integer('tonBaoDuong')->nullable();
            $table->integer('tonSuaChuaChung')->nullable();
            $table->integer('tonDong')->nullable();
            $table->integer('tonSon')->nullable();
            $table->integer('tiepNhanBaoDuong')->nullable();
            $table->integer('tiepNhanSuaChuaChung')->nullable();
            $table->integer('tiepNhanDong')->nullable();
            $table->integer('tiepNhanSon')->nullable();
            $table->integer('hoanThanhBaoDuong')->nullable();
            $table->integer('hoanThanhSuaChuaChung')->nullable();
            $table->integer('hoanThanhDong')->nullable();
            $table->integer('hoanThanhSon')->nullable();
            //------------- CSKH
            $table->integer('callDatHenSuccess')->nullable();
            $table->integer('callDatHenFail')->nullable();
            $table->integer('datHen')->nullable();
            $table->integer('dvHaiLong')->nullable();
            $table->integer('dvKhongHaiLong')->nullable();
            $table->integer('dvKhongThanhCong')->nullable();
            $table->integer('muaXeSuccess')->nullable();
            $table->integer('muaXeFail')->nullable();
            $table->integer('duyetBanLe')->nullable();
            $table->integer('knThaiDo')->nullable();
            $table->integer('knChatLuong')->nullable();
            $table->integer('knThoiGian')->nullable();
            $table->integer('knVeSinh')->nullable();
            $table->integer('knGiaCa')->nullable();
            $table->integer('knKhuyenMai')->nullable();
            $table->integer('knDatHen')->nullable();
            $table->integer('knTraiNghiem')->nullable();
            //-------------- MKT
            $table->integer('khBanGiao')->nullable();
            $table->integer('khSuKien')->nullable();
            //----------------
            $table->boolean('clock')->default(false);
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
        Schema::dropIfExists('report');
    }
}
