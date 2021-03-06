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
    }
    return view('login');
})->name('trangchu');

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
        Route::get('get/list','GuestController@getList');
        Route::get('check/{num}','GuestController@checkPhone');
        Route::post('add','GuestController@add');
        Route::post('delete','GuestController@delete');
        Route::post('edit/show','GuestController@editShow');
        Route::post('update','GuestController@update');
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
        Route::get('getreportkho/{chose}/ngayfrom/{ngayfrom}/ngayto/{ngayto}','KhoController@getReportKho');
        
    });
    Route::group(['prefix' => 'hd', 'middleware' => ['f_hd']], function(){
        // Route::get('list','HDController@index')->name('hd.list');
        // Route::get('get/list','HDController@getList');
        // Route::get('get/list/code','HDController@getListCode');
        // Route::get('get/list/wait','HDController@getListWait');
        // Route::post('add/code','HDController@addCode');
        // Route::post('delete','HDController@delete');
        // Route::post('deleteWait','HDController@deleteWait');
        // // ajax get quickly
        Route::get('get/guest/personal/','HDController@getGuestPersonal');
        Route::get('get/guest/company/','HDController@getGuestCompany');
        Route::get('get/guest/{id}','HDController@getGuest');
        // Route::get('get/car/{id}','HDController@getCar');
        // Route::get('get/load/hd/','HDController@loadHD');
        // Route::get('get/detail/hd/{id}','HDController@detailHD');
        // // --- end ajax quickly
        // Route::get('exportToWord','HDController@test');

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

        //----- qu???n l?? ????? ngh???
        Route::get('hd/denghi/quanly','HDController@getHDQuanLyDeNghi')->name('hd.quanly.denghi');
        Route::get('hd/denghi/chondenghi/{id}','HDController@chonDeNghi');
        Route::get('hd/danhsach','HDController@getDanhSach');
        Route::post('hd/denghi/guidenghi','HDController@guiDeNghi');
        Route::post('hd/denghi/xoa','HDController@xoaDeNghi');
        Route::post('hd/denghi/yeucausua','HDController@yeuCauSua');
        Route::post('hd/denghi/yeucauhuy','HDController@yeuCauHuy');
        Route::post('hd/denghi/gangiavon','HDController@ganGiaVon');
        Route::get('getedit/pkcost/{id}','HDController@getEditPkCost');
        Route::get('getedit/pkfree/{id}','HDController@getEditPkFree');
        Route::post('postedit/pkcost/','HDController@postEditPKCost');
        Route::post('postedit/pkfree/','HDController@postEditPKFree');

        //----- qu???n l?? ????? ngh???
        Route::get('kho/tonkho/get','KhoController@getPageTonKho')->name('sale.kho');
        Route::get('kho/tonkho/data','KhoController@getTonKho');

        //----- ph?? duy???t ????? ngh???
        Route::get('hd/denghi/pheduyet','HDController@getHDPheDuyetDeNghi')->name('hd.quanly.pheduyet');
        Route::post('hd/denghi/pheduyet/ok','HDController@duyetDeNghi');
        Route::post('hd/denghi/pheduyet/ganxe/ok','HDController@ganXeHDCho');
        Route::post('hd/denghi/yeucausua/ok','HDController@duyetYeuCauSua');
        Route::get('hd/denghi/checktonkho/{id}','HDController@checkTonKho');
        Route::get('hd/denghi/checktonkho/ok/{id}','HDController@checkTonKhoOk');
        Route::post('hd/denghi/huydenghi/ok','HDController@huyDeNghi');

        //----- ph?? duy???t h???p ?????ng
        Route::get('hd/denghi/pheduyethopdong','HDController@getHDPheDuyetHopDong')->name('hd.quanly.pheduyet.hopdong');
        Route::post('hd/denghi/pheduyetlead/ok','HDController@duyetDeNghiLead');
        Route::post('hd/denghi/pheduyetleadhuy/ok','HDController@duyetDeNghiLeadHuy');
        Route::post('hd/denghi/yeucausualead','HDController@duyetYeuCauSuaLead');

        // --- In ???n h???p ?????ng
        Route::get('banle/canhan/tienmat/down/{id}','HDController@cntm');
        Route::get('banle/canhan/nganhang/down/{id}','HDController@cnnh');
        Route::get('banle/congty/tienmat/down/{id}','HDController@cttm');
        Route::get('banle/congty/nganhang/down/{id}','HDController@ctnh');

        // --- In ???n ph??? l???c
        Route::get('banle/phuluc/canhan/down/{id}','HDController@inPhuLucCaNhan');
        Route::get('banle/phuluc/congty/down/{id}','HDController@inPhuLucCongTy');

        // --- In ???n ????? ngh???
        Route::get('banle/denghi/canhan/down/{id}','HDController@inDeNghiCaNhan');
        Route::get('banle/denghi/congty/down/{id}','HDController@inDeNghiCongTy');

        // --- B???ng gi?? xe
        Route::get('listbanggia','HanhChinhController@showBangGiaXe')->name('sale.banggiaxe');
        Route::get('xemlistbanggia','HanhChinhController@showXemBangGiaXe')->name('sale.xembanggiaxe');
        Route::get('ajax/list/banggia','HanhChinhController@getBangGia');
        Route::post('ajax/post/banggia','HanhChinhController@postBangGia');
        Route::post('ajax/delete/banggia','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/banggia/{id}','HanhChinhController@getEditBangGia');
        Route::post('ajax/update/banggia','HanhChinhController@updateBangGia');

        // --- Th??ng b??o n???i b???
        Route::get('listthongbao','HanhChinhController@showSaleThongBao')->name('sale.thongbao');
        Route::get('xemlistthongbao','HanhChinhController@showXemSaleThongBao')->name('sale.xemthongbao');
        Route::get('ajax/list/thongbao','HanhChinhController@getSaleThongBao');
        Route::post('ajax/post/thongbao','HanhChinhController@postSaleThongBao');
        Route::post('ajax/delete/thongbao','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/thongbao/{id}','HanhChinhController@getEditSaleThongBao');
        Route::post('ajax/update/thongbao','HanhChinhController@updateSaleThongBao');

        // --- In t??o lao
        Route::get('complete/pdi/{id}','HDController@inPdiXe');
        Route::get('complete/bhbb/{id}','HDController@inBHBB');
        Route::get('complete/phukien/{id}','HDController@inPhuKien');

    });
    Route::group(['prefix' => 'ketoan', 'middleware' => ['f_hd']], function(){
        Route::get('hopdongxe','KetoanController@getKeToan')->name('ketoan');
        Route::get('hopdong/danhsach','KetoanController@getDanhSachHopDong');
        Route::get('hopdong/bienban/{id}','KetoanController@inBienBan');
        Route::get('hopdong/quyettoan/{id}','KetoanController@inQuyetToan');
        Route::get('baocaohopdong','KetoanController@getBaoCaoHopDong')->name('ketoan.baocaohopdong');
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
        Route::post('approve','LaiThuController@approve');
    });

    Route::group(['prefix' => 'capxang'], function(){
        Route::get('list','DeNghiCapXangController@showCapXang')->name('capxang.denghi');
        Route::post('reg','DeNghiCapXangController@postDeNghi')->name('capxang.post');
        Route::post('del','DeNghiCapXangController@del');
        // In x??ng
        Route::get('xang/{id}', 'DeNghiCapXangController@inXang')->name('xang.in');

        // duyet
        Route::get('duyet','DeNghiCapXangController@showDuyetCapXang')->name('capxang.duyet')->middleware(['f_capxang']);
        Route::post('allow','DeNghiCapXangController@allowCapXang')->middleware(['f_capxang']);
        Route::post('cancel','DeNghiCapXangController@cancelCapXang')->middleware(['f_capxang']);
        Route::post('leadallow','DeNghiCapXangController@leadAllowCapXang');

    });

    Route::group(['prefix' => 'hanhchinh'], function(){
        Route::get('list','HanhChinhController@showBieuMau')->name('hanhchinh.bieumau.quanly');
        Route::get('ajax/list','HanhChinhController@getBieuMau');
        Route::post('ajax/post','HanhChinhController@postBieuMau');
        Route::post('ajax/delete','HanhChinhController@deleteBieuMau');
        Route::post('ajax/getedit/{id}','HanhChinhController@getEditBieuMau');
        Route::post('ajax/update','HanhChinhController@updateBieuMau');

        // th??ng b??o xem
        Route::get('list/thongbao','HanhChinhController@showXemThongBao')->name('hanhchinh.xemthongbao');
        Route::get('ajax/xemthongbao','HanhChinhController@getXemThongBao');

        // bi???u m???u xem
        Route::get('list/bieumau','HanhChinhController@showXemBieuMau')->name('hanhchinh.xembieumau');
        Route::get('ajax/xembieumau','HanhChinhController@getXemBieuMau');

        // H??? s?? nh??n vi??n
        Route::get('hoso','HanhChinhController@getHoSo')->name('hanhchinh.hoso');
        Route::post('gethoso','HanhChinhController@getHoSoWithName');

        // Ch???ng t??? m???c
        Route::get('list/chungtumoc','ChungTuController@showChungTuMoc')->name('chungtu.panel');
        Route::get('ajax/loadchungtu','ChungTuController@loadChungTu');
        Route::get('ajax/xemchungtu','ChungTuController@xemChungTu');
        Route::post('chungtuajax/post','ChungTuController@postBieuMau');
        Route::post('chungtuajax/delete','ChungTuController@deleteChungTu');
        Route::post('chungtuajax/getedit/{id}','ChungTuController@getEditChungTu');
        Route::post('chungtuajax/update','ChungTuController@updateChungTu');

         // Xem Ch???ng t??? m???c
         Route::get('list/xemchungtumoc','ChungTuController@showXemChungTu')->name('xemchungtu.panel');

         // n???i quy - quy ch??? xem
        Route::get('list/noiquy','HanhChinhController@showXemNoiQuy')->name('noiquy.xem');
        Route::get('ajax/xemnoiquy','HanhChinhController@getXemNoiQuy');
    });

    Route::group(['prefix' => 'vpp', 'middleware' => ['f_nhansu']], function(){
        // Qu???n l?? nh??m h??ng
        Route::get('quanlynhomhang','VPPController@nhomHangPanel')->name('vpp.nhomhang.panel');
        Route::get('quanlynhomhang/loadnhomhang','VPPController@loadNhomHang');
        Route::post('quanlynhomhang/post','VPPController@postNhomHang');
        Route::post('quanlynhomhang/delete','VPPController@deleteNhomHang');
        Route::get('quanlynhomhang/edit/{id}','VPPController@loadEditNhomHang');
        Route::post('quanlynhomhang/update','VPPController@updateNhomHang');
        // Qu???n l?? danh m???c
        Route::get('quanlydanhmuc','VPPController@danhMucPanel')->name('vpp.danhmuc.panel');
        Route::get('quanlydanhmuc/loaddanhmuc','VPPController@loadDanhMuc');
        Route::post('quanlydanhmuc/post','VPPController@postDanhMuc');
        Route::post('quanlydanhmuc/delete','VPPController@deleteDanhMuc');
        Route::get('quanlydanhmuc/edit/{id}','VPPController@loadEditDanhMuc');
        Route::post('quanlydanhmuc/update','VPPController@updateDanhMuc');
        // Qu???n l?? nh???p kho
        Route::get('quanlynhapkho','VPPController@nhapKhoPanel')->name('vpp.nhapkho.panel');
        // Route::post('quanlynhapkho/post','VPPController@nhapKhoPost');
        Route::post('quanlynhapkho/update','VPPController@nhapKhoUpdate');
        Route::post('quanlynhapkho/delete','VPPController@nhapKhoDelete');
        Route::get('quanlynhapkho/loaddanhmuc','VPPController@nhapKhoLoadDanhMuc');
        Route::get('quanlynhapkho/loadphieunhap','VPPController@nhapKhoLoadPhieuNhap');
        Route::get('quanlynhapkho/loadphieunhapchitiet','VPPController@nhapKhoLoadPhieuNhapChiTiet');
        Route::post('quanlynhapkho/taophieu','VPPController@nhapKhoTaoPhieuNhap');        
        // Qu???n l?? xu???t kho
        Route::get('quanlyxuatkho','VPPController@quanLyXuatKhoPanel')->name('vpp.quanlyxuatkho.panel');
        Route::get('quanlyxuatkho/loadphieu','VPPController@xuatKhoLoadPhieu');
        Route::get('quanlyxuatkho/loadphieuchitiet','VPPController@xuatKhoLoadChiTiet');
        Route::get('quanlyxuatkho/refresh','VPPController@refreshPage')->name('vpp.refresh');
        Route::post('quanlyxuatkho/duyetphieu','VPPController@duyetPhieu');
        Route::post('quanlyxuatkho/huyduyetphieu','VPPController@huyDuyetPhieu');
        // B??o c??o kho
        Route::get('baocaokho','VPPController@baoCaoKhoPanel')->name('vpp.baocaokho.panel');
        Route::post('baocaokho/tonkhothucte','VPPController@tonKhoThucTe');
        Route::post('baocaokho/biendongkho','VPPController@bienDongKho');
        Route::post('baocaokho/yeucaudaduyet','VPPController@yeuCauDaDuyet');
        Route::post('baocaokho/yeucaudoiduyet','VPPController@yeuCauDoiDuyet');
        Route::post('baocaokho/nhapkhochitiet','VPPController@nhapKhoChiTiet');
    });

    Route::group(['prefix' => 'requestvpp'], function(){
        // ????? ngh??? c??ng c???
        Route::get('denghicongcu','VPPController@deNghiCongCuPanel')->name('vpp.denghicongcu.panel');
        Route::post('denghicongcu/update','VPPController@deNghiUpdate');
        Route::post('denghicongcu/delete','VPPController@yeuCauDelete');
        Route::get('denghicongcu/loaddanhmuc','VPPController@nhapKhoLoadDanhMuc');
        Route::get('denghicongcu/loadphieu','VPPController@deNghiLoadPhieu');
        Route::get('denghicongcu/loadphieuchitiet','VPPController@deNghiLoadChiTiet');
        Route::post('denghicongcu/taophieu','VPPController@yeuCauTaoPhieu');
        Route::post('denghicongcu/nhanhang','VPPController@nhanHang');
    });

    Route::group(['prefix' => 'nhansu'], function(){
        Route::get('quanlychamcong','NhanSuController@quanLyChamCong')->name('nhansu.panel')->middleware(['f_nhansu']);
        Route::get('quanly/ajax/getnhanvien','NhanSuController@quanLyChamCongGetNhanVien');
        Route::post('quanly/ajax/postnhanvien','NhanSuController@quanLyChamCongPostNhanVien');
      
        // LoaiPhep -> Qu???n l?? lo???i ph??p
        Route::get('quanlyphep','NhanSuController@quanLyPhep')->name('quanlyphep.panel')->middleware(['f_nhansu']);
        Route::get('quanlyphep/ajax/getlist','NhanSuController@quanLyPhepGetList');
        Route::post('quanlyphep/ajax/post','NhanSuController@quanLyPhepPost');
        Route::post('quanlyphep/ajax/delete','NhanSuController@quanLyPhepDelete');

        // Chi ti???t ch???m c??ng
        Route::get('chitiet','NhanSuController@chiTietChamCong')->name('chitiet.panel');
        Route::get('chitiet/ajax/getnhanvien','NhanSuController@chiTietGetNhanVien');
        Route::get('chitiet/ajax/getnhanvienroom','NhanSuController@chiTietGetNhanVienRoom');
        Route::post('chitiet/ajax/themphep','NhanSuController@chiTietThemPhep');

        // xin ph??p
        Route::get('xinphep','NhanSuController@xinPhepGetList')->name('xinphep.panel');
        Route::get('xinphep/ajax/getnhanvien','NhanSuController@xinPhepGetNhanVien');
        Route::post('xinphep/ajax/delete','NhanSuController@xinPhepDelete');
        Route::post('xinphep/ajax/deleteadmin','NhanSuController@xinPhepDeleteAdmin')->middleware(['f_nhansu']);
        Route::get('xinphep/ajax/getphepnam/{id}/nam/{nam}','NhanSuController@getPhepNam');
        Route::get('xinphep/ajax/dongbo','NhanSuController@dongBoPhep');

        // xin t??ng ca
        Route::post('chitiet/ajax/themtangca','NhanSuController@chiTietThemTangCa');

        // ph?? duy???t ph??p
        Route::get('pheduyet','NhanSuController@pheDuyetGetList')->name('pheduyet.panel')->middleware(['f_nhansupheduyet']);
        Route::get('pheduyet/ajax/getlist','NhanSuController@pheDuyetPhepGetList');
        Route::post('pheduyet/ajax/pheduyet','NhanSuController@pheDuyetPhep');

        // ph?? duy???t t??ng ca
        Route::get('pheduyettangca','NhanSuController@getTangCaPanel')->name('tangca.panel')->middleware(['f_nhansupheduyet']);
        Route::get('pheduyettangca/ajax/getlist','NhanSuController@pheDuyetTangCaGetList');
        Route::post('pheduyettangca/ajax/pheduyet','NhanSuController@pheDuyetTangCa');
        Route::post('capnhattangca/ajax/capnhat','NhanSuController@capNhatTangCa');
        Route::post('pheduyettangca/ajax/delete','NhanSuController@tangCaDelete');
        Route::post('pheduyettangca/ajax/deleteadmin','NhanSuController@tangCaDeleteAdmin');

         // t???ng h???p
        Route::get('tonghop','NhanSuController@getTongHop')->name('tonghop.panel')->middleware(['f_nhansu']);
        Route::get('tonghop/ajax/getngay','NhanSuController@tongHopXemNgay');
        Route::get('tonghop/ajax/getthang','NhanSuController@tongHopXemThang');

         // Xem th??ng
        Route::get('xemthang/ngay/{ngay}/thang/{thang}/nam/{nam}','NhanSuController@xemThang')->name('xemthang.panel')->middleware(['f_nhansu']);

        // Import Excel
        Route::get('importexcel','NhanSuController@getImport')->name('import.panel')->middleware(['f_nhansu']);
        Route::post('import','NhanSuController@importExcel')->name('import.post');

         // Ch???t c??ng
        Route::get('chotcong','NhanSuController@getChotCong')->name('chotcong.panel')->middleware(['f_nhansu']);
        Route::post('chotcong/ajax/chot','NhanSuController@chotCong');
        Route::get('chotcong/ajax/get','NhanSuController@chiTietChotCong');
        Route::post('chotcong/ajax/huy','NhanSuController@huyChotCong');
        Route::post('chotcong/ajax/xacnhanall','NhanSuController@xacNhanAll');
        Route::post('chotcong/ajax/huyall','NhanSuController@huyAll');

        // Qu???n l?? ph?? duy???t
        Route::get('quanlypheduyetphep','NhanSuController@getQuanLyPheDuyet')->name('quanlypheduyet.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlypheduyetphep/ajax/getlist','NhanSuController@quanLyPheDuyetGetList');
        Route::post('quanlypheduyetphep/ajax/seen','NhanSuController@quanLyPheDuyetSeen');

        // Qu???n l?? ph?? duy???t t??ng ca
        Route::get('quanlypheduyettangca','NhanSuController@getQuanLyPheDuyetTangCa')->name('quanlypheduyettangca.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlypheduyettangca/ajax/getlist','NhanSuController@quanLyPheDuyetTangCaGetList');
        Route::post('quanlypheduyettangca/ajax/seen','NhanSuController@quanLyPheDuyetTangCaSeen');

        // Qu???n l?? t??ng ca/ng??y l???
        Route::get('quanlytangcale','NhanSuController@getQuanLyTangCaLe')->name('quanlytangcale.panel')->middleware(['f_nhansupheduyet']);
        Route::get('quanlytangca/ajax/get','NhanSuController@getQuanLyTangCa');
        Route::post('quanlytangca/ajax/postnhanvien','NhanSuController@quanLyTangCaPost');
        Route::post('quanlytangca/ajax/xoa','NhanSuController@xoaNhanVienTangCa');
        Route::post('quanlytangca/ajax/themall','NhanSuController@themAllNhanVienTangCa');
        Route::post('quanlytangca/ajax/huyall','NhanSuController@huyAllNhanVienTangCa');

        // B??o c??o ph??p n??m
        Route::get('baocaophepnam','NhanSuController@getBaoCaoPhepNam')->name('baocaophepnam.panel')->middleware(['f_nhansupheduyet']);
        Route::get('loadbaocaophepnam','NhanSuController@loadBaoCaoPhepNam');

        // Qu???n l?? bi??n b???n
        Route::get('quanlybienban','NhanSuController@getPanelBB')->name('bienban.panel')->middleware(['f_nhansupheduyet']);
        Route::get('ajax/loadbienban','NhanSuController@loadBienBan'); 
        Route::post('ajax/postbienban','NhanSuController@postBienBan'); 
        Route::post('ajax/loadnhanvienbbkt','NhanSuController@loadNhanVien')->name('loadnhanvienbbkt'); 
        Route::post('ajax/delete/bienbankhenthuong','NhanSuController@deleteBBKT'); 

        // Qu???n l?? khen th?????ng
        Route::get('quanlykhenthuong','NhanSuController@getPanelKT')->name('khenthuong.panel')->middleware(['f_nhansupheduyet']);
        Route::get('ajax/loadkhenthuong','NhanSuController@loadKhenThuong'); 
        Route::post('ajax/postkhenthuong','NhanSuController@postKhenThuong'); 
        Route::post('ajax/loadnhanvienbbkt','NhanSuController@loadNhanVien')->name('loadnhanvienbbkt'); 
        Route::post('ajax/delete/bienbankhenthuong','NhanSuController@deleteBBKT'); 

        // Qu???n l?? khen th?????ng
        Route::post('xembienban','NhanSuController@xemBienBan');
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
        // --- th??m h???p ?????ng chi ti???t
        Route::post('addcar','ReportController@addCar')->name('add.car');
        Route::get('loadaddcar/{id}','ReportController@loadAddCar')->name('add.car.load');
        Route::post('deletecar','ReportController@deleteCar')->name('add.car.delete');

        //--- th??m c??ng vi???c
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
        //--- C??ng vi???c trong ng??y
        Route::get('list','WorkController@show')->name('worktohard');
        Route::get('getworklist','WorkController@getWorkList');
        Route::post('addwork','WorkController@addWork');
        Route::post('delwork','WorkController@delWork');
        Route::get('getworkedit/{id}','WorkController@getWorkEdit');
        Route::post('editwork','WorkController@editWork');
        Route::post('check','WorkController@checkWork');

        //--- C??ng vi???c trong ng??y
        Route::get('complete','WorkController@complete')->name('complete.list');
        Route::get('complete/list','WorkController@showComplete')->name('complete');

        //--- ??ang th???c hi???n
        Route::get('working','WorkController@working')->name('working.list');
        Route::get('working/list','WorkController@showWorking');
        Route::post('editworking','WorkController@editWorking');

        //--- Giao vi???c
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

        //--- Nh???n vi???c
        Route::get('getwork','WorkController@getWork')->name('work.get');
        Route::get('getworkdetail','WorkController@getWorkDetail');
        Route::post('getnoapprove','WorkController@getNoApprove');
        Route::post('getapprove','WorkController@getApprove');
    });

    Route::group(['prefix' => 'nhatky', 'middleware' => ['f_role']], function(){
        Route::get('list','NhatKyController@getList')->name('nhatky.list');   
        Route::get('ajax/xem','NhatKyController@loadList');      
    });

    Route::group(['prefix' => 'dichvu'], function(){
        // qu???n l?? ph??? ki???n
        Route::post('loadtimkiem','DichVuController@timKiem')->name('timkiembaogia'); 
        Route::post('loadbaogia','DichVuController@loadBaoGia')->name('loadbaogia');     
        Route::post('deletebaogia','DichVuController@deleteBaoGia')->name('deletebaogia');  
        Route::post('thuchienbaogia','DichVuController@thucHienBaoGia')->name('thuchienbaogia');     
        Route::post('huybaogia','DichVuController@huyBaoGia')->name('huybaogia');     
        Route::post('donebaogia','DichVuController@doneBaoGia')->name('donebaogia'); 
        // in ???n
        Route::get('inbaogia/{idbg}','DichVuController@printBaoGia'); 
        Route::get('inyeucaucapvattu/{idbg}','DichVuController@printYeuCauCapVatTu'); 
        Route::get('inlenhsuachua/{idbg}','DichVuController@printLenhSuaChua'); 
        Route::get('inquyettoan/{idbg}','DichVuController@printQuyetToan'); 
        // h???ng m???c
        Route::post('loadhangmuc','DichVuController@taiHangMuc')->name('loadhangmuc');     
        Route::post('loadbhpk','DichVuController@taiBHPK')->name('loadbhpk');     
        Route::post('luubhpk','DichVuController@luuBHPK')->name('luuhangmuc');  
        Route::post('refreshhangmuc','DichVuController@refreshHM')->name('refreshhangmuc'); 
        Route::post('delhangmuc','DichVuController@delHM')->name('delhangmuc'); 
        Route::post('loadtongcong','DichVuController@getTong')->name('loadtongcong');     
  
        // qu???n l?? ph??? ki???n
        Route::get('phukienpanel','DichVuController@phuKienPanel')->name('phukien.panel')->middleware(['f_bhpk']);     
        Route::post('phukien/timhopdong','DichVuController@timHopDong')->name('timhopdong');  
        Route::post('phukien/timkhachhang','DichVuController@timKhachHang')->name('timkhachhang');        
        Route::post('phukien/postbaogia','DichVuController@postBaoGia')->name('postbaogia');        
        Route::post('phukien/editbaogia','DichVuController@editBaoGia')->name('editbaogia');  
        
         // qu???n l?? ph??? ki???n
         Route::get('baohiempanel','DichVuController@baoHiemPanel')->name('baohiem.panel')->middleware(['f_bhpk']);     

        // qu???n l?? kh??ch h??ng
        Route::get('khachhang','DichVuController@khachHangPanel')->name('phukien.khachhang')->middleware(['f_bhpk']);   
        Route::get('get/list','DichVuController@getKhachHang');    
        Route::post('guest/add','DichVuController@addKhachHang');    
        Route::post('guest/delete','DichVuController@delKhachHang');    
        Route::post('guest/edit/show/','DichVuController@getKhachHangEdit'); 
        Route::post('guest/update/','DichVuController@updateKhachHang'); 

        // qu???n l?? h???ng m???c
        Route::get('hangmuc','DichVuController@hangMucPanel')->name('dichvu.hangmuc')->middleware(['f_bhpk']);   
        Route::get('hangmuc/get/list','DichVuController@getHangMuc');    
        Route::post('hangmuc/guest/add','DichVuController@addHangMuc');  
        Route::post('hangmuc/guest/delete','DichVuController@delHangMuc');      
        Route::post('hangmuc/guest/edit/show/','DichVuController@getHangMucEdit'); 
        Route::post('hangmuc/guest/update/','DichVuController@updateHangMuc'); 

        // b??o c??o doanh thu
        Route::get('baocaodoanhthu','DichVuController@baoCaoDoanhThuPanel')->name('dichvu.baocaodoanhthu.panel')->middleware(['f_bhpk']); 
        Route::post('loadbaocaodoanhthu','DichVuController@loadBaoCaoDoanhThu');  
        
        // b??o c??o ti???n ?????
        Route::get('baocaotiendo','DichVuController@baoCaoTienDoPanel')->name('dichvu.baocaotiendo.panel')->middleware(['f_bhpk']); 
        Route::post('loadbaocaotiendo','DichVuController@loadTienDo');  

        // L???y d??? li???u ch???nh s???a
        Route::post('hangmuc/baohiemphukien/getedit/','DichVuController@getEditHangMuc')->name('getedithangmuc');
        // c???p nh???t d??? li???u ch???nh s???a 
        Route::post('hangmuc/baohiemphukien/postedit/','DichVuController@editHangMuc')->name('postedithangmuc'); 
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

        // M??? r???ng h???p
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

        // Tra c???u
        Route::get('tracuuhop','HopController@getTraCuu')->name('cuochop.tracuu.panel');
        Route::get('tracuuhop/getlist','HopController@getListTraCuu');
        Route::post('tracuuhop/loadchitietvande','HopController@loadChiTietVanDe');
        Route::get('tracuuhop/morong/{id}','HopController@hopMoRongVanDe');
        Route::post('tracuuhop/xacnhan','HopController@xacNhan');

        // Tra c???u ADMIN
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
});

// Route::get('mail', function () { 
//     return new App\Mail\GiaoViec(['Nguy???n C?? Ch???n','Tr???n D???n','Kh??ng','Kh??ng','']);
// });