<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaohiemHopdongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baohiem_hopdong', function (Blueprint $table) {
            $table->increments('id');
            
            // Liên kết tới khách hàng bảo hiểm
            $table->integer('id_guest_baohiem')->unsigned();
            $table->foreign('id_guest_baohiem')
                  ->references('id')
                  ->on('guest_baohiem')
                  ->onDelete('cascade');
                  
            // Thông tin hợp đồng bảo hiểm
            $table->date('ngayNhap')->nullable()->comment('Ngày nhập hợp đồng');
            $table->string('donViBaoHiem')->comment('Đơn vị bảo hiểm: MIC, BẢO VIỆT,...');
            $table->string('loaiHinhBaoHiem')->comment('Loại hình bảo hiểm: TNDS, VCX,...');
            $table->bigInteger('tongPhi')->default(0)->comment('Tổng phí bảo hiểm');
            
            // Thông tin chi tiết về xe tại thời điểm cấp bảo hiểm
            $table->string('loaiXe')->nullable()->comment('Dòng xe: ACCENT, STAGAZER,...');
            $table->integer('namSanXuat')->nullable()->comment('Năm sản xuất của xe');
            $table->bigInteger('giaTriXe')->nullable()->comment('Giá trị xe bảo hiểm');
            
            // Thời gian hiệu lực
            $table->date('ngayCap')->nullable()->comment('Ngày cấp bảo hiểm');
            $table->date('ngayHieuLuc')->nullable()->comment('Ngày hiệu lực bảo hiểm');
            $table->date('ngayKetThuc')->nullable()->comment('Ngày kết thúc bảo hiểm');
            
            // Người phụ trách kinh doanh
            $table->string('nvKinhDoanh')->nullable()->comment('Nhân viên kinh doanh bán bảo hiểm');
            
            // Người thực hiện nhập hệ thống
            $table->integer('id_user_create')->unsigned()->comment('ID tài khoản user nhập hệ thống');
            $table->foreign('id_user_create')->references('id')->on('users');
            $table->integer('soQuyetToan')->nullable()->comment('Số Quyết toán cho đơn hàng');
            $table->string('yeuCau')->nullable()->comment('Yêu cầu khách hàng');
            $table->integer('giamGia')->default(0);


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
        Schema::dropIfExists('baohiem_hopdong');
    }
}
