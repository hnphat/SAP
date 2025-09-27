<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use \Illuminate\Support\Facades\Auth;

Route::get('show/{id}', 'LaiThuController@showQR')->name('showbv');

Route::get('/', function () {
    $jsonString = file_get_contents('upload/cauhinh/app.json');
    $data = json_decode($jsonString, true);   
    if (Auth::check()) {
        return view('admin.home', ['data' => $data]);
        // return view('welcome');
    }
    return view('login');
})->name('trangchu');

// Route::get('/mainboard', function () {
//     $jsonString = file_get_contents('upload/cauhinh/app.json');
//     $data = json_decode($jsonString, true);   
//     if (Auth::check()) {
//         return view('admin.home', ['data' => $data]);
//     }
//     return view('login');
// })->name('mainboard');

Route::get('/out',function(){
    Auth::logout();
    return redirect()->route('trangchu');
})->name('out');

Route::post('/login', 'UserController@login')->name('login');
Route::group(['prefix' => 'management', 'middleware' => 'login'], function(){
    // Realtime
   Route::get('run', 'EventRealController@realTime')->name('action');
   Route::get('runreg', 'EventRealController@realTimeReg')->name('action.reg');
    // Realtime
   Route::get('user/changepass','UserController@changePass')->name('changepass.list');
   Route::post('user/change','UserController@change')->name('change');
   Route::post('user/ajax/posttep','UserController@upPic');
   Route::group(['prefix' => 'user', 'middleware' => ['f_roleuser']], function(){
        Route::get('list', 'UserController@index')->name('user.list');
        Route::get('del/{id}', 'UserController@destroy');
        Route::get('lock/{id}', 'UserController@lock');
        Route::post('create', 'UserController@store')->name('ajax.user.create');
        Route::post('update', 'UserController@update')->name('ajax.user.update');
        Route::get('lock/{id}', 'UserController@lock');
   });
    Route::group(['prefix' => 'roles', 'middleware' => ['f_role']], function(){
        Route::get('list','RolesController@index')->name('roles.list');
        Route::post('add','RolesController@add')->name('roles.add');
        Route::get('rm/{role_id}/{user_id}','RolesController@rm');
        Route::get('showdetail/{id}','RolesController@showDetail');
    });
    Route::group(['prefix' => 'phong', 'middleware' => ['f_role']], function(){
        Route::get('panel','PhongController@getPanel')->name('phong.panel');
        Route::get('getlist','PhongController@getList');
        Route::post('add','PhongController@addPhong');
        Route::post('edit','PhongController@editPhong');
        Route::post('update','PhongController@updatePhong');
        Route::post('delete','PhongController@deletePhong');
        Route::get('more/{id}','PhongController@showMore');
        Route::post('more/add','PhongController@moreAdd');
        Route::post('more/delete','PhongController@moreDelete');
        Route::post('more/addplus','PhongController@addPlus');

    });
    Route::group(['prefix' => 'group', 'middleware' => ['f_role']], function(){
        Route::get('panel','GroupController@getPanel')->name('group.panel');
        Route::get('getlist','GroupController@getList');
        Route::post('add','GroupController@addPhong');
        Route::post('edit','GroupController@editPhong');
        Route::post('update','GroupController@updatePhong');
        Route::post('delete','GroupController@deletePhong');
        Route::get('more/{id}','GroupController@showMore');
        Route::post('more/add','GroupController@moreAdd');
        Route::post('more/delete','GroupController@moreDelete');
        Route::post('more/addplus','GroupController@addPlus');

    });
    Route::group(['prefix' => 'hoso', 'middleware' => ['f_hoso']], function(){
        Route::get('list','HoSoController@index')->name('hoso.list');
        Route::get('users','HoSoController@getUser')->name('hoso.users');
        Route::post('add', 'HoSoController@store')->name('ajax.hoso.add');
        Route::get('get', 'HoSoController@getHoSo')->name('ajax.hoso.get');
        Route::post('edit', 'HoSoController@editHoSo')->name('ajax.hoso.edit');
        Route::post('update', 'HoSoController@updateHoSo')->name('ajax.hoso.update');
        Route::post('delete', 'HoSoController@deleteHoSo')->name('ajax.hoso.delete');
        Route::post('ajax/posttep', 'HoSoController@postTep');
    });
    Route::group(['prefix' => 'cauhinh', 'middleware' => ['f_hoso']], function(){
        Route::get('viewlist','CauHinhController@viewList')->name('cauhinh.panel');   
        Route::get('ajax/get','CauHinhController@getAjax');    
        Route::post('ajax/saveconfig','CauHinhController@saveConfig'); 
    });
    Route::group(['prefix' => 'baocaohopdong', 'middleware' => ['f_baocaohopdong']], function(){
        Route::get('reporthopdong','KhoController@getReportHopDong')->name('get.khohd.v2.reporthopdong');
        Route::post('danhsachhopdong','HDController@baoCaoHopDong')->name('baocaohopdong.post');  
        Route::post('loadchitiet','HDController@loadChiTietHopDong')->name('chitiethopdong.post');  
        // Route::get('exportexcel', 'ExportController@export')->name('exportexcel');
        Route::get('exportexcel/{from}/den/{to}/loaibaocao/{loai}', 'HDController@exportExcel');
        Route::get('exportexcelcustom/{from}/den/{to}/loaibaocao/{loai}', 'HDController@exportExcelCustom');
    });
    Route::group(['prefix' => 'typecar', 'middleware' => ['f_typecar']], function(){
        Route::get('list','TypeCarController@index')->name('typecar.list');
        Route::get('getlist','TypeCarController@getList')->name('typecar.getlist');
        Route::post('add', 'TypeCarController@add')->name('typecar.add');
        Route::post('edit', 'TypeCarController@showEdit')->name('typecar.showedit');
        Route::post('update', 'TypeCarController@update')->name('typecar.update');
        Route::post('delete', 'TypeCarController@delete')->name('typecar.delete');
        Route::get('more/{id}','TypeCarController@more')->name('typecar.more');
        Route::post('more/delete', 'TypeCarController@deleteMore')->name('typecar.more.delete');
        Route::post('more/add', 'TypeCarController@moreAdd')->name('typecar.more.add');
        Route::post('more/addplus', 'TypeCarController@addPlus')->name('typecar.more.addplus');
        Route::post('more/editshowplus', 'TypeCarController@getEditShowPlus')->name('typecar.more.editshowplus');
        Route::post('more/editaddplus', 'TypeCarController@editAddPlus')->name('typecar.more.editaddplus');
    });
    Route::group(['prefix' => 'package', 'middleware' => ['f_package']], function(){
        Route::get('list','PackageController@index')->name('package.list');
        Route::get('get/list','PackageController@getList');
        Route::post('add','PackageController@add');
        Route::post('delete','PackageController@delete');
        Route::post('edit/show','PackageController@editShow');
        Route::post('update','PackageController@update');
    });
    Route::group(['prefix' => 'cong'], function(){
        Route::get('list','CongController@index')->name('cong.list');
        Route::get('get/list','CongController@getList');
        Route::post('add','CongController@add');
        Route::post('delete','CongController@delete');
        Route::post('edit/show','CongController@editShow');
        Route::post('update','CongController@update');
    });
    Route::group(['prefix' => 'phutung'], function(){
        Route::get('get/list','PhuTungController@getList');
        Route::post('add','PhuTungController@add');
        Route::post('delete','PhuTungController@delete');
        Route::post('edit/show','PhuTungController@editShow');
        Route::post('update','PhuTungController@update');
    });
    Route::group(['prefix' => 'guest', 'middleware' => ['f_guest']], function(){
        Route::get('list','GuestController@index')->name('guest.list');
        Route::get('list/baocao','GuestController@indexBaoCao')->name('guest.list.baocao');
        Route::get('get/list','GuestController@getList');
        Route::post('getcounter','GuestController@getCounter');
        Route::get('get/list/report','GuestController@getListReport');
        Route::get('check/{num}','GuestController@checkPhone');
        Route::post('add','GuestController@add');
        Route::post('delete','GuestController@delete');
        Route::post('edit/show','GuestController@editShow');
        Route::post('update','GuestController@update');
        Route::post('update/moving','GuestController@updateMoving');

        // khách hàng - sale - hợp đồng
        Route::get('listkhachhangsalehd','GuestController@getKhachHangSaleHD')->name('khachhang.sale.hd')->middleware(['f_mkt']);
        Route::post('loadbaocaokhachhang','GuestController@loadBaoCaoKhachhangSaleHD');  

        // Khách hàng drp
        Route::get('khachhangdrp','GuestController@getKhachHangDRP')->name('khachhang.drp')->middleware(['f_mkt']);
        Route::get('bangcauhoidrp','GuestController@getCauHoiDRP')->name('khachhang.question.drp');
        Route::get('bangcauhoidrpload','GuestController@loadCauHoiDRP')->name('khachhang.question.load.drp');
        Route::post('cauhoidrppost','GuestController@postCauHoiDRP')->name('khachhang.question.post.drp');
        Route::post('deletecauhoidrpload','GuestController@deleteCauHoiDRP')->name('khachhang.question.delete.drp');
        Route::post('cauhoidrpget/{id}','GuestController@getContentCauHoiDRP')->name('khachhang.question.getcontent.drp');
        Route::post('cauhoidrpupdate','GuestController@postUpdateCauHoiDRP')->name('khachhang.question.postupdate.drp');
        // Bảng đánh giá DRP
        Route::get('loadkhachhangdrp/{from}/to/{to}/mode/{nhanvien}','GuestController@loadKhachHangDRP')->name('danhgia.drp.load');
        Route::post('postkhachhangdrp','GuestController@postKhachHangDRP')->name('danhgia.drp.post');
        Route::post('deletekhachhangdrp','GuestController@deleteKhachHangDRP')->name('danhgia.drp.delete');
        Route::get('danhgiadrp/{drpcheck}','GuestController@danhGiaDRP')->name('drp.check');
        Route::post('postdanhgia','GuestController@postDanhGiaDRP')->name('drp.check.post');
        Route::post('xacnhandanhgia','GuestController@xacNhanDanhGiaDRP')->name('drp.check.done');
        Route::post('khachhangdrp/getdata','GuestController@getGuestDRP')->name('drp.get.guest');
        Route::post('postupdatekhachhangdrp','GuestController@postUpdateGuestDRP')->name('drp.update.guest');
        Route::post('khachhangdrp/upload','GuestController@uploadFileGuestDRP')->name('drp.uploadfile');
        Route::post('khachhangdrp/xoaupload','GuestController@deleteFileGuestDRP')->name('drp.xoafile');
        // -------------- upload
        Route::post('up/file','GuestController@upFile')->name('guest.upload.file');
        Route::get('getallphone','GuestController@getAllPhone');
        Route::post('deleteonphone','GuestController@deleteOnPhone');

    });
    Route::group(['prefix' => 'kho', 'middleware' => ['f_kho']], function(){
        Route::get('list','KhoController@index')->name('kho.list');
        Route::get('get/list','KhoController@getList');
        Route::get('get/list/out','KhoController@getListOut');
        Route::get('get/list/order','KhoController@getListOrder');
        Route::post('add','KhoController@add');
        Route::post('delete','KhoController@delete');
        Route::post('edit/show','KhoController@editShow');
        Route::post('update','KhoController@update');

        // Kho remake v2
        Route::get('getkho','KhoController@getKho')->name('get.kho.v2');
        Route::get('getkho/list','KhoController@getKhoList');
        Route::post('getkho/add','KhoController@addV2');
        Route::post('getkho/delete','KhoController@deleteV2');
        Route::post('getkho/edit/show','KhoController@editShowV2');
        Route::post('getkho/update','KhoController@updateV2');
        Route::post('getkho/updatehd','KhoController@updateV2OnlyHD');

        // xe hd
        Route::get('getkhohd','KhoController@getKhoHD')->name('get.khohd.v2');
        Route::get('getkhohd/list','KhoController@getKhoHDList');

        // report kho
        Route::get('report','KhoController@getReport')->name('get.khohd.v2.report');
        Route::get('reportloadall','KhoController@getReportAll')->name('get.khohd.v2.report.all');
        Route::get('getreportkho/{chose}/ngayfrom/{ngayfrom}/ngayto/{ngayto}','KhoController@getReportKho');
        Route::get('getdetailhd/{id}','KhoController@getDetailHD')->name('get.khohd.v2.report.detail');
        
    });
    Route::group(['prefix' => 'hd', 'middleware' => ['f_hd']], function(){
        // // ajax get quickly
        Route::get('get/guest/personal/','HDController@getGuestPersonal');
        Route::get('get/guest/company/','HDController@getGuestCompany');
        Route::get('get/guest/{id}','HDController@getGuest');

        //-------------- HD v2
        Route::get('hd/denghi','HDController@getHDDeNghi')->name('hd.denghi');
        Route::post('hd/taomau','HDController@taoMau');
        Route::get('get/pkfree/{id}','HDController@getpkfree');
        Route::get('get/pkpay/{id}','HDController@getpkpay');
        Route::get('get/pkcost/{id}','HDController@getpkcost');
        Route::get('get/total/{id}','HDController@getTotal');

        Route::post('add/pkpay','HDController@addPkPay');
        Route::post('add/pkfree','HDController@addPkFree');
        Route::post('add/pkcost','HDController@addPkCost');

        Route::post('delete/pkpay/','HDController@deletePkPay');
        Route::post('delete/pkfree/','HDController@deletePkFree');
        Route::post('delete/pkcost/','HDController@deletePkCost');

        //----- quản lý đề nghị
        Route::get('hd/denghi/quanly','HDController@getHDQuanLyDeNghi')->name('hd.quanly.denghi');
        Route::get('hd/denghi/chondenghi/{id}','HDController@chonDeNghi');
        Route::post('hd/denghi/loadpkpayfromtypecar','HDController@loadFromTypeCar');
        Route::post('hd/denghi/chonhanghoa','HDController@chonHangHoa');
        Route::get('hd/danhsach','HDController@getDanhSach');
        Route::get('hd/danhsachforlist','HDController@getDanhSachForList');
        Route::get('hd/ketoan/danhsachhopdong','HDController@getDanhSachHopDong');
        Route::post('hd/denghi/guidenghi','HDController@guiDeNghi');
        Route::post('hd/denghi/xoa','HDController@xoaDeNghi');
        Route::post('hd/denghi/yeucausua','HDController@yeuCauSua');
        Route::post('hd/denghi/yeucauhuy','HDController@yeuCauHuy');
        Route::post('hd/denghi/gangiavon','HDController@ganGiaVon');
        Route::post('hd/denghi/capnhatphivanchuyen','HDController@capNhatPhiVanChuyen');
        Route::get('getedit/pkcost/{id}','HDController@getEditPkCost');
        Route::get('getedit/pkfree/{id}','HDController@getEditPkFree');
        Route::post('postedit/pkcost/','HDController@postEditPKCost');
        Route::post('postedit/pkfree/','HDController@postEditPKFree');
        // -- Xử lý lỗi khi bị treo ở giai đoạn admin sale không duyệt được
        Route::post('hd/denghi/xulyloi','HDController@xuLyLoi');

        //----- quản lý đề nghị
        Route::get('kho/tonkho/get','KhoController@getPageTonKho')->name('sale.kho');
        Route::get('kho/tonkho/getv2','KhoController@getPageTonKhoV2ForSale')->name('sale.kho.v2');
        Route::get('kho/tonkho/getreportv2','KhoController@getReportAllForSale');
        Route::get('kho/tonkho/getdetailhdforsale/{id}','KhoController@getDetailHDForSale');
        Route::get('kho/tonkho/data','KhoController@getTonKho');

        //----- phê duyệt đề nghị
        Route::get('hd/denghi/pheduyet','HDController@getHDPheDuyetDeNghi')->name('hd.quanly.pheduyet');
        Route::post('hd/denghi/pheduyet/ok','HDController@duyetDeNghi');
        Route::post('hd/denghi/pheduyet/ganxe/ok','HDController@ganXeHDCho');
        Route::post('hd/denghi/yeucausua/ok','HDController@duyetYeuCauSua');
        Route::get('hd/denghi/checktonkho/{id}','HDController@checkTonKho');
        Route::get('hd/denghi/checktonkho/ok/{id}','HDController@checkTonKhoOk');
        Route::post('hd/denghi/huydenghi/ok','HDController@huyDeNghi');

        //----- phê duyệt hợp đồng
        Route::get('hd/denghi/pheduyethopdong','HDController@getHDPheDuyetHopDong')->name('hd.quanly.pheduyet.hopdong');
        Route::post('hd/denghi/pheduyetlead/ok','HDController@duyetDeNghiLead');
        Route::post('hd/denghi/pheduyetleadhuy/ok','HDController@duyetDeNghiLeadHuy');
        Route::post('hd/denghi/yeucausualead','HDController@duyetYeuCauSuaLead');
        Route::get('hd/history/{id}','HDController@getHistory');
        Route::post('hd/movehd','HDController@moveHD');

        // --- In ấn hợp đồng
        Route::get('banle/canhan/tienmat/down/{id}','HDController@cntm');
        Route::get('banle/canhan/nganhang/down/{id}','HDController@cnnh');
        Route::get('banle/congty/tienmat/down/{id}','HDController@cttm');
        Route::get('banle/congty/nganhang/down/{id}','HDController@ctnh');

        // --- In ấn phụ lục
        Route::get('banle/phuluc/canhan/down/{id}','HDController@inPhuLucCaNhan');
        Route::get('banle/phuluc/congty/down/{id}','HDController@inPhuLucCongTy');

        // --- In ấn đề nghị
        Route::get('banle/denghi/canhan/down/{id}','HDController@inDeNghiCaNhan');
        Route::get('banle/denghi/congty/down/{id}','HDController@inDeNghiCongTy');

         // --- In ấn worksheet
         Route::get('banle/denghi/canhan/down/worksheet/{id}','HDController@worksheetcn');

        // --- BẢng giá xe
        Route::get('listbanggia','HanhChinhController@showBangGiaXe')->name('sale.banggiaxe');
        Route::get('xemlistbanggia','HanhChinhController@showXemBangGiaXe')->name('sale.xembanggiaxe');
        Route::get('ajax/list/banggia','HanhChinhController@getBangGia');
        Route::post('ajax/post/banggia','HanhChinhController@postBangGia');
        Route::post('ajax/delete/banggia','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/banggia/{id}','HanhChinhController@getEditBangGia');
        Route::post('ajax/update/banggia','HanhChinhController@updateBangGia');

        // --- Thông báo nội bộ
        Route::get('listthongbao','HanhChinhController@showSaleThongBao')->name('sale.thongbao');
        Route::get('xemlistthongbao','HanhChinhController@showXemSaleThongBao')->name('sale.xemthongbao');
        Route::get('ajax/list/thongbao','HanhChinhController@getSaleThongBao');
        Route::post('ajax/post/thongbao','HanhChinhController@postSaleThongBao');
        Route::post('ajax/delete/thongbao','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/thongbao/{id}','HanhChinhController@getEditSaleThongBao');
        Route::post('ajax/update/thongbao','HanhChinhController@updateSaleThongBao');

        // --- In tào lao
        Route::get('complete/pdi/{id}','HDController@inPdiXe');
        Route::get('complete/bhbb/{id}','HDController@inBHBB');
        Route::get('complete/phukien/{id}','HDController@inPhuKien');
        Route::get('complete/phukienkemtheoxe/{id}','HDController@inPhuKienKemTheoXe');
        Route::get('complete/denghiruthosoxe/{id}','HDController@inDeNghiRutHoSoXe');
        Route::get('complete/thanhlyhopdong/{id}','HDController@inThanhLy');
        Route::get('complete/giayracong/{id}','HDController@inGiayRaCong');


        //-- Tra cứu phụ kiện
        Route::get('tracuu/phukien','HDController@traCuuPanel')->name('tracuu.phukien');
    });
    Route::group(['prefix' => 'ketoan', 'middleware' => ['f_hd']], function(){
        Route::get('hopdongxe','KetoanController@getKeToan')->name('ketoan');
        Route::get('hopdong/danhsach','KetoanController@getDanhSachHopDong');
        Route::get('hopdong/bienban/{id}','KetoanController@inBienBan');
        Route::get('hopdong/quyettoan/{id}','KetoanController@inQuyetToan');
        Route::get('baocaohopdong','KetoanController@getBaoCaoHopDong')->name('ketoan.baocaohopdong');
        // Bổ sung tính năng cho PKT
        Route::get('xenhanno','KetoanController@getXeNhanNo')->name('ketoan.xenhanno');
        Route::get('xenhanno/getkho','KetoanController@getKhoHDList');
        Route::post('xenhanno/edit/show/','KetoanController@getEditXeNhanNo');
        Route::post('xenhanno/update/','KetoanController@updateXeNhanNo');
         // Quản lý hợp đồng
        Route::get('quanlyhopdong','HDController@getQuanLyHopDong')->name('ketoan.quanlyhopdong');
    });
    Route::group(['prefix' => 'pheduyet', 'middleware' => ['f_pheduyet']], function(){
        Route::get('list','PheDuyetController@index')->name('pheduyet.list');
        Route::get('check/{id}','PheDuyetController@check');
        Route::get('detail/hd/{id}','PheDuyetController@detailHD');
        Route::get('get/pkpay/{id}','HDController@getpkpay');
        Route::get('get/pkfree/{id}','HDController@getpkfree');
        Route::get('get/pkcost/{id}','HDController@getpkcost');
        Route::get('get/total/{id}','HDController@getTotal');
        Route::get('huy/{sale}/{user}','PheDuyetController@huy')->name('pheduyet.huy');
    });

    Route::group(['prefix' => 'denghi', 'middleware' => ['f_denghi']], function(){
        Route::get('list','DeNghiController@index')->name('denghi.list');
        Route::post('pheduyet/show','DeNghiController@show');
        Route::post('pheduyet','DeNghiController@pheDuyet');
        Route::post('thuhoi','DeNghiController@thuHoi');
        Route::get('get/list/wait/all','DeNghiController@getListWaitAll');
    });

    Route::group(['prefix' => 'cancel', 'middleware' => ['f_cancel']], function(){
        Route::get('list','CancelHDController@index')->name('cancel.list');
        Route::post('post','CancelHDController@postCancel')->name('cancel.post');
        Route::get('del/{id}','CancelHDController@delCancel');
    });

    Route::group(['prefix' => 'laithu', 'middleware' => ['f_laithu']], function(){
        Route::get('list','LaiThuController@index')->name('laithu.list');
        Route::post('post','LaiThuController@store')->name('laithu.post');
        Route::post('del','LaiThuController@destroy');
        Route::post('change','LaiThuController@change');
        Route::post('setboss','LaiThuController@setBoss');
        Route::post('setblank','LaiThuController@setBlank');
        Route::post('show','LaiThuController@showNow');
        Route::post('getedit','LaiThuController@getEdit');
        Route::post('update','LaiThuController@update');
    });

    Route::group(['prefix' => 'status'], function(){
        Route::get('list','LaiThuController@getStatus')->name('status.list');
    });

    Route::group(['prefix' => 'reg'], function(){
        Route::get('list','LaiThuController@showReg')->name('laithu.reg');
        Route::get('list/pay','LaiThuController@showPay')->name('laithu.pay');
        Route::post('post','LaiThuController@postReg')->name('reg.post');
        Route::post('pay','LaiThuController@postPay')->name('reg.pay.post');
        Route::post('del','LaiThuController@delReg');
        Route::get('pay/{id}','LaiThuController@pay');
    });

    Route::group(['prefix' => 'duyet', 'middleware' => ['f_duyet']], function(){
        Route::get('list','LaiThuController@showDuyet')->name('laithu.duyet');
        Route::get('list/tbp','LaiThuController@showDuyetTBP')->name('laithu.tbp.duyet');
        Route::get('list/pay','LaiThuController@showDuyetPay')->name('laithu.duyet.pay');
        Route::get('list/getpay/{id}','LaiThuController@getPayId');
        Route::post('tbp/duyet','LaiThuController@allowLaiThuTBP');
        Route::post('allow','LaiThuController@allowLaiThu')->name('laithu.pay.post');
        Route::post('khongduyet','LaiThuController@khongDuyet');
        Route::post('approve','LaiThuController@approve');
    });

    Route::group(['prefix' => 'capxang'], function(){
        Route::get('list','DeNghiCapXangController@showCapXang')->name('capxang.denghi');
        Route::post('reg','DeNghiCapXangController@postDeNghi')->name('capxang.post');
        Route::post('del','DeNghiCapXangController@del');
        // In xăng
        Route::get('xang/{id}', 'DeNghiCapXangController@inXang')->name('xang.in');
        // duyet
        Route::get('duyet','DeNghiCapXangController@showDuyetCapXang')->name('capxang.duyet')->middleware(['f_capxang']);
        Route::post('allow','DeNghiCapXangController@allowCapXang')->middleware(['f_capxang']);
        Route::post('cancel','DeNghiCapXangController@cancelCapXang')->middleware(['f_capxang']);
        Route::post('leadallow','DeNghiCapXangController@leadAllowCapXang');
        // Đề nghị v2
        Route::get('loaddenghinhienlieu','DeNghiCapXangController@loadDeNghiNhienLieu')->name('capxang.loaddenghinhienlieu');
        Route::post('getxehopdong','DeNghiCapXangController@getXeHopDong')->name('capxang.getxehopdong');
        Route::post('getxehopdongchitiet','DeNghiCapXangController@getXeHopDongChiTiet')->name('capxang.getxehopdongchitiet');
    });

    Route::group(['prefix' => 'hanhchinh'], function(){
        Route::get('list','HanhChinhController@showBieuMau')->name('hanhchinh.bieumau.quanly');
        Route::get('ajax/list','HanhChinhController@getBieuMau');
        Route::post('ajax/post','HanhChinhController@postBieuMau');
        Route::post('ajax/delete','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/{id}','HanhChinhController@getEditBieuMau');
        Route::post('ajax/update','HanhChinhController@updateBieuMau');

        // thông báo xem
        Route::get('list/thongbao','HanhChinhController@showXemThongBao')->name('hanhchinh.xemthongbao');
        Route::get('ajax/xemthongbao','HanhChinhController@getXemThongBao');

        // biểu mẫu xem
        Route::get('list/bieumau','HanhChinhController@showXemBieuMau')->name('hanhchinh.xembieumau');
        Route::get('ajax/xembieumau','HanhChinhController@getXemBieuMau');

        // Hồ sơ nhân viên
        Route::get('hoso','HanhChinhController@getHoSo')->name('hanhchinh.hoso');
        Route::post('gethoso','HanhChinhController@getHoSoWithName');

        // Chứng từ mộc
        Route::get('list/chungtumoc','ChungTuController@showChungTuMoc')->name('chungtu.panel');
        Route::get('ajax/loadchungtu','ChungTuController@loadChungTu');
        Route::get('ajax/xemchungtu','ChungTuController@xemChungTu');
        Route::post('chungtuajax/delete','ChungTuController@deleteChungTu');
        Route::post('chungtuajax/getedit/{id}','ChungTuController@getEditChungTu');
        Route::post('chungtuajax/update','ChungTuController@updateChungTu');
        Route::post('chungtuajax/delete/filescan','ChungTuController@deleteFileScan');

        // Đề nghị đóng mộc
        Route::get('ajax/loaddenghimoc','ChungTuController@loadDeNghiDongMoc');
        Route::get('list/chungtumoc/denghi','ChungTuController@getDeNghiMoc')->name('denghidongdau.panel');
        Route::post('denghichungtu/post','ChungTuController@postChungTu')->name('denghichungtu.post');
        Route::post('chungtuajax/postclient','ChungTuController@postBieuMauUpdateClient')->name('chungtu.post.update.client');
        Route::post('chungtuajax/post','ChungTuController@postBieuMauUpdate')->name('chungtu.post.update');
        Route::post('chungtuajax/upfile','ChungTuController@upFile')->name('upfile.post');
        Route::post('chungtuajax/block','ChungTuController@checkBlock')->name('chungtu.block');


         // Xem Chứng từ mộc
         Route::get('list/xemchungtumoc','ChungTuController@showXemChungTu')->name('xemchungtu.panel');

         // nội quy - quy chế xem
        Route::get('list/noiquy','HanhChinhController@showXemNoiQuy')->name('noiquy.xem');
        Route::get('ajax/xemnoiquy','HanhChinhController@getXemNoiQuy');
    });

    Route::group(['prefix' => 'vpp', 'middleware' => ['f_nhansu']], function(){
        // Quản lý nhóm hàng
        Route::get('quanlynhomhang','VPPController@nhomHangPanel')->name('vpp.nhomhang.panel');
        Route::get('quanlynhomhang/loadnhomhang','VPPController@loadNhomHang');
        Route::post('quanlynhomhang/post','VPPController@postNhomHang');
        Route::post('quanlynhomhang/delete','VPPController@deleteNhomHang');
        Route::get('quanlynhomhang/edit/{id}','VPPController@loadEditNhomHang');
        Route::post('quanlynhomhang/update','VPPController@updateNhomHang');
        // Quản lý danh mục
        Route::get('quanlydanhmuc','VPPController@danhMucPanel')->name('vpp.danhmuc.panel');
        Route::get('quanlydanhmuc/loaddanhmuc','VPPController@loadDanhMuc');
        Route::post('quanlydanhmuc/post','VPPController@postDanhMuc');
        Route::post('quanlydanhmuc/delete','VPPController@deleteDanhMuc');
        Route::get('quanlydanhmuc/edit/{id}','VPPController@loadEditDanhMuc');
        Route::post('quanlydanhmuc/update','VPPController@updateDanhMuc');
        // Quản lý nhập kho
        Route::get('quanlynhapkho','VPPController@nhapKhoPanel')->name('vpp.nhapkho.panel');
        // Route::post('quanlynhapkho/post','VPPController@nhapKhoPost');
        Route::post('quanlynhapkho/update','VPPController@nhapKhoUpdate');
        Route::post('quanlynhapkho/delete','VPPController@nhapKhoDelete');
        Route::get('quanlynhapkho/loaddanhmuc','VPPController@nhapKhoLoadDanhMuc');
        Route::get('quanlynhapkho/loaddanhmucall','VPPController@nhapKhoLoadDanhMucAll');
        Route::get('quanlynhapkho/loadphieunhap','VPPController@nhapKhoLoadPhieuNhap');
        Route::get('quanlynhapkho/loadphieunhapchitiet','VPPController@nhapKhoLoadPhieuNhapChiTiet');
        Route::post('quanlynhapkho/taophieu','VPPController@nhapKhoTaoPhieuNhap');        
        // Quản lý xuất kho
        Route::get('quanlyxuatkho','VPPController@quanLyXuatKhoPanel')->name('vpp.quanlyxuatkho.panel');
        Route::get('quanlyxuatkho/loadphieu','VPPController@xuatKhoLoadPhieu');
        Route::get('quanlyxuatkho/loadphieucongcu','VPPController@xuatKhoLoadPhieuCongCu');
        Route::get('quanlyxuatkho/loadphieuchitiet','VPPController@xuatKhoLoadChiTiet');
        Route::get('quanlyxuatkho/loadphieuchitietcongcu','VPPController@xuatKhoLoadChiTietCongCu');
        Route::get('quanlyxuatkho/refresh','VPPController@refreshPage')->name('vpp.refresh');
        Route::post('quanlyxuatkho/duyetphieu','VPPController@duyetPhieu');
        Route::post('quanlyxuatkho/duyetphieucongcu','VPPController@duyetPhieuCongCu');
        Route::post('quanlyxuatkho/huyduyetphieu','VPPController@huyDuyetPhieu');
        Route::post('quanlyxuatkho/huyduyetphieucongcu','VPPController@huyDuyetPhieuCongCu');
        Route::get('quanlyxuatkho/loadnhatky','VPPController@loadNhatKy');
        // Báo cáo kho
        Route::get('baocaokho','VPPController@baoCaoKhoPanel')->name('vpp.baocaokho.panel');
        Route::post('baocaokho/tonkhothucte','VPPController@tonKhoThucTe');
        Route::post('baocaokho/biendongkho','VPPController@bienDongKho');
        Route::post('baocaokho/yeucaudaduyet','VPPController@yeuCauDaDuyet');
        Route::post('baocaokho/yeucaudoiduyet','VPPController@yeuCauDoiDuyet');
        Route::post('baocaokho/nhapkhochitiet','VPPController@nhapKhoChiTiet');
        Route::post('baocaokho/baocaophongban','VPPController@baoCaoPhongBan');
    });

    Route::group(['prefix' => 'requestvpp'], function(){
        // Đề nghị công cụ
        Route::get('denghidungcu','VPPController@deNghiCongCuPanel')->name('vpp.denghicongcu.panel');
        Route::post('denghicongcu/update','VPPController@deNghiUpdate');
        Route::post('denghicongcu/updatecongcu','VPPController@deNghiUpdateCongCu');
        Route::post('denghicongcu/delete','VPPController@yeuCauDelete');
        Route::post('denghicongcu/deletecongcu','VPPController@yeuCauDeleteCongCu');
        Route::get('denghicongcu/loaddanhmuc','VPPController@nhapKhoLoadDanhMuc');
        Route::get('denghicongcu/loadnhomsp','VPPController@loadNhomSP');
        Route::get('denghicongcu/loadsp/{id}','VPPController@loadSP');
        Route::get('denghicongcu/loaddanhmuccongcu','VPPController@nhapKhoLoadCongCu');
        Route::get('denghicongcu/congcu/dangsudung','VPPController@congCuDangSuDung');
        Route::post('denghicongcu/tracongcu','VPPController@traCongCu');
        Route::post('denghicongcu/duyettracongcu','VPPController@duyetTra');
        Route::post('denghicongcu/tuchoi','VPPController@tuChoi');
        Route::get('denghicongcu/loadphieu','VPPController@deNghiLoadPhieu');
        Route::get('denghicongcu/loadphieucongcu','VPPController@deNghiLoadPhieuCongCu');
        Route::get('denghicongcu/loadphieuchitiet','VPPController@deNghiLoadChiTiet');
        Route::get('denghicongcu/loadphieuchitietcongcu','VPPController@deNghiLoadChiTietCongCu');
        Route::post('denghicongcu/taophieu','VPPController@yeuCauTaoPhieu');
        Route::post('denghicongcu/taophieucongcu','VPPController@yeuCauTaoPhieuCongCu');
        Route::post('denghicongcu/nhanhang','VPPController@nhanHang');
    });

    Route::group(['prefix' => 'nhansu'], function(){
        Route::get('quanlychamcong','NhanSuController@quanLyChamCong')->name('nhansu.panel')->middleware(['f_nhansu']);
        Route::get('quanly/ajax/getnhanvien','NhanSuController@quanLyChamCongGetNhanVien');
        Route::post('quanly/ajax/postnhanvien','NhanSuController@quanLyChamCongPostNhanVien');
      
        // LoaiPhep -> Quản lý loại phép
        Route::get('quanlyphep','NhanSuController@quanLyPhep')->name('quanlyphep.panel')->middleware(['f_nhansu']);
        Route::get('quanlyphep/ajax/getlist','NhanSuController@quanLyPhepGetList');
        Route::post('quanlyphep/ajax/post','NhanSuController@quanLyPhepPost');
        Route::post('quanlyphep/ajax/delete','NhanSuController@quanLyPhepDelete');

        // Chi tiết chấm công
        Route::get('chitiet','NhanSuController@chiTietChamCong')->name('chitiet.panel');
        Route::get('chitiet/ajax/getnhanvien','NhanSuController@chiTietGetNhanVien');
        Route::post('chitiet/ajax/getnhanvieninfo','NhanSuController@chiTietGetNhanVienInfo');
        Route::get('chitiet/ajax/getnhanvienroom','NhanSuController@chiTietGetNhanVienRoom');
        Route::post('chitiet/ajax/themphep','NhanSuController@chiTietThemPhep');

        // xin phép
        Route::get('xinphep','NhanSuController@xinPhepGetList')->name('xinphep.panel');
        Route::get('xinphep/ajax/getnhanvien','NhanSuController@xinPhepGetNhanVien');
        Route::post('xinphep/ajax/delete','NhanSuController@xinPhepDelete');
        Route::post('xinphep/ajax/deleteadmin','NhanSuController@xinPhepDeleteAdmin')->middleware(['f_nhansu']);
        Route::get('xinphep/ajax/getphepnam/{id}/nam/{nam}','NhanSuController@getPhepNam');
        Route::get('xinphep/ajax/dongbo','NhanSuController@dongBoPhep');

        // xin tăng ca
        Route::post('chitiet/ajax/themtangca','NhanSuController@chiTietThemTangCa');

        // phê duyệt phép
        Route::get('pheduyet','NhanSuController@pheDuyetGetList')->name('pheduyet.panel')->middleware(['f_nhansupheduyet']);
        // Route::get('pheduyet/ajax/getlist','NhanSuController@pheDuyetPhepGetList');
        Route::post('pheduyet/ajax/pheduyet','NhanSuController@pheDuyetPhep');
        // Xử lý phép Server Processing
        Route::get('pheduyet/ssp/getlist','NhanSuController@pheDuyetPhepDataTable');


        // phê duyệt tăng ca
        Route::get('pheduyettangca','NhanSuController@getTangCaPanel')->name('tangca.panel')->middleware(['f_nhansupheduyet']);
        Route::get('pheduyettangca/ajax/getlist','NhanSuController@pheDuyetTangCaGetList');
        Route::post('pheduyettangca/ajax/pheduyet','NhanSuController@pheDuyetTangCa');
        Route::post('capnhattangca/ajax/capnhat','NhanSuController@capNhatTangCa');
        Route::post('pheduyettangca/ajax/delete','NhanSuController@tangCaDelete');
        Route::post('pheduyettangca/ajax/deleteadmin','NhanSuController@tangCaDeleteAdmin');

         // tổng hợp
        Route::get('tonghop','NhanSuController@getTongHop')->name('tonghop.panel')->middleware(['f_nhansu']);
        Route::get('tonghop/ajax/getngay','NhanSuController@tongHopXemNgay');
        Route::get('tonghop/ajax/getthang','NhanSuController@tongHopXemThang');

         // Xem tháng
        Route::get('xemthang/ngay/{ngay}/thang/{thang}/nam/{nam}','NhanSuController@xemThang')->name('xemthang.panel')->middleware(['f_nhansu']);

        // Import Excel
        Route::get('importexcel','NhanSuController@getImport')->name('import.panel')->middleware(['f_nhansu']);
        Route::post('import','NhanSuController@importExcel')->name('import.post');

         // Chốt công
        Route::get('chotcong','NhanSuController@getChotCong')->name('chotcong.panel')->middleware(['f_nhansu']);
        Route::post('chotcong/ajax/chot','NhanSuController@chotCong');
        Route::get('chotcong/ajax/get','NhanSuController@chiTietChotCong')->name('getchotcong');
        Route::post('chotcong/ajax/huy','NhanSuController@huyChotCong');
        Route::post('chotcong/ajax/xacnhanall','NhanSuController@xacNhanAll');
        Route::post('chotcong/ajax/huyall','NhanSuController@huyAll');

        // Quản lý phê duyệt
        Route::get('quanlypheduyetphep','NhanSuController@getQuanLyPheDuyet')->name('quanlypheduyet.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlypheduyetphep/ajax/getlist','NhanSuController@quanLyPheDuyetGetList');
        Route::post('quanlypheduyetphep/ajax/seen','NhanSuController@quanLyPheDuyetSeen');

        // Quản lý phê duyệt tăng ca
        Route::get('quanlypheduyettangca','NhanSuController@getQuanLyPheDuyetTangCa')->name('quanlypheduyettangca.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlypheduyettangca/ajax/getlist','NhanSuController@quanLyPheDuyetTangCaGetList');
        Route::post('quanlypheduyettangca/ajax/seen','NhanSuController@quanLyPheDuyetTangCaSeen');

        // Quản lý tăng ca/ngày lễ
        Route::get('quanlytangcale','NhanSuController@getQuanLyTangCaLe')->name('quanlytangcale.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlytangca/ajax/get','NhanSuController@getQuanLyTangCa');
        Route::post('quanlytangca/ajax/postnhanvien','NhanSuController@quanLyTangCaPost');
        Route::post('quanlytangca/ajax/xoa','NhanSuController@xoaNhanVienTangCa');
        Route::post('quanlytangca/ajax/themall','NhanSuController@themAllNhanVienTangCa');
        Route::post('quanlytangca/ajax/huyall','NhanSuController@huyAllNhanVienTangCa');

        // Báo cáo phép năm
        Route::get('baocaophepnam','NhanSuController@getBaoCaoPhepNam')->name('baocaophepnam.panel')->middleware(['f_nhansupheduyet']);
        Route::get('loadbaocaophepnam','NhanSuController@loadBaoCaoPhepNam');

        // Quản lý biên bản
        Route::get('quanlybienban','NhanSuController@getPanelBB')->name('bienban.panel')->middleware(['f_nhansupheduyet']);
        Route::get('ajax/loadbienban','NhanSuController@loadBienBan'); 
        Route::post('ajax/postbienban','NhanSuController@postBienBan'); 
        Route::post('ajax/loadnhanvienbbkt','NhanSuController@loadNhanVien')->name('loadnhanvienbbkt'); 
        Route::post('ajax/delete/bienbankhenthuong','NhanSuController@deleteBBKT'); 

        // Quản lý khen thưởng
        Route::get('quanlykhenthuong','NhanSuController@getPanelKT')->name('khenthuong.panel')->middleware(['f_nhansupheduyet']);
        Route::get('ajax/loadkhenthuong','NhanSuController@loadKhenThuong'); 
        Route::post('ajax/postkhenthuong','NhanSuController@postKhenThuong'); 
        Route::post('ajax/loadnhanvienbbkt','NhanSuController@loadNhanVien')->name('loadnhanvienbbkt'); 
        Route::post('ajax/delete/bienbankhenthuong','NhanSuController@deleteBBKT'); 

        // Quản lý khen thưởng
        Route::post('xembienban','NhanSuController@xemBienBan');

        // Báo cáo lương
        Route::get('loadbaocaoluong','NhanSuController@loadBaoCaoLuong')->name('nhansu.baocaoluong');
        Route::get('loadquanlyluong','NhanSuController@quanLyLuong')->name('nhansu.quanlyluong')->middleware(['f_nhansu']);
        Route::post('ajax/importfileluong/','NhanSuController@importLuong');
        Route::post('loadluong','NhanSuController@loadLuong');
    });

    Route::group(['prefix' => 'caphoa'], function(){
        Route::get('panel','CapHoaController@getPanel')->name('caphoa.panel');
        Route::post('post','CapHoaController@postCapHoa')->name('caphoa.post');
        Route::post('del','CapHoaController@delCapHoa');
        Route::post('duyet','CapHoaController@duyetCapHoa');
    });

    Route::get('qr/{content}', function ($content) {
//        return QrCode::size(200)->generate('https://google.com');
        return view('qr', ['content' => $content]);
    })->where('content', '.*')->name('qrcode');

    Route::group(['prefix' => 'report', 'middleware' => ['f_report']], function(){
        Route::get('list','ReportController@showReport')->name('report');
        Route::get('load','ReportController@loadReport')->name('report.load');
        Route::post('save','ReportController@saveReport')->name('report.save');
        Route::post('savenotsend','ReportController@saveNotSend');
        // --- thêm hợp đồng chi tiết
        Route::post('addcar','ReportController@addCar')->name('add.car');
        Route::get('loadaddcar/{id}','ReportController@loadAddCar')->name('add.car.load');
        Route::post('deletecar','ReportController@deleteCar')->name('add.car.delete');

        //--- thêm công việc
        Route::post('addwork','ReportController@addWork')->name('add.work');
        Route::get('loadwork','ReportController@loadWork')->name('add.work.load');
        Route::post('deletework','ReportController@deleteWork')->name('add.work.delete');

        // update kpi
        Route::post('updatekpikd','ReportController@updateKpiKD');
        Route::post('updatekpidv','ReportController@updateKpiDV');
    });

    Route::group(['prefix' => 'overview', 'middleware' => ['f_report']], function(){
        Route::get('list','ReportController@overviewList')->name('overview.list');
        Route::get('worklist','ReportController@overviewWorkList')->name('overview.worklist');
        Route::get('getworklist','ReportController@getWorkList');
        Route::get('status','ReportController@status')->name('overview.status');
        Route::get('statusmonth/{_month}/room/{_room}','ReportController@statusMonth')->name('overview.status.month');
        //-- pkd
        Route::get('getpkdall/{_from}/to/{_to}','ReportController@getPKDAll');

        //-- pdv
        Route::get('getpdvall/{_from}/to/{_to}','ReportController@getPDVAll');

        //-- xuong
        Route::get('getxuongall/{_from}/to/{_to}','ReportController@getXuongAll');

        //-- cskh
        Route::get('getcskhall/{_from}/to/{_to}','ReportController@getCSKHAll');

        //-- mkt
        Route::get('getmktall/{_from}/to/{_to}','ReportController@getMktAll');

        // -- All Report Work
        Route::get('reportworkadmin/{id}/date/{_from}/to/{_to}/check/{check}','ReportController@getReportWorkAdmin');
    });

    Route::group(['prefix' => 'work', 'middleware' => ['f_work']], function(){
        //--- Công việc trong ngày
        Route::get('list','WorkController@show')->name('worktohard');
        Route::get('getworklist','WorkController@getWorkList');
        Route::post('addwork','WorkController@addWork');
        Route::post('delwork','WorkController@delWork');
        Route::get('getworkedit/{id}','WorkController@getWorkEdit');
        Route::post('editwork','WorkController@editWork');
        Route::post('check','WorkController@checkWork');

        //--- Công việc trong ngày
        Route::get('complete','WorkController@complete')->name('complete.list');
        Route::get('complete/list','WorkController@showComplete')->name('complete');

        //--- Đang thực hiện
        Route::get('working','WorkController@working')->name('working.list');
        Route::get('working/list','WorkController@showWorking');
        Route::post('editworking','WorkController@editWorking');

        //--- Giao việc
        Route::get('pushwork','WorkController@pushWork')->name('work.push');
        Route::get('pushwork/list','WorkController@showPushWork');
        Route::post('addpushwork','WorkController@addPushWork');
        Route::post('editpushwork','WorkController@editPushWork');
        Route::post('delpushwork','WorkController@delPushWork');
        Route::post('pushcheck','WorkController@pushCheckWork');
        Route::post('approve','WorkController@approve');
        Route::post('noapprove','WorkController@noApprove');
        Route::post('checkpush','WorkController@checkPushWork');
        Route::get('viewmore/{id}','WorkController@viewMore');

        //--- Nhận việc
        Route::get('getwork','WorkController@getWork')->name('work.get');
        Route::get('getworkdetail','WorkController@getWorkDetail');
        Route::post('getnoapprove','WorkController@getNoApprove');
        Route::post('getapprove','WorkController@getApprove');
    });

    Route::group(['prefix' => 'nhatky', 'middleware' => ['f_role']], function(){
        Route::get('list','NhatKyController@getList')->name('nhatky.list');   
        Route::get('ajax/xem','NhatKyController@loadList');      

        // Tra cứu nâng cao
        Route::get('gettracuu','NhatKyController@getTraCuu')->name('tracuunangcao');
        // Route::post('loadnhatky','NhatKyController@loadNhatKy')->name('nhatky.loadnhatky');
        Route::get('loadnhatky','NhatKyController@loadNhatKyV2')->name('nhatky.loadnhatky');
        Route::post('loadnhatkyv2','NhatKyController@loadNhatKyNangCao')->name('nhatky.loadnhatkyv2');
    });

    Route::group(['prefix' => 'marketing', 'middleware' => ['f_mkt']], function(){
        Route::get('getindex','MktController@index')->name('mkt.index');   
        Route::post('postdata','MktController@postData');
        Route::post('loadbaocao','MktController@loadBaoCao');
        Route::post('setcounter','MktController@setCounter');
        Route::post('setgroup','MktController@setGroup');
        Route::post('deleteguest','MktController@deleteGuest');
        Route::post('revertguest','MktController@revertGuest');
        Route::post('getsalelist','MktController@getSaleList');
        Route::post('setsale','MktController@setSale');
        Route::post('setfail','MktController@setFail');
    });

    Route::group(['prefix' => 'dichvu'], function(){
        // quản lý bảo hiểm
        Route::post('loadtimkiembaohiem','DichVuController@timKiemBaoHiem')->name('timkiembaogiabaohiem'); 
        // quản lý phụ kiện
        Route::post('loadtimkiem','DichVuController@timKiem')->name('timkiembaogia'); 
        Route::post('loadcounterbadge','DichVuController@counterBadge')->name('counterbadge'); 
        Route::post('loadbaogia','DichVuController@loadBaoGia')->name('loadbaogia'); 
        Route::post('loadhanghoa','DichVuController@taiHangHoa')->name('loadhanghoa');    
        Route::post('deletebaogia','DichVuController@deleteBaoGia')->name('deletebaogia');  
        Route::post('thuchienbaogia','DichVuController@thucHienBaoGia')->name('thuchienbaogia');     
        Route::post('huybaogia','DichVuController@huyBaoGia')->name('huybaogia');     
        Route::post('donebaogia','DichVuController@doneBaoGia')->name('donebaogia'); 
        // in ấn
        Route::get('inbaogia/{idbg}','DichVuController@printBaoGia'); 
        Route::get('inyeucaucapvattu/{idbg}','DichVuController@printYeuCauCapVatTu'); 
        Route::get('inlenhsuachua/{idbg}','DichVuController@printLenhSuaChua'); 
        Route::get('inquyettoan/{idbg}','DichVuController@printQuyetToan'); 
        // hạng mục
        Route::post('loadhangmuc','DichVuController@taiHangMuc')->name('loadhangmuc');     
        Route::post('loadbhpk','DichVuController@taiBHPK')->name('loadbhpk');     
        Route::post('luubhpk','DichVuController@luuBHPK')->name('luuhangmuc'); 
        Route::post('updatebhpk','DichVuController@updateBHPK')->name('updatehangmuc'); 
        Route::post('lienketphukien','DichVuController@lienKetPhuKien')->name('lienketphukien');   
        Route::post('refreshhangmuc','DichVuController@refreshHM')->name('refreshhangmuc'); 
        Route::post('delhangmuc','DichVuController@delHM')->name('delhangmuc'); 
        Route::post('loadtongcong','DichVuController@getTong')->name('loadtongcong');     
  
        // quản lý phụ kiện
        Route::get('phukienpanel','DichVuController@phuKienPanel')->name('phukien.panel')->middleware(['f_bhpk']);     
        Route::post('phukien/timhopdong','DichVuController@timHopDong')->name('timhopdong'); 
        Route::post('phukien/timhopdongwithdata','DichVuController@timHopDongWithData')->name('timhopdongwithdata');  
        Route::post('phukien/timkhachhang','DichVuController@timKhachHang')->name('timkhachhang');        
        Route::post('phukien/postbaogia','DichVuController@postBaoGia')->name('postbaogia');        
        Route::post('phukien/editbaogia','DichVuController@editBaoGia')->name('editbaogia');  
        
         // quản lý phụ kiện
         Route::get('baohiempanel','DichVuController@baoHiemPanel')->name('baohiem.panel')->middleware(['f_bhpk']);     

        // quản lý khách hàng
        Route::get('khachhang','DichVuController@khachHangPanel')->name('phukien.khachhang')->middleware(['f_bhpk']);   
        Route::get('get/list','DichVuController@getKhachHang');    
        Route::post('guest/add','DichVuController@addKhachHang');    
        Route::post('guest/delete','DichVuController@delKhachHang');    
        Route::post('guest/edit/show/','DichVuController@getKhachHangEdit'); 
        Route::post('guest/update/','DichVuController@updateKhachHang'); 

        // quản lý hạng mục
        Route::get('hangmuc','DichVuController@hangMucPanel')->name('dichvu.hangmuc')->middleware(['f_bhpk']);   
        Route::get('hangmuc/get/list','DichVuController@getHangMuc');    
        Route::post('hangmuc/guest/add','DichVuController@addHangMuc');  
        Route::post('hangmuc/guest/delete','DichVuController@delHangMuc'); 
        Route::post('hangmuc/khoa','DichVuController@khoaHangMuc');     
        Route::post('hangmuc/mapall','DichVuController@mapAllHangMuc'); 
        Route::post('hangmuc/guest/edit/show/','DichVuController@getHangMucEdit'); 
        Route::post('hangmuc/guest/update/','DichVuController@updateHangMuc'); 
        Route::post('hangmuc/ajax/importfile/','DichVuController@importDanhMuc'); 
        Route::post('hangmuc/ajax/hiddendanhmuc/','DichVuController@hiddenDanhMuc');

        // báo cáo doanh thu
        Route::get('baocaodoanhthu','DichVuController@baoCaoDoanhThuPanel')->name('dichvu.baocaodoanhthu.panel')->middleware(['f_bhpk']); 
        Route::post('loadbaocaodoanhthu','DichVuController@loadBaoCaoDoanhThu'); 
        Route::post('counterdoanhthu','DichVuController@counterBaoCaoDoanhThu');  
        Route::get('exportexcel/{from}/den/{to}/loaibaocao/{loai}/u/{u}', 'DichVuController@exportExcel');
        Route::get('exportexceltophukien/{from}/den/{to}/loaibaocao/{loai}/u/{u}', 'DichVuController@exportExcelToPhuKien');
        // báo cáo tiến độ
        Route::get('baocaotiendo','DichVuController@baoCaoTienDoPanel')->name('dichvu.baocaotiendo.panel')->middleware(['f_bhpk']); 
        Route::post('loadbaocaotiendo','DichVuController@loadTienDo');
        Route::post('loadbaocaotiendo/loaddoanhthu','DichVuController@loadTienDoDoanhThu');

        // Lấy dữ liệu chỉnh sửa
        Route::post('hangmuc/baohiemphukien/getedit/','DichVuController@getEditHangMuc')->name('getedithangmuc');
        Route::post('hangmuc/baohiemphukien/getedithangmuc/','DichVuController@getEditHangMucHangHoa')->name('getedithangmuchanghoa');
        Route::post('hangmuc/baohiemphukien/postktv/','DichVuController@postKTV')->name('postktv');
        Route::post('hangmuc/baohiemphukien/xoaktv/','DichVuController@xoaKTV')->name('xoaktv');
        // cập nhật dữ liệu chỉnh sửa 
        Route::post('hangmuc/baohiemphukien/postedit/','DichVuController@editHangMuc')->name('postedithangmuc'); 

        // Ghi nhận doanh thu
        Route::get('doanhthuphukien/panel','DichVuController@getDTPK')->name('dichvu.doanhthuphukien');
        Route::get('ghinhandoanhthu/getDTPK/list','DichVuController@getDTPKList');
        Route::post('geteditthu/show','DichVuController@showEditThu');
        Route::post('updatethu','DichVuController@updateThu');
        Route::post('hoantrang','DichVuController@hoanTrang');
        Route::post('cancelend','DichVuController@cancelEnd');
        Route::post('hoantatcongviec','DichVuController@hoanTatCongViec');
        Route::post('hoantrangcongviec','DichVuController@hoanTrangCongViec');
    });


    Route::group(['prefix' => 'cuochop', 'middleware' => ['f_hop']], function(){
        Route::get('quanlyhop','HopController@getQuanLy')->name('cuochop.panel');
        Route::get('quanlyhop/getlist','HopController@getList');
        Route::post('quanlyhop/post','HopController@postHop');
        Route::post('quanlyhop/postedit','HopController@postEdit');
        Route::post('quanlyhop/delete','HopController@deleteHop');
        Route::post('quanlyhop/loadmember','HopController@loadMember');
        Route::post('quanlyhop/loadedit','HopController@loadEdit');
        Route::post('quanlyhop/postmem','HopController@postMember');
        Route::post('quanlyhop/deletemem','HopController@deleteMem');

        // Mở rộng họp
        Route::get('quanlyhop/morong/{id}','HopController@hopMoRong');
        Route::post('chitiethop/postvande','HopController@postVanDe');
        Route::post('chitiethop/loadchitiet','HopController@loadChiTiet');
        Route::post('chitiethop/xoavande','HopController@xoaVanDe');
        Route::post('chitiethop/postmem','HopController@postMemVanDe');
        Route::post('chitiethop/loadchitietmem','HopController@loadChiTietMem');
        Route::post('chitiethop/deletemem','HopController@deleteMemChiTiet');
        Route::post('chitiethop/loadgopy','HopController@loadGopY');
        Route::post('chitiethop/postgopy','HopController@postGopY');
        Route::post('chitiethop/xoagopy','HopController@xoaGopY');
        Route::post('chitiethop/loadsuagopy','HopController@suaLoadGopY');
        Route::post('chitiethop/suagopy','HopController@suaGopY');
        Route::post('chitiethop/loadcapnhat','HopController@loadCapNhat');
        Route::post('chitiethop/capnhatketluan','HopController@capNhatKetLuan');

        // Tra cứu
        Route::get('tracuuhop','HopController@getTraCuu')->name('cuochop.tracuu.panel');
        Route::get('tracuuhop/getlist','HopController@getListTraCuu');
        Route::post('tracuuhop/loadchitietvande','HopController@loadChiTietVanDe');
        Route::get('tracuuhop/morong/{id}','HopController@hopMoRongVanDe');
        Route::post('tracuuhop/xacnhan','HopController@xacNhan');

        // Tra cứu ADMIN
        Route::get('tracuuhopad','HopController@getTraCuuAd')->name('cuochop.tracuuad.panel');
        Route::get('tracuuhop/getlistad','HopController@getListTraCuuAd');

    });

    Route::group(['prefix' => 'danhgia'], function(){
        Route::get('list','DanhGiaController@getPanel')->name('danhgia.panel');   
        Route::get('getlist','DanhGiaController@getList');   
        Route::post('them','DanhGiaController@post');   
        Route::post('delete','DanhGiaController@delete');   
        Route::post('ajax/post','DanhGiaController@postSign');   
    });

    Route::group(['prefix' => 'xecuuho'], function(){
        Route::get('index','XeCuuHoController@index')->name('quanlyxecuuho');   
        Route::get('danhsach','XeCuuHoController@danhsach');   
        Route::post('them','XeCuuHoController@post')->name('xecuuho.them');   
        Route::post('upfile','XeCuuHoController@upFile')->name('xecuuho.upfile');
        Route::post('upfilemap','XeCuuHoController@upFileMap')->name('xecuuho.upfile.map');
        Route::post('delete/filescan','XeCuuHoController@deleteFileScan');
        Route::post('delete/map','XeCuuHoController@deleteMap');
        Route::post('/getedit/{id}','XeCuuHoController@getEdit');
        Route::post('update','XeCuuHoController@postUpdate')->name('xecuuho.post.update');
        Route::post('ghiso','XeCuuHoController@ghiSo')->name('xecuuho.ghiso');
        Route::post('delete','XeCuuHoController@delete');
    });
});
