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

Route::get('show/{id}', 'LaiThuController@showQR');

Route::get('/', function () {
    if (Auth::check()) {
        return view('admin.home');
    }
    return view('login');
})->name('trangchu');

Route::get('/out',function(){
    Auth::logout();
    return redirect()->route('trangchu');
})->name('out');

Route::post('/login', 'UserController@login')->name('login');
Route::group(['prefix' => 'management', 'middleware' => 'login'], function(){
   Route::get('user/changepass','UserController@changePass')->name('changepass.list');
   Route::post('user/change','UserController@change')->name('change');
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
    Route::group(['prefix' => 'hoso', 'middleware' => ['f_hoso']], function(){
        Route::get('list','HoSoController@index')->name('hoso.list');
        Route::get('users','HoSoController@getUser')->name('hoso.users');
        Route::post('add', 'HoSoController@store')->name('ajax.hoso.add');
        Route::get('get', 'HoSoController@getHoSo')->name('ajax.hoso.get');
        Route::post('edit', 'HoSoController@editHoSo')->name('ajax.hoso.edit');
        Route::post('update', 'HoSoController@updateHoSo')->name('ajax.hoso.update');
        Route::post('delete', 'HoSoController@deleteHoSo')->name('ajax.hoso.delete');
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
    });
    Route::group(['prefix' => 'hd', 'middleware' => ['f_hd']], function(){
        Route::get('list','HDController@index')->name('hd.list');
        Route::get('get/list','HDController@getList');
        Route::get('get/list/code','HDController@getListCode');
        Route::get('get/list/wait','HDController@getListWait');
        Route::post('add/code','HDController@addCode');
        Route::post('add/pkpay','HDController@addPkPay');
        Route::post('add/pkfree','HDController@addPkFree');
        Route::post('add/pkcost','HDController@addPkCost');
        Route::post('delete','HDController@delete');
        Route::post('deleteWait','HDController@deleteWait');
        Route::post('delete/pkpay/','HDController@deletePkPay');
        Route::post('delete/pkfree/','HDController@deletePkFree');
        Route::post('delete/pkcost/','HDController@deletePkCost');
        // ajax get quickly
        Route::get('get/guest/personal/','HDController@getGuestPersonal');
        Route::get('get/guest/company/','HDController@getGuestCompany');
        Route::get('get/guest/{id}','HDController@getGuest');
        Route::get('get/car/{id}','HDController@getCar');
        Route::get('get/load/hd/','HDController@loadHD');
        Route::get('get/detail/hd/{id}','HDController@detailHD');
        Route::get('get/pkpay/{id}','HDController@getpkpay');
        Route::get('get/pkfree/{id}','HDController@getpkfree');
        Route::get('get/pkcost/{id}','HDController@getpkcost');
        Route::get('get/total/{id}','HDController@getTotal');
        // --- end ajax quickly
        Route::get('exportToWord','HDController@test');
        Route::get('banle/canhan/tienmat/down/{id}','HDController@cntm');
        Route::get('banle/canhan/nganhang/down/{id}','HDController@cnnh');
        Route::get('banle/congty/tienmat/down/{id}','HDController@cttm');
        Route::get('banle/congty/nganhang/down/{id}','HDController@ctnh');
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
    });

    Route::group(['prefix' => 'status'], function(){
        Route::get('list','LaiThuController@getStatus')->name('status.list');
    });

    Route::group(['prefix' => 'reg'], function(){
        Route::get('list','LaiThuController@showReg')->name('laithu.reg');
        Route::post('post','LaiThuController@postReg')->name('reg.post');
        Route::post('pay','LaiThuController@postPay')->name('reg.pay.post');
        Route::post('del','LaiThuController@delReg');
        Route::get('pay/{id}','LaiThuController@pay');
    });

    Route::group(['prefix' => 'duyet', 'middleware' => ['f_duyet']], function(){
        Route::get('list','LaiThuController@showDuyet')->name('laithu.duyet');
        Route::post('allow','LaiThuController@allowLaiThu');
        Route::post('approve','LaiThuController@approve');
    });

    Route::group(['prefix' => 'capxang', 'middleware' => ['f_capxang']], function(){
        Route::get('list','LaiThuController@showCapXang')->name('capxang.duyet');
        Route::post('allow','LaiThuController@allowCapXang');
        Route::post('leadallow','LaiThuController@leadAllowCapXang');
    });

    Route::get('qr/{content}', function ($content) {
//        return QrCode::size(200)->generate('https://google.com');
        return view('qr', ['content' => $content]);
    })->where('content', '.*')->name('qrcode');

    Route::get('xang/{id}', 'LaiThuController@inXang')->name('xang.in');

    Route::group(['prefix' => 'report', 'middleware' => ['f_report']], function(){
        Route::get('list','ReportController@showReport')->name('report');
        Route::post('khoitao','ReportController@khoiTao')->name('report.init');
        Route::get('load','ReportController@loadReport')->name('report.load');
        Route::post('save','ReportController@saveReport')->name('report.save');
        Route::post('savenotsend','ReportController@saveNotSend');
        // --- thêm hợp đồng chi tiết
        Route::post('addcar','ReportController@addCar')->name('add.car');
        Route::get('loadaddcar/{id}','ReportController@loadAddCar')->name('add.car.load');
        Route::post('deletecar','ReportController@deleteCar')->name('add.car.delete');

        //--- thêm công việc
        Route::post('addwork','ReportController@addWork')->name('add.work');
        Route::get('loadwork/{id}','ReportController@loadWork')->name('add.work.load');
        Route::post('deletework','ReportController@deleteWork')->name('add.work.delete');

        //--- nhập kho
        Route::post('addnhap','ReportController@addNhap')->name('add.nhap');
        Route::get('loadnhap/{id}','ReportController@loadNhap')->name('add.nhap.load');
        Route::post('deletenhap','ReportController@deleteNhap')->name('add.nhap.delete');

        //--- xuất kho
        Route::post('addxuat','ReportController@addXuat')->name('add.xuat');
        Route::get('loadxuat/{id}','ReportController@loadXuat')->name('add.xuat.load');
        Route::post('deletexuat','ReportController@deleteXuat')->name('add.xuat.delete');

    });

    Route::group(['prefix' => 'overview', 'middleware' => ['f_report']], function(){
        Route::get('list','ReportController@overviewList')->name('overview.list');
        Route::get('status','ReportController@status')->name('overview.status');
        Route::get('statusmonth/{_month}/room/{_room}','ReportController@statusMonth')->name('overview.status.month');
        //-- pkd
        Route::get('getpkd/{_date}','ReportController@getPKD');
        Route::get('getpkdall/{_month}','ReportController@getPKDAll');

        //-- pdv
        Route::get('getpdv/{_date}','ReportController@getPDV');
        Route::get('getpdvall/{_month}','ReportController@getPDVAll');

        //-- xuong
        Route::get('getxuong/{_date}','ReportController@getXuong');
        Route::get('getxuongall/{_month}','ReportController@getXuongAll');

        //-- cskh
        Route::get('getcskh/{_date}','ReportController@getCSKH');
        Route::get('getcskhall/{_month}','ReportController@getCSKHAll');

        //-- hcns
        Route::get('gethcns/{_date}','ReportController@getHCNS');
        Route::get('gethcnsall/{_month}','ReportController@getHCNSAll');

        //-- mkt
        Route::get('getmkt/{_date}','ReportController@getMkt');
        Route::get('getmktall/{_month}','ReportController@getMktAll');

        //-- it
        Route::get('getit/{_date}','ReportController@getIt');
        Route::get('getitall/{_month}','ReportController@getItAll');

        //-- ptdl
        Route::get('getptdl/{_date}','ReportController@getPtdl');
        Route::get('getptdlall/{_month}','ReportController@getPtdlAll');

         //-- ketoan
        Route::get('getketoan/{_date}','ReportController@getKetoan');
        Route::get('getketoanlall/{_month}','ReportController@getKetoanAll');
    });
});
