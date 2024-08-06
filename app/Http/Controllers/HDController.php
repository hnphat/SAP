<?php

namespace App\Http\Controllers;

use App\BhPkPackage;
use App\PackageV2;
use App\SaleOffV2;
use App\CarSale;
use App\Guest;
use App\BHPK;
use App\RequestHD;
use App\Sale;
use App\KhoV2;
use App\HopDong;
use App\SaleOff;
use App\NhatKy;
use App\TypeCar;
use App\Roles;
use App\RoleUser;
use App\User;
use App\HistoryHopDong;
use Excel;
use App\TypeCarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;

class HDController extends Controller
{
    //
    public function index() {
//        $xeList = CarSale::where('order', 1)->orWhere('exist',1)->orderBy('id_type_car_detail','asc')->get();
        $xeList = TypeCarDetail::select('*')->orderBy('name','asc')->get();
        return view('page.hd', ['xeList' => $xeList]);
    }

    public function getList() {
        $result = CarSale::select('car_sale.*','t.name as ten')
            ->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')
            ->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getListCode() {
        $result = Sale::select('r.tamUng','r.giaXe','sale.created_at','sale.id','g.name as surname','t.name','sale.complete','sale.admin_check','sale.lead_sale_check')
       // $result = Sale::select('r.tamUng','r.giaXe','sale.created_at','sale.id','g.name as surname','t.name','sale.complete','sale.admin_check','sale.lead_sale_check')
            ->join('guest as g','sale.id_guest','=','g.id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->join('request_hd as r','sale.id','r.sale_id')
            ->where('sale.id_user_create', Auth::user()->id)
            ->orderby('sale.id','desc')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getListWait() {
        $result = RequestHD::select('request_hd.user_id','request_hd.guest_id','request_hd.id','request_hd.car_detail_id','request_hd.color','request_hd.tamUng','request_hd.giaXe','request_hd.admin_check','u.name as user_name','t.name as carname','g.name as guestname')
            ->join('guest as g','request_hd.guest_id','=','g.id')
            ->join('type_car_detail as t','request_hd.car_detail_id','=','t.id')
            ->join('users as u','request_hd.user_id','=', 'u.id')
            ->where('request_hd.user_id','=',Auth::user()->id)
            ->orderby('request_hd.id','desc')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list wailt successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getListPK() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function addCode(Request $request) {
        $code = new RequestHD();
        $code->user_id = Auth::user()->id;
        $code->car_detail_id = $request->chonXe;
        $code->color = $request->chonMauXe;
        $code->tamUng = $request->tamUng;
        $code->giaXe = $request->giaBanXe;
        $code->guest_id = $request->idGuest;
        $code->save();
        if($code) {
            return response()->json([
                'message' => 'Tạo đề nghị hợp đồng thành công!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function delete(Request $request) {
        $result = Sale::where('id', $request->id)->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function deleteWait(Request $request) {
        $result = RequestHD::where('id', $request->id)->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete data wait successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getGuestPersonal(){
        if (Auth::user()->hasRole('system'))
            $result = Guest::where('id_type_guest',1)
                ->where([
                    ['lenHopDong','=', true],
                ])
                ->get();
        elseif (Auth::user()->hasRole('adminsale')) {
            $r = Roles::where('name','adminsale')->first();
            $r_u = RoleUser::where('role_id',$r->id)->get();
            $arr_temp = [];
            foreach($r_u as $row) {
                $temple = Guest::where('id_type_guest',1)
                ->where([
                    ['id_user_create','=', $row->user_id],
                    ['lenHopDong','=', true],
                ])
                ->get();
                if ($temple) {                        
                    foreach($temple as $row2) {
                        array_push($arr_temp, $row2);
                    }
                }
            }
            $result = $arr_temp;
        }
        else
            $result = Guest::where('id_type_guest',1)
            ->where([
                ['id_user_create','=', Auth::user()->id],
                ['lenHopDong','=', true],
            ])
            ->get();
        if($result) {
                echo "<option value='0'>Chọn</option>";
            foreach($result as $row){
                echo "<option value='".$row->id."'>".$row->name."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function getGuestCompany(){
        if (Auth::user()->hasRole('system'))
            $result = Guest::where('id_type_guest',2)
                ->where([
                    ['lenHopDong','=', true],
                ])
                ->get();
        elseif (Auth::user()->hasRole('adminsale')) {
            $r = Roles::where('name','adminsale')->first();
            $r_u = RoleUser::where('role_id',$r->id)->get();
            $arr_temp = [];
            foreach($r_u as $row) {
                $temple = Guest::where('id_type_guest',2)
                ->where([
                    ['id_user_create','=', $row->user_id],
                    ['lenHopDong','=', true],
                ])
                ->get();
                if ($temple) {                        
                    foreach($temple as $row2) {
                        array_push($arr_temp, $row2);
                    }
                }
            }
            $result = $arr_temp;
        }
        else
            $result = Guest::where('id_type_guest',2)
            ->where([
                ['id_user_create','=', Auth::user()->id],
                ['lenHopDong','=', true],
            ])
            ->get();
        if($result) {
            echo "<option value='0'>Chọn</option>";
            foreach($result as $row){
                echo "<option value='".$row->id."'>".$row->name."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function getGuest($id){
        $result = Guest::where('id',$id)->first();
        if($result) {
            return response()->json([
                'message' => 'Get Guest Success!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getCar($id){
        $status = "";
        $result = CarSale::where('id',$id)->first();
        if($result) {
            if ($result->sale()->exists()) {
                $status = "<span class='badge badge-secondary'>Xe này đã được lên hợp đồng</span>";
            } elseif ($result->order == 1) {
                $status = "<span class='badge badge-info'>Đang đặt hàng</span>";
            } elseif ($result->exist == 1) {
                $status = "<span class='badge badge-success'>Đang có xe</span>";
            }
            return response()->json([
                'message' => 'Get Car Success!',
                'code' => 200,
                'name_car' => $result->typeCarDetail->name,
                'cost' => number_format($result->cost),
                'status' => $status,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function loadHD() {
        $result = Sale::select('sale.id')->join('guest as g','sale.id_guest','=','g.id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->where('sale.id_user_create', Auth::user()->id)
            ->orderby('sale.id','desc')
            ->get();
        if($result) {
            if($result) {
                echo "<option value='0'>Chọn</option>";
                foreach($result as $row){
                    if ($row->cancelHd && $row->cancelHd->cancel == 1)
                        echo "<option disabled value='".$row->id."'>HAGI-0".$row->id."/HDMB-PA (Đã hủy)</option>";
                    else
                        echo "<option value='".$row->id."'>HAGI-0".$row->id."/HDMB-PA</option>";
                }
            } else {
                echo "<option value='0'>Không tìm thấy</option>";
            }
        }
    }

    public function detailHD($id) {
        $result = Sale::select('r.tamUng','r.giaXe','sale.id as idsale','sale.admin_check','sale.lead_sale_check','sale.complete','g.*','g.name as surname', 'c.*','t.name as name_car')
            ->join('guest as g','sale.id_guest','=','g.id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->join('request_hd as r','r.sale_id','=','sale.id')
            ->where('sale.id', $id)
            ->orderby('sale.id','desc')
            ->first();
        if($result) {
            return response()->json([
                'message' => 'Get Detail HD Success!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function test() {
//        $phpWord = new PhpWord();
//
//        $section = $phpWord->addSection();
//
//        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
//tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
//quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
//consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
//cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
//proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
//
//        $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");
//        $section->addText($description);
//
//        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
//        try {
//            $objWriter->save(storage_path('helloWorld.docx'));
//        } catch (Exception $e) {
//
//        }
//
//        return response()->download(storage_path('helloWorld.docx'));

        $templateProcessor = new TemplateProcessor('template/CN_HD_TM.docx');
        // Cá nhân
        $templateProcessor->setValues([
            'soHopDong' => '878782321',
            'ngay' => '08',
            'thang' => '08',
            'nam' => '2021',
            'sale' => 'Trần Bảo Toàn',
            'salePhone' => '0989 998 998',
            'guest' => 'Nguyễn Hồng Phương',
            'diaChi' => 'Long Xuyên, An Giang',
            'dienThoai' => '0918 988 998',
            'cmnd' => '351 878 877',
            'ngayCap' => '01/02/2010',
            'noiCap' => 'CA. An Giang',
            'ngaySinh' => '16/05/1984',
            'tenDaiDien' => 'Nguyễn Hồng Phương',
//            'noiDung' => 'Xe Accent 2021 Full Vàng Cát </w:t><w:p/><w:t> GHKS08767321 </w:t><w:p/><w:t> GK0878321. ',
            'noiDung' => 'Xe Accent 2021 Full Vàng Cát <w:br/>GHKS08767321 <w:br/>GK0878321. ',
            'donGia' => '326.000.000',
//            'truocBa' => '26.000.000',
//            'ttruocBa' => '26.000.000',
//            'duongBo' => '1.550.000',
//            'tduongBo' => '1.550.000',
//            'tnns' => '2.500.000',
//            'ttnns' => '2.500.000',
//            'bhtx' => '1.500.000',
//            'tbhtx' => '1.500.000',
//            'bienSo' => '350.000',
//            'tbienso' => '350.000',
//            'dangKiem' => '2.500.000',
//            'tdangKiem' => '2.500.000',
            'thanhTien' => '326.000.000',
            'phuKien' => '4.500.000',
            'giaPhuKien' => '4.500.00',
            'tongCong' => '326.000.000',
            'phukien' => 'Túi chống sốc, ba lô, thảm lót chân, trải sàn, bình chữa cháy',
            'tangThem' => 'combo bảo dưỡng 3000km',
            'tamUng' => '30.000.000',
            'tamUngBangChu' => 'Ba mươi triệu đồng',
        ]);
        // Công ty
//        $templateProcessor->setValues([
//            'soHopDong' => '878782321',
//            'ngay' => '08',
//            'thang' => '08',
//            'nam' => '2021',
//            'sale' => 'Trần Bảo Toàn',
//            'salePhone' => '0989 998 998',
//            'guest' => 'Nguyễn Hồng Phương',
//            'diaChi' => 'Long Xuyên, An Giang',
//            'dienThoai' => '0918 988 998',
//            'mst' => '1602037961',
//            'chucVu' => 'Nhân viên',
//            'tenDaiDien' => 'Nguyễn Hồng Phương',
//            'noiDung' => 'Xe Accent 2021 Full Vàng Cát, GHKS08767321, GK0878321. ',
//            'donGia' => '326.000.000',
//            'truocBa' => '26.000.000',
//            'ttruocBa' => '26.000.000',
//            'duongBo' => '1.550.000',
//            'tduongBo' => '1.550.000',
//            'tnns' => '2.500.000',
//            'ttnns' => '2.500.000',
//            'bhtx' => '1.500.000',
//            'tbhtx' => '1.500.000',
//            'bienSo' => '350.000',
//            'tbienso' => '350.000',
//            'dangKiem' => '2.500.000',
//            'tdangKiem' => '2.500.000',
//            'thanhTien' => '326.000.000',
//            'tongCong' => '367.000.000',
//            'phukien' => 'Túi chống sốc, ba lô, thảm lót chân, trải sàn, bình chữa cháy',
//            'tangThem' => 'combo bảo dưỡng 3000km',
//            'tamUng' => '30.000.000',
//            'tamUngBangChu' => 'Ba mươi triệu đồng',
//        ]);
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);

    }

    public function cntm($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/CN_HD_TM_NO_PK.docx');
        $hasPK = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
        ->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','pay']
        ])->exists();

        // $hasPK = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
        //     ['sale_off.id_sale','=', $id],
        //     ['package.type','=','pay']
        // ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CN_HD_TM.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $sum = 0;
            $sumpk = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                if ($row->type == 'pay') {
                    $sumpk += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                    $sum += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDTM ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'phuKien' => number_format($sumpk),
                'giaPhuKien' => number_format($sumpk),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        } else {
            // Không phụ kiện
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $sum = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                // $sum += $row->cost;
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDTM ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        }
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In hợp đồng cá nhân tiền mặt";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
    public function cnnh($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/CN_HD_NH_NO_PK.docx');
        $hasPK = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as s','saleoffv2.id_hd','=','s.id')
        ->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CN_HD_NH.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $sum = 0;
            $sumpk = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                if ($row->type == 'pay') {
                    $sumpk += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                    $sum += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDNH ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'phuKien' => number_format($sumpk),
                'giaPhuKien' => number_format($sumpk),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        } else {
            // Không phụ kiện
            $sale = HopDong::find($id);
            $sum = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                // $sum += $row->cost;
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDNH ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        }
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In hợp đồng cá nhân ngân hàng";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
    public function cttm($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/CT_HD_TM_NO_PK.docx');
        $hasPK = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as s','saleoffv2.id_hd','=','s.id')
        ->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CT_HD_TM.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $sum = 0;
            $sumpk = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                if ($row->type == 'pay') {
                    $sumpk += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                    $sum += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDTM ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'phuKien' => number_format($sumpk),
                'giaPhuKien' => number_format($sumpk),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        } else {
            // Không phụ kiện
            $sale = HopDong::find($id);
            $sum = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                // $sum += $row->cost;
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDTM ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        }
        $pathToSave = 'template/CT_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In hợp đồng công ty tiền mặt";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
    public function ctnh($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/CT_HD_NH_NO_PK.docx');
        $hasPK = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as s','saleoffv2.id_hd','=','s.id')
        ->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CT_HD_NH.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $sum = 0;
            $sumpk = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                if ($row->type == 'pay') {
                    $sumpk += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                    $sum += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDNH ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'phuKien' => number_format($sumpk),
                'giaPhuKien' => number_format($sumpk),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        } else {
            // Không phụ kiện
            $sale = HopDong::find($id);
            $sum = 0;
            $tongChiPhi = 0;
            $pkfree = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free') continue;
                if ($row->type == 'cost') {
                    $pkcost .=  $row->name . ', ';
                    $tongChiPhi += $row->cost;
                }
                // $sum += $row->cost;
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $kho = $sale->carSale;
            $giaXe = $sale->giaXe;
            $sum += $sale->giaXe;
            $outhd = 'HDNH ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => '- Xe ô tô ' . $car->seat . ' chỗ ngồi hiệu HYUNDAI<w:br/>' .
                    '- ' . $car_detail->name . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất và AVN<w:br/>',
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($sale->giaXe),
                'tamUng' => number_format($sale->tienCoc),
                'tamUngBangChu' => \HelpFunction::convert($sale->tienCoc),
                'chiPhi' => number_format($tongChiPhi),
                'giaChiPhi' => number_format($tongChiPhi),
                'tongCong' => number_format($sum),
                'bangChuTongCong' => \HelpFunction::convert($sum),
                'quaTang' => $pkfree,
                'cacLoaiPhi' => $pkcost,
            ]);
        }
        $pathToSave = 'template/CT_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In hợp đồng công ty ngân hàng";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inPhuLucCaNhan($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/PHULUC.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $tongChiPhi = 0;
            $i = 1;
            $stt = "";
            $tang = "";
            $chiPhiChiTiet = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'cost') {
                    $stt .= $i . '<w:br/>';
                    $pkcost .=  $row->name . '<w:br/>';
                    $chiPhiChiTiet .=  number_format($row->cost) . '<w:br/>';
                    $tongChiPhi += $row->cost;
                    $i++;
                    if ($row->cost_tang == true) {
                        $tang .= 'Tặng <w:br/>';
                        $tongChiPhi -= $row->cost;
                    } else {
                        $tang .= '<w:br/>';
                    }
                }               
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' CKD';
            $outhd = 'PHỤ LỤC ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân            
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'phuLucLoaiXe' => $tenXe,
                'stt' => $stt,
                'tang' => $tang,
                'cacLoaiPhi' => $pkcost,
                'thanhTienPhi' => $chiPhiChiTiet,
                'tongPhi' => number_format($tongChiPhi),
                'bangChuTongPhi' => \HelpFunction::convert($tongChiPhi),
            ]);

        $pathToSave = 'template/PHULUCDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In phụ lục hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In phụ lục hợp đồng cá nhân";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inPhuLucCongTy($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/PHULUCCONGTY.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $tongChiPhi = 0;
            $i = 1;
            $stt = "";
            $tang = "";
            $chiPhiChiTiet = "";
            $pkcost = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'cost') {
                    $stt .= $i . '<w:br/>';
                    $pkcost .=  $row->name . '<w:br/>';
                    $chiPhiChiTiet .=  number_format($row->cost) . '<w:br/>';
                    $tongChiPhi += $row->cost;
                    $i++;
                    if ($row->cost_tang == true) {
                        $tang .= 'Tặng <w:br/>';
                        $tongChiPhi -= $row->cost;
                    } else {
                        $tang .= '<w:br/>';
                    }
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' CKD';
            $outhd = 'PHỤ LỤC ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'phuLucLoaiXe' => $tenXe,
                'stt' => $stt,
                'tang' => $tang,
                'cacLoaiPhi' => $pkcost,
                'thanhTienPhi' => $chiPhiChiTiet,
                'tongPhi' => number_format($tongChiPhi),
                'bangChuTongPhi' => \HelpFunction::convert($tongChiPhi),
            ]);

        $pathToSave = 'template/PHULUCCONGTYDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In phụ lục hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In phụ lục hợp đồng công ty";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inDeNghiCaNhan($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/DENGHI.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            // $nguonKH = $sale->nguonKH;
            $nguonKH = $sale->guest->nguon;
            $isTienMat = ($sale->isTienMat) ? "Tiền mặt" : "Ngân hàng";
            $tongChiPhi = 0;
            $i = 2;
            $j = 1;
            $k = 1;
            $dem = 0;
            $stt = "";
            $tang = "";
            $sttPK = "";
            $sttPKB = "";
            $dspk = "";
            $dsqt = "";
            $other = "";
            $tongPhuKien = 0;
            $tongPhuKienFree = 0;
            $chiPhiChiTiet = "";
            $chiPhiChiTietPK = "";
            $chiPhiChiTietPKB = "";
            $pkcost = "";
            $pkfree = "";
            $pkpay = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'cost') {
                    $stt .= $i . '<w:br/>';
                    $other .= 'Chi:          Ngày:<w:br/>';
                    $pkcost .=  $row->name . '<w:br/>';
                    $chiPhiChiTiet .=  number_format($row->cost) . '<w:br/>';
                    $tongChiPhi += $row->cost;
                    $i++;
                    if ($row->cost_tang == true) {
                        $tang .= 'Tặng <w:br/>';
                        $tongChiPhi -= $row->cost;
                    } else {
                        $tang .= '<w:br/>';
                    }
                }
                if ($row->type == 'pay') {
                    $tongPhuKien += $row->cost;
                    $dspk .=  $row->name . ";";
                    $dem++;
                    $sttPKB .= $j . '<w:br/>';
                    $pkpay .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPKB .=  number_format($row->cost) . '<w:br/>';
                    $j++;
                }
                if ($row->type == 'free' && ($row->mode == null && $row->free_kem != 1)) {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPK .=  number_format($row->cost) . ' (TT)<w:br/>';                    
                    $tongPhuKienFree += $row->cost;
                    $k++;
                } else if ($row->type == 'free' && ($row->mode != null && $row->mode != "KEMTHEOXE")) {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    switch($row->mode) {
                        case "TANGTHEM":  $chiPhiChiTietPK .=  number_format($row->cost) . ' (TT)<w:br/>'; break;
                        case "CTKM":  $chiPhiChiTietPK .=  number_format($row->cost) . ' (CTKM)<w:br/>'; break;
                    }
                    if ($row->mode == "GIABAN") {
                        $p = BHPK::find($row->mapk);
                        $chiPhiChiTietPK .=  number_format($p->donGia) . ' (TTGB)<w:br/>'; 
                        $tongPhuKienFree += $p->donGia;
                    } else {
                        $tongPhuKienFree += $row->cost;
                    }
                    $k++;
                }

                if ($row->type == 'free' && (($row->mode != null && $row->mode == "KEMTHEOXE") || ($row->mode == null && $row->free_kem == true))) 
                    $dsqt .=  $row->name . ";";
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'ĐNTHHĐ ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Xử lý tỉ suất
            $giaVon = 0;
            if ($sale->isGiaVon) {
                $giaVon = TypeCarDetail::find($sale->id_car_sale)->giaVon;
            } else {
                $giaVon = $sale->giaVon;
            }
            $htvSupport = $sale->htvSupport;
            $phiVanChuyen = $sale->phiVanChuyen;
            $khuyenMai = 0;
            $cpkhac = 0;
            $hh = $sale->hoaHongMoiGioi;        
            $pkm = $sale->package;
            foreach($pkm as $row2) {                
                if ($row2->type == 'free' && $row2->free_kem == false) {
                    $khuyenMai += $row2->cost;
                }
                if ($row2->type == 'cost' && $row2->cost_tang == true) {
                    $khuyenMai += $row2->cost;
                }

                if ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }
            }
            $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $hh + $phiVanChuyen);
            // $loiNhuan = ($giaXe + $htvSupport) - ($khuyenMai + $giaVon + $hh);
            $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
            // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
            // ----------------
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'tenDaiDien' => $sale->guest->name,
                'phuLucLoaiXe' => $tenXe,
                'thanhTien' => number_format($giaXe),
                'stt' => $stt,
                'tang' => $tang,
                'cpKhac' => $other,
                'niemYet' => number_format($sale->giaNiemYet),
                'donGiaPK' => number_format($tongPhuKien),
                'tongPhiPhuKien' => number_format($giaXe + $tongChiPhi + ($magiamgia == 0 ? $tongPhuKien : ($tongPhuKien - ($tongPhuKien*$magiamgia/100)))),
                'cacLoaiPhi' => $pkcost,
                'dsPhuKien' => $dspk,
                'dem' => $dem,
                'mst' => $sale->guest->mst,
                'quaTang' => $dsqt,
                'tamUng' => number_format($sale->tienCoc),
                'moiGioi' => number_format($sale->hoaHongMoiGioi),
                'mhoTen' => $sale->hoTen,
                'mcmnd' => $sale->CMND2,
                'mdienThoai' => $sale->dienThoai,
                'thanhTienPhi' => $chiPhiChiTiet,
                'tongPhi' => number_format($tongChiPhi + $giaXe),
                'bangChuTongPhi' => \HelpFunction::convert($tongChiPhi),
                'mauXe' => $sale->mau,
                'sttPK' => $sttPK,
                'cacLoaiPhiPK' => $pkfree,
                'thanhTienPhiPK' => $chiPhiChiTietPK,
                'sttPKB' => $sttPKB,
                'cacLoaiPhiPKB' => $pkpay,
                'thanhTienPhiPKB' => $chiPhiChiTietPKB,
                'tongPhuKienFree' => number_format($tongPhuKienFree),
                'tongPhuKienBan' => number_format(($magiamgia == 0 ? $tongPhuKien : ($tongPhuKien - ($tongPhuKien*$magiamgia/100)))) . " " . ($magiamgia == 0 ? "" : " (-".$magiamgia."%)"),
                'tisuat' => round($tiSuat,2) . " %",
                'htvSupport' => number_format($htvSupport),
                'nguonKH' => $nguonKH,
                'isTienMat' => $isTienMat,
                'phiVanChuyen' => number_format($phiVanChuyen),
            ]);

        $pathToSave = 'template/DENGHIDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In đề nghị thực hiện hợp đồng " . $logSoHd;
        $nhatKy->save();


        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In đề nghị thực hiện hợp đồng cá nhân";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inDeNghiCongTy($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/DENGHICONGTY.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            // $nguonKH = $sale->nguonKH;
            $nguonKH = $sale->guest->nguon;
            $isTienMat = ($sale->isTienMat) ? "Tiền mặt" : "Ngân hàng";
            $tongChiPhi = 0;
            $i = 2;
            $j = 1;
            $k = 1;
            $dem = 0;
            $tang = "";
            $stt = "";
            $sttPK = "";
            $sttPKB = "";
            $dspk = "";
            $dsqt = "";
            $other = "";
            $tongPhuKien = 0;
            $tongPhuKienFree = 0;
            $chiPhiChiTiet = "";
            $chiPhiChiTietPK = "";
            $chiPhiChiTietPKB = "";
            $pkcost = "";
            $pkfree = "";
            $pkpay = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'cost') {
                    $stt .= $i . '<w:br/>';
                    $other .= 'Chi:          Ngày:<w:br/>';
                    $pkcost .=  $row->name . '<w:br/>';
                    $chiPhiChiTiet .=  number_format($row->cost) . '<w:br/>';
                    $tongChiPhi += $row->cost;
                    $i++;
                    if ($row->cost_tang == true) {
                        $tang .= 'Tặng <w:br/>';
                        $tongChiPhi -= $row->cost;
                    } else {
                        $tang .= '<w:br/>';
                    }
                }
                if ($row->type == 'pay') {
                    $tongPhuKien += $row->cost;
                    $dspk .=  $row->name . ";";
                    $dem++;
                    $sttPKB .= $j . '<w:br/>';
                    $pkpay .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPKB .=  number_format($row->cost) . '<w:br/>';
                    $j++;
                }
                if ($row->type == 'free' && ($row->mode == null && $row->free_kem != 1)) {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPK .=  number_format($row->cost) . ' (TT)<w:br/>';                    
                    $tongPhuKienFree += $row->cost;
                    $k++;
                } else if ($row->type == 'free' && ($row->mode != null && $row->mode != "KEMTHEOXE")) {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    switch($row->mode) {
                        case "TANGTHEM":  $chiPhiChiTietPK .=  number_format($row->cost) . ' (TT)<w:br/>'; break;
                        case "CTKM":  $chiPhiChiTietPK .=  number_format($row->cost) . ' (CTKM)<w:br/>'; break;
                    }
                    if ($row->mode == "GIABAN") {
                        $p = BHPK::find($row->mapk);
                        $chiPhiChiTietPK .=  number_format($p->donGia) . ' (TTGB)<w:br/>'; 
                        $tongPhuKienFree += $p->donGia;
                    } else {
                        $tongPhuKienFree += $row->cost;
                    }
                    $k++;
                }

                if ($row->type == 'free' && (($row->mode != null && $row->mode == "KEMTHEOXE") || ($row->mode == null && $row->free_kem == true))) 
                    $dsqt .=  $row->name . ";";
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'ĐNTHHĐ ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            // Xử lý tỉ suất
            $giaVon = 0;
            if ($sale->isGiaVon) {
                $giaVon = TypeCarDetail::find($sale->id_car_sale)->giaVon;
            } else {
                $giaVon = $sale->giaVon;
            }
            $htvSupport = $sale->htvSupport;
            $phiVanChuyen = $sale->phiVanChuyen;
            $khuyenMai = 0;
            $cpkhac = 0;
            $hh = $sale->hoaHongMoiGioi;        
            $pkm = $sale->package;
            foreach($pkm as $row2) {                
                if ($row2->type == 'free' && $row2->free_kem == false) {
                    $khuyenMai += $row2->cost;
                }
                if ($row2->type == 'cost' && $row2->cost_tang == true) {
                    $khuyenMai += $row2->cost;
                }
                if ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }
            }
            $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $hh + $phiVanChuyen);
            // $loiNhuan = ($giaXe + $htvSupport) - ($khuyenMai + $giaVon + $hh);
            $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
            // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
            // ----------------
            // Cá nhân
            $logSoHd = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $templateProcessor->setValues([
                'soHopDong' => $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA",
                'ngay' => $arrdate[2],
                'thang' => $arrdate[1],
                'nam' => $arrdate[0],
                'cmnd' => $sale->guest->cmnd,
                'ngayCap' => \HelpFunction::setDate($sale->guest->ngayCap),
                'noiCap' => $sale->guest->noiCap,
                'mst' => $sale->guest->mst,
                'ngaySinh' => \HelpFunction::setDate($sale->guest->ngaySinh),
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'phuLucLoaiXe' => $tenXe,
                'thanhTien' => number_format($giaXe),
                'stt' => $stt,
                'tang' => $tang,
                'cpKhac' => $other,
                'niemYet' => number_format($sale->giaNiemYet),
                'donGiaPK' => number_format($tongPhuKien),
                'tongPhiPhuKien' => number_format($giaXe + $tongChiPhi + ($magiamgia == 0 ? $tongPhuKien : ($tongPhuKien - ($tongPhuKien*$magiamgia/100)))),
                'cacLoaiPhi' => $pkcost,
                'dsPhuKien' => $dspk,
                'dem' => $dem,
                'quaTang' => $dsqt,
                'tamUng' => number_format($sale->tienCoc),
                'moiGioi' => number_format($sale->hoaHongMoiGioi),
                'mhoTen' => $sale->hoTen,
                'mcmnd' => $sale->CMND2,
                'mdienThoai' => $sale->dienThoai,
                'thanhTienPhi' => $chiPhiChiTiet,
                'tongPhi' => number_format($tongChiPhi + $giaXe),
                'bangChuTongPhi' => \HelpFunction::convert($tongChiPhi),
                'mauXe' => $sale->mau,
                'sttPK' => $sttPK,
                'cacLoaiPhiPK' => $pkfree,
                'thanhTienPhiPK' => $chiPhiChiTietPK,
                'sttPKB' => $sttPKB,
                'cacLoaiPhiPKB' => $pkpay,
                'thanhTienPhiPKB' => $chiPhiChiTietPKB,
                'tongPhuKienFree' => number_format($tongPhuKienFree),
                'tongPhuKienBan' => number_format(($magiamgia == 0 ? $tongPhuKien : ($tongPhuKien - ($tongPhuKien*$magiamgia/100)))) . " " . ($magiamgia == 0 ? "" : " (-".$magiamgia."%)"),
                'tisuat' => round($tiSuat,2) . " %",
                'htvSupport' => number_format($htvSupport),
                'nguonKH' => $nguonKH,
                'isTienMat' => $isTienMat,
                'phiVanChuyen' => number_format($phiVanChuyen),
            ]);

        $pathToSave = 'template/DENGHICONGTYDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In đề nghị thực hiện hợp đồng " . $logSoHd;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In đề nghị thực hiện hợp đồng công ty";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
    //------------------------- HD V2
    public function getHDDeNghi() {
        $xeList = TypeCarDetail::select('*')->where('isShow',true)->orderBy('name','asc')->get();
        return view('hopdong.denghi', ['xeList' => $xeList]);
    }

    public function getHDQuanLyDeNghi() {
        $xeList = TypeCarDetail::select('*')->orderBy('name','asc')->get();
        $hopdong = HopDong::select('*')->where('id_user_create', Auth::user()->id)
        ->orderby('id','desc')->get();
        $typecar = TypeCar::all();
        return view('hopdong.quanlydenghi', ['hopdong' => $hopdong, 'xeList' => $xeList, 'typecar' => $typecar]);
    }

    public function getHDPheDuyetDeNghi() {
        $hopdong = HopDong::select('*')
        ->orderby('id','desc')->get();
        return view('hopdong.pheduyet', ['hopdong' => $hopdong]);
    }

    public function getHDPheDuyetHopDong() {
        $hopdong = HopDong::select('*')->orderby('id','desc')->get();
        return view('hopdong.duyetlead', ['hopdong' => $hopdong]);
    }

    public function getDanhSach() {
        $hdWait = "";
        $code = "";
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('ketoan'))
            $result = HopDong::select('*')->orderby('id','desc')->get();
        else 
            $result = HopDong::select('*')->where('id_user_create', Auth::user()->id)
            ->orderby('id','desc')->get();
        if($result) {
                echo "<option value='0'>Chọn</option>";
            foreach($result as $row){ 
                if($row->hdWait == true) 
                    $hdWait = "(Hợp đồng chờ)";
                else
                    $hdWait = "";

                if($row->code == 0) 
                    $code = "";
                else
                    $code = "[HĐ: ".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."/HĐMB-PA]";

                if($row->lead_check_cancel	== true) 
                    echo "<option class='bg-danger' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã hủy) ".$hdWait."</option>";
                elseif ($row->requestCheck == false)
                    echo "<option class='bg-secondary' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Chưa gửi) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == false) 
                    echo "<option class='bg-success' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Admin chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                    echo "<option class='bg-warning' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Trưởng phòng chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã duyệt) ".$hdWait."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function getDanhSachForList() {
        $hdWait = "";
        $code = "";
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('ketoan'))
            $result = HopDong::select('*')->orderby('id','desc')->get();
        else 
            $result = HopDong::select('*')->where('id_user_create', Auth::user()->id)
            ->orderby('id','desc')->get();
        if($result) {
                echo "<option value='0'>Chọn</option>";
            foreach($result as $row){ 
                if($row->hdWait == true) 
                    $hdWait = "(Hợp đồng chờ)";
                else
                    $hdWait = "";

                if($row->code == 0) 
                    $code = "";
                else
                    $code = "[".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."]";

                if($row->lead_check_cancel	== true) 
                    echo "<option class='bg-danger' value='".$row->id."'>".$code."[".$row->guest->name."][".$row->user->userDetail->surname."] (Đã hủy) ".$hdWait."</option>";
                elseif ($row->requestCheck == false)
                    echo "<option class='bg-secondary' value='".$row->id."'>".$code."[".$row->guest->name."][".$row->user->userDetail->surname."] (Chưa gửi) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == false) 
                    echo "<option class='bg-success' value='".$row->id."'>".$code."[".$row->guest->name."][".$row->user->userDetail->surname."] (Admin chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                    echo "<option class='bg-warning' value='".$row->id."'>".$code."[".$row->guest->name."][".$row->user->userDetail->surname."] (Trưởng phòng chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                    echo "<option value='".$row->id."'>".$code."[".$row->guest->name."][".$row->user->userDetail->surname."] (Đã duyệt) ".$hdWait."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function taoMau(Request $request) {
        if ($request->chonXe == false || $request->chonMauXe == false)
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi tạo mẫu, chưa chọn xe đề nghị!',
                'code' => 500
            ]);
        $code = new HopDong();
        $code->id_user_create = Auth::user()->id;
        $code->id_car_sale = $request->chonXe;
        $code->isTienMat = $request->hinhThucThanhToan;
        $code->nguonKH = $request->nguonKH;
        $code->mau = $request->chonMauXe;
        $code->tienCoc = $request->tamUng;
        $code->giaXe = $request->giaBanXe;
        $code->giaNiemYet = $request->giaNiemYet;
        $code->id_guest = $request->khachHang;
        $code->hoaHongMoiGioi = $request->hoaHongMoiGioi;
        $code->hoTen = $request->hoTen;
        $code->CMND2 = $request->cmnd;
        $code->dienThoai = $request->dienThoai;
        $code->save();
        $idSale = $code->id;

        // --------------- Add các loại phí
        $pkcost = new PackageV2;
        $pkcost->name = "Phí trước bạ";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Phí đăng ký xe";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Phí đăng kiểm xe";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Phí đường bộ";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Bảo hiểm TNDS";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Bảo hiểm vật chất";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Hỗ trợ đăng ký - đăng kiểm";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }

        $pkcost = new PackageV2;
        $pkcost->name = "Chi phí khác";
        $pkcost->cost = 0;
        $pkcost->id_user_create = Auth::user()->id;
        $pkcost->type = 'cost';
        $pkcost->save();

        if($pkcost) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkcost->id;
            $saleOff->save();
        }


        // --------------- Add 05 phụ kiện theo xe
        $pkpay = new PackageV2;
        $pkpay->name = "Áo trùm xe";
        $pkpay->cost = 0;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new PackageV2;
        $pkpay->name = "Bao tay lái";
        $pkpay->cost = 0;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new PackageV2;
        $pkpay->name = "Tappi sàn";
        $pkpay->cost = 0;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new PackageV2;
        $pkpay->name = "Khăn lau xe";
        $pkpay->cost = 0;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new PackageV2;
        $pkpay->name = "Bình chữa cháy";
        $pkpay->cost = 0;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOffV2;
            $saleOff->id_hd = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        // --------------- End add 05 phụ kiện theo xe
        if($code) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Đề nghị thực hiện hợp đồng";
            $nhatKy->noiDung = "Thực hiện khởi tạo hợp đồng mới, danh mục chi phí đăng ký";
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $idSale;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Thực hiện khởi tạo hợp đồng mới";
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'type' => 'info',
                'message' => 'Đã tạo mẫu!',
                'code' => 200,
                'idInserted' => $idSale
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getpkfree($id) {
        $pkban = SaleOffV2::select('saleoffv2.*','package.free_kem as free_kem','package.name as name','package.cost as cost','package.mode as mode', 'package.mapk as mapk')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as s','saleoffv2.id_hd','=','s.id')
        ->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','free']
        ])->get();
        if($pkban) {
            return response()->json([
                'message' => 'Get PK Free Success!',
                'code' => 200,
                'pkfree' => $pkban
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getpkpay($id) {
        $pkban = SaleOffV2::select('package.*')->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')->join('hop_dong as s','saleoffv2.id_hd','=','s.id')->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','pay']
        ])->get();
        if($pkban) {
            return response()->json([
                'message' => 'Get PK Pay Success!',
                'code' => 200,
                'pkban' => $pkban
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getpkcost($id) {
        $pkcost = SaleOffV2::select('package.*')->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')->join('hop_dong as s','saleoffv2.id_hd','=','s.id')->where([
            ['saleoffv2.id_hd','=', $id],
            ['package.type','=','cost']
        ])->get();
        if($pkcost) {
            return response()->json([
                'message' => 'Get PK Cost Success!',
                'code' => 200,
                'pkcost' => $pkcost
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getEditPkCost($id) {
        $pkcost = PackageV2::select('*')
        ->where([
            ['id','=', $id]
        ])->first();
        if($pkcost) {
            return response()->json([
                'type' => 'info',
                'message' => 'Get PK Cost Success!',
                'code' => 200,
                'pkcost' => $pkcost
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getEditPkFree($id) {
        $pkfree = PackageV2::select('*')
        ->where([
            ['id','=', $id]
        ])->first();
        if($pkfree) {
            return response()->json([
                'type' => 'info',
                'message' => 'Get PK Free Success!',
                'code' => 200,
                'pkfree' => $pkfree
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function addPkCost(Request $request){
        $check = HopDong::find($request->idHD3);
        if ($check->lead_check != 1) {
            $pkpay = new PackageV2;
            $pkpay->name = $request->namePkCost;
            $pkpay->cost = $request->giaPkCost;
            $pkpay->cost_tang = $request->tang;
            $pkpay->id_user_create = Auth::user()->id;
            $pkpay->type = 'cost';
            $pkpay->save();
            if($pkpay) {
                $saleOff = new SaleOffV2;
                $saleOff->id_hd = $request->idHD3;
                $saleOff->id_bh_pk_package = $pkpay->id;
                $saleOff->save();
                if($saleOff) {

                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                    $nhatKy->noiDung = "Bổ sung chi phí cho đề nghị thực hiện hợp đồng ĐN/0".$request->idHD3."(không phải mã hợp đồng chính thức) <br/>Nội dung: " . $request->namePkCost . " <br/>Giá: " . round($request->giaPkCost,2);
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->idHD3;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Bổ sung hạng mục chi phí:  <br/>Nội dung: " . $request->namePkCost . " <br/>Giá: " . round($request->giaPkCost,2) . " <br/>Tặng: " . ($request->tang ? "Có" : "Không");
                    $his->ghiChu = "";
                    $his->save();

                    return response()->json([
                        'message' => 'Tạo các chi phí thành công!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'message' => 'Quản lý đã phê duyệt không thể thêm nội dung!',
            'code' => 200
        ]);
    }

    public function postEditPKCost(Request $request){
        $check = HopDong::find($request->idSaleHD);
        // Dành cho admin sale và nhân viên kinh doanh
        if ($check->admin_check != 1 && $check->requestCheck == false) {
            $temppkpay = PackageV2::find($request->idPkCost);
            $pkpay = PackageV2::find($request->idPkCost);
            $pkpay->name = $request->endpk;
            $pkpay->cost_tang = $request->etang;
            $pkpay->cost = $request->egiapk;
            $pkpay->save();
            if($pkpay) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Điều chỉnh nội dung chi phí đề nghị ĐN/0".$request->idSaleHD."(không phải mã hợp đồng) <br/>Nội dung: " . $request->endpk . " <br/>Từ giá: "
                . round($temppkpay->cost,2) . " thành giá: " . round($request->egiapk,2);
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->idSaleHD;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Điều chỉnh nội dung chi phí <br/>Nội dung: " . $request->endpk . " <br/>Từ giá: "
                . round($temppkpay->cost,2) . " thành giá: " . round($request->egiapk,2) . "<br/>Tặng: " .($request->etang ? "Có" : "Không");
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã chỉnh sửa!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } 
        // Dành cho kế toán
        if (Auth::user()->hasRole('ketoan')) {
            if ($check->id_car_kho != null && $check->hdWait != true) {
                $car = KhoV2::find($check->id_car_kho);
                if ($car->xuatXe == true)
                    return response()->json([
                        'type' => 'warning',
                        'message' => 'Xe đã xuất kho không thể chỉnh sửa nội dung này!',
                        'code' => 200
                    ]);
                else {
                    $temppkpay = PackageV2::find($request->idPkCost);
                    $pkpay = PackageV2::find($request->idPkCost);
                    $pkpay->name = $request->endpk;
                    $pkpay->cost_tang = $request->etang;
                    $pkpay->cost = $request->egiapk;
                    $pkpay->save();
                    if($pkpay) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Kế toán - Quản lý hợp đồng";
                        $nhatKy->noiDung = "Điều chỉnh nội dung chi phí đề nghị ĐN/0".$request->idSaleHD."(không phải mã hợp đồng) <br/>Nội dung: " . $request->endpk . " <br/>Từ giá: "
                        . round($temppkpay->cost,2) . " thành giá: " . round($request->egiapk,2);
                        $nhatKy->save();

                        return response()->json([
                            'type' => 'info',
                            'message' => 'Đã chỉnh sửa!',
                            'code' => 200
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Internal server fail!',
                            'code' => 500
                        ]);
                    }
                }
            } else 
            return response()->json([
                'type' => 'warning',
                'message' => 'Xe chưa được gán vào hợp đồng, không thể chỉnh sửa nội dung này!',
                'code' => 200
            ]);
        }        
        return response()->json([
            'type' => 'warning',
            'message' => 'Bạn đã gửi đề nghị hoặc quản lý đã phê duyệt không thể chỉnh sửa nội dung!',
            'code' => 200
        ]);
    }

    public function postEditPKFree(Request $request){
        $check = HopDong::find($request->idSaleHDFree);
        if ($check->admin_check != 1 && $check->requestCheck == false) {
            $temppkpay = PackageV2::find($request->idPkFree);
            $pkpay = PackageV2::find($request->idPkFree);
            $pkpay->name = $request->ndfree;
            $pkpay->free_kem = ($request->freetang == 1) ? 1 : 0;  
            ($request->emapkfree && $request->emapkfree != "undefined") ? $pkpay->mapk = $request->emapkfree : "";
            ($request->emapkmode && $request->emapkmode != "undefined") ? $pkpay->mode = $request->emapkmode : "";
            if ($request->emapkmode == "GIABAN") {
                $p = BHPK::find($request->emapkfree);
                $pkpay->cost = $p->donGia;
            } elseif ($request->emapkfree) {
                $p = BHPK::find($request->emapkfree);
                $pkpay->cost = $p->giaVon;
            } else {
                $pkpay->cost = $request->giafree;
            }
            $pkpay->save();
            if($pkpay) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Điều chỉnh nội dung khuyến mãi, quà tặng cho đề nghị ĐN/0".$request->idSaleHDFree."(không phải mã hợp đồng) <br/>Nội dung: " . $request->endpk 
                . "Từ giá: "
                . round($temppkpay->cost,2) . " thành giá: " . round($request->giafree,2);
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->idSaleHDFree;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Điều chỉnh nội dung khuyến mãi, quà tặng <br/>Nội dung: " . $temppkpay->name
                . "<br/> từ MODE: " . $temppkpay->mode . " đến MODE: " . $request->emapkmode;
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã chỉnh sửa!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        // Dành cho kế toán
        if (Auth::user()->hasRole('ketoan')) {
            if ($check->id_car_kho != null && $check->hdWait != true) {
                $car = KhoV2::find($check->id_car_kho);
                if ($car->xuatXe == true)
                    return response()->json([
                        'type' => 'warning',
                        'message' => 'Xe đã xuất kho không thể chỉnh sửa nội dung này!',
                        'code' => 200
                    ]);
                else {
                    $temppkpay = PackageV2::find($request->idPkFree);
                    $pkpay = PackageV2::find($request->idPkFree);
                    $pkpay->name = $request->ndfree;
                    $pkpay->free_kem = $request->freetang;
                    $pkpay->cost = $request->giafree;
                    $pkpay->save();
                    if($pkpay) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Kế toán - Quản lý hợp đồng";
                        $nhatKy->noiDung = "Điều chỉnh nội dung khuyến mãi, quà tặng cho đề nghị ĐN/0".$request->idSaleHDFree."(không phải mã hợp đồng) <br/>Nội dung: " . $request->endpk 
                        . "Từ giá: "
                        . round($temppkpay->cost,2) . " thành giá: " . round($request->giafree,2);
                        $nhatKy->save();

                        return response()->json([
                            'type' => 'info',
                            'message' => 'Đã chỉnh sửa!',
                            'code' => 200
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Internal server fail!',
                            'code' => 500
                        ]);
                    }
                }
            } else 
            return response()->json([
                'type' => 'warning',
                'message' => 'Xe chưa được gán vào hợp đồng, không thể chỉnh sửa nội dung này!',
                'code' => 200
            ]);
        }   
        return response()->json([
            'type' => 'warning',
            'message' => 'Bạn đã gửi đề nghị hoặc quản lý đã phê duyệt không thể chỉnh sửa nội dung!',
            'code' => 200
        ]);
    }

    public function addPkPay(Request $request){
        $check = HopDong::find($request->idHD);
        if ($check->lead_check != 1) {
            $pkpay = new PackageV2;
            $pkpay->name = $request->namePkPay;
            $pkpay->cost = $request->giaPkPay;
            $pkpay->mapk = $request->mapkcost;
            $pkpay->id_user_create = Auth::user()->id;
            $pkpay->type = 'pay';
            $pkpay->save();
            if($pkpay) {
                $saleOff = new SaleOffV2;
                $saleOff->id_hd = $request->idHD;
                $saleOff->id_bh_pk_package = $pkpay->id;
                $saleOff->save();
                if($saleOff) {

                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Thêm phụ kiện bán cho đề nghị ĐN/0".$request->idHD."(không phải mã hợp đồng) <br/>Nội dung: " . $request->namePkPay . " <br/>Giá: " . round($request->giaPkPay,2);
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->idHD;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Thêm phụ kiện bán <br/>Nội dung: " . $request->namePkPay . " <br/>Giá: " . round($request->giaPkPay,2);
                    $his->ghiChu = "";
                    $his->save();

                    return response()->json([
                        'message' => 'Tạo phụ kiện bán thành công!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'message' => 'Quản lý đã phê duyệt không thể thêm nội dung!',
            'code' => 200
        ]);
    }

    public function addPkFree(Request $request){
        $check = HopDong::find($request->idHD2);
        if ($check->lead_check != 1) {
            $pkpay = new PackageV2;
            $pkpay->name = $request->namePkFree;
            $pkpay->free_kem = ($request->addfreetang == 1) ? 1 : 0;
            $pkpay->id_user_create = Auth::user()->id;
            $pkpay->mapk = $request->mapkfree;
            $pkpay->mode = $request->mapkmode;
            if ($request->mapkmode == "GIABAN") {
                $p = BHPK::find($request->mapkfree);
                $pkpay->cost = $p->donGia;
            } elseif ($request->mapkfree != null || $request->mapkfree != "undefined") {
                $p = BHPK::find($request->mapkfree);
                $pkpay->cost = $p->giaVon;
            } else {
                $pkpay->cost = $request->giaPkFree;
            }            
            $pkpay->type = 'free';
            $pkpay->save();
            if($pkpay) {
                $saleOff = new SaleOffV2;
                $saleOff->id_hd = $request->idHD2;
                $saleOff->id_bh_pk_package = $pkpay->id;
                $saleOff->save();
                if($saleOff) {
                    
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                    $nhatKy->noiDung = "Thêm phụ kiện tặng cho đề nghị ĐN/0".$request->idHD2."(không phải mã hợp đồng) <br/>Nội dung: " . $request->namePkFree . " <br/>Giá: " . round($request->giaPkFree,2);
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->idHD2;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Thêm phụ kiện tặng <br/>Nội dung: " . $request->namePkFree . " <br/>Giá: " . round($request->giaPkFree,2) . " Mode: " . $request->mapkmode;
                    $his->ghiChu = "";
                    $his->save();

                    return response()->json([
                        'message' => 'Tạo phụ kiện tặng thành công!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'message' => 'Quản lý đã phê duyệt không thể thêm nội dung!',
            'code' => 200
        ]);
    }

    public function deletePkPay(Request $request) {
        $check = HopDong::find($request->sale);
        if ($check->lead_check != 1 && $check->requestCheck != 1) {
            $temp = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->first();
            $result = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->delete();
            $pac = PackageV2::find($request->id);
            $pac->delete();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa phụ kiện bán cho đề nghị ĐN/0".$request->sale."(không phải mã hợp đồng) <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->sale;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Xóa phụ kiện bán <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Delete PK Pay successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }   
    }

  
    public function deletePkFree(Request $request) {
        $check = HopDong::find($request->sale);
        if ($check->lead_check != 1 && $check->requestCheck != 1) {
            $temp = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->first();
            $result = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->delete();
            $pac = PackageV2::find($request->id);
            $pac->delete();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa phụ kiện tặng cho đề nghị ĐN/0".$request->sale."(không phải mã hợp đồng) <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->sale;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Xóa phụ kiện tặng <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Delete PK Free successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
    }

    public function deletePkCost(Request $request) {
        $check = HopDong::find($request->sale);
        if ($check->lead_check != 1 && $check->requestCheck != 1) {
            $temp = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->first();
            $result = SaleOffV2::where([
                ['id_hd','=', $request->sale],
                ['id_bh_pk_package','=', $request->id]
            ])->delete();
            $pac = PackageV2::find($request->id);
            $pac->delete();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa chi phí cho đề nghị ĐN/0".$request->sale."(không phải mã hợp đồng) <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->sale;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Xóa chi phí <br/>Nội dung: " . $temp->name . " <br/>Giá: " . round($temp->cost,2);
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Delete PK Cost successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }      
    }

    public function getTotal($id){
        $sale = HopDong::find($id);
        $magiamgia = $sale->magiamgia;
        $sum = 0;
        $package = $sale->package;
        foreach($package as $row) {
            if ($row->type == 'free') continue;
            else if ($row->type == 'cost' && $row->cost_tang == true) 
                $sum -= $row->cost;
            else if ($row->type == 'pay') 
                $sum += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
            else 
                $sum += $row->cost;
        }
        echo $sum + $sale->giaXe;
    }

    public function chonDeNghi($id){
        $result = HopDong::select('hop_dong.*','c.name as namecar', 'c.id as idcar', 'g.name as guestname', 
        'g.phone', 'g.address', 'g.daiDien', 'g.chucVu', 'g.mst', 'g.cmnd', 'g.ngayCap', 'g.noiCap', 
        'g.ngaySinh')
            ->join('guest as g','hop_dong.id_guest','=','g.id')
            ->join('type_car_detail as c','hop_dong.id_car_sale','=','c.id')
            // ->where('hop_dong.id_user_create', Auth::user()->id)
            ->where('hop_dong.id', $id)
            ->orderby('hop_dong.id','desc')
            ->first();
        $sohd = $result->code.".".$result->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($result->created_at)."/HĐMB-PA";
        $car = KhoV2::find($result->id_car_kho);
        $waitCar = TypeCarDetail::find($result->id_car_sale);
        if($result) {
            return response()->json([
                'message' => 'Get HD Success!',
                'code' => 200,
                'data' => $result,
                'car' => $car,
                'waitcar' => $waitCar,
                'sohd' => $sohd,
                'loaiXe' => $waitCar->id_type_car
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function loadFromTypeCar(Request $request) {
        $idtypecar = $request->idtypecar;
        $pk = BHPK::where('loaiXe',$idtypecar)->orderBy('id','desc')->get();
        if ($pk) {
            return response()->json([
                'type' => 'info',
                'message' => 'Load cơ sỡ dữ liệu phụ kiện theo dòng xe thành công',
                'code' => 200,
                'data' => $pk
            ]);
        } else {            
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function chonHangHoa(Request $request) {
        $mahang = $request->mahang;
        $pk = BHPK::where('ma',strtoupper($mahang))->first();
        if ($pk) {
            return response()->json([
                'type' => 'info',
                'message' => 'Load mã phụ kiện ' .$mahang. ' thành công',
                'code' => 200,
                'data' => $pk
            ]);
        } else {            
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function guiDeNghi(Request $request){
        $result = HopDong::find($request->idHopDong);
        $result->requestCheck = true;
        $result->tienCoc = $request->tamUng;
        $result->giaXe = $request->giaBanXe;
        $result->giaNiemYet = $request->giaNiemYet;
        $result->isTienMat = $request->hinhThucThanhToan;
        $result->nguonKH = $request->nguonKH;
        $result->hoaHongMoiGioi = $request->hoaHongMoiGioi;
        $result->hoTen = $request->hoTen;
        $result->CMND2 = $request->cmnd;
        $result->dienThoai = $request->dienThoai;
        $result->id_car_sale = $request->xeBan;
        $result->mau = $request->mauSac;
        $result->magiamgia = $request->magiamgia;
        $result->save();
        if($result) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
            $nhatKy->noiDung = "Gửi đề nghị thực hiện hợp đồng cho đề nghị ĐN/0".$request->idHopDong."(không phải mã hợp đồng)";
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $request->idHopDong;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Gửi đề nghị thực hiện hợp đồng";
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã gửi đề nghị hợp đồng!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function xuLyLoi(Request $request){
        $result = HopDong::find($request->idHopDong);
        
        if ($result->code != null && $result->code != 0) {
            return response()->json([
                'type' => 'error',
                'message' => 'Hợp đồng hiện không xảy ra lỗi!',
                'code' => 500
            ]);
        }

        $result->requestCheck = false;
        $result->admin_check = false;
        $result->lead_check = false;
        if (Auth::user()->hasRole("system"))
            $result->save();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị - Xử lý lỗi";
            $nhatKy->noiDung = "System xử lý lỗi cho đề nghị ĐN/0".$request->idHopDong."(không phải mã hợp đồng)";
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $request->idHopDong;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "System xử lý lỗi";
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã xử lý lỗi hợp đồng!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function xoaDeNghi(Request $request){
        $result = HopDong::find($request->id);
        if($result->admin_check == false && $result->lead_check == false) {
            $saleoff = SaleOffV2::where('id_hd', $request->id)->get();
            $saleoffdel = SaleOffV2::where('id_hd', $request->id)->delete();
            foreach($saleoff as $row) {
                $package = PackageV2::find($row->id_bh_pk_package)->delete();
            }            
            $result->delete();
            if($result) {             
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã xóa!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'type' => 'info',
            'message' => 'Không thể xóa!',
            'code' => 200
        ]);
    }

    public function duyetDeNghi(Request $request){
        $result = HopDong::find($request->id);      
        if($request->sohd == 0) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Chưa nhập số hợp đồng!',
                'code' => 200,
                'data' => $result
            ]);
        } 

        // $check = HopDong::select('*')->where('code',$request->sohd)->exists(); 
        // if($check) {
        //     return response()->json([
        //         'type' => 'warning',
        //         'message' => 'Số hợp đồng đã tồn tại!',
        //         'code' => 200,
        //         'data' => $result
        //     ]);
        // } 

        if ($request->wait == 1 && $request->daiLy == 1) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Hợp đồng không thể đồng thời là hợp đồng chờ và hợp đồng Đại lý!',
                'code' => 200
            ]);
        }

        if((Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) && $result->requestCheck == true) {
            if ($request->wait == 1) {
                $result->admin_check = true;
                $result->hdWait = true;    
                $result->code = $request->sohd; 
                $result->isGiaVon = $request->isGiaVon; 
                $result->htvSupport = $request->htvSupport;
                $result->phiVanChuyen = $request->phiVanChuyen;
                $result->save();
                if($result) {

                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->noiDung = "Phê duyệt hợp đồng chờ (Mã đề nghị ĐN/0".$request->id.") <br/>Số hợp đồng ".$request->sohd. " đã được gán!";
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->id;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Phê duyệt hợp đồng chờ <br/>Số hợp đồng ".$request->sohd. " đã được gán!";
                    $his->ghiChu = "";
                    $his->save();   
        
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Hợp đồng chờ! Đã duyệt!',
                        'code' => 200,
                        'data' => $result
                    ]);
                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } else if ($request->idXeGan == null) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Bạn chưa gán xe cho đề nghị này!',
                    'code' => 200
                ]);
            } else {
                $result->admin_check = true;
                $result->id_car_kho = $request->idXeGan;
                    $car = KhoV2::find($request->idXeGan);
                    $car->type = "HD";
                    $car->save();
                $result->code = $request->sohd; 
                $result->hdDaiLy = $request->daiLy; 
                $result->isGiaVon = $request->isGiaVon; 
                $result->htvSupport = $request->htvSupport;
                $result->phiVanChuyen = $request->phiVanChuyen;
                $result->save();
                if($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Phê duyệt đề nghị thực hiện hợp đồng (Mã đề nghị ĐN/0".$request->id.")<br/>Số hợp đồng ".$request->sohd. " đã được gán! HĐ Đại lý (1: HĐ Đại lý; 0: HĐ Thường): " . $request->daiLy;
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->id;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Phê duyệt đề nghị thực hiện hợp đồng <br/>Số hợp đồng ".$request->sohd. " đã được gán! HĐ Đại lý (1: HĐ Đại lý; 0: HĐ Thường): " . $request->daiLy;
                    $his->ghiChu = "";
                    $his->save(); 

                    return response()->json([
                        'type' => 'success',
                        'message' => 'Đã duyệt!',
                        'code' => 200,
                        'data' => $result
                    ]);
                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            }
        }
        return response()->json([
            'type' => 'info',
            'message' => 'Lỗi! Sale chưa gửi đề nghị!',
            'code' => 200,
            'data' => null
        ]);
    }

    public function ganGiaVon(Request $request){ 
        $result = HopDong::find($request->id);
        $sohd = $result->code.".".$result->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($result->created_at)."/HĐMB-PA";
        if(Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) {
            if ($result->id_car_kho != null) {
                $car = KhoV2::find($result->id_car_kho);
                if ($car->xuatXe == true)
                    return response()->json([
                        'type' => 'warning',
                        'message' => 'Xe đã xuất kho không thể gán giá vốn!',
                        'code' => 200
                    ]);                   
                else {
                    $result->giaVon = $request->giaVon;
                    $result->save();
                    if ($result) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->noiDung = "Gán giá vốn " . number_format($request->giaVon) 
                        . " cho hợp đồng số: " . $sohd . " (Mã đề nghị ĐN/0".$request->id.")";
                        $nhatKy->save();

                        $his = new HistoryHopDong();
                        $his->idDeNghi = $request->id;
                        $his->id_user = Auth::user()->id;
                        $his->ngay = Date("H:m:s d-m-Y");
                        $his->noiDung = "Gán giá vốn " . number_format($request->giaVon) 
                        . " cho hợp đồng";
                        $his->ghiChu = "";
                        $his->save(); 

                        return response()->json([
                            'type' => 'info',
                            'message' => 'Đã gán giá vốn!',
                            'code' => 200,
                            'data' => $result
                        ]);  
                    }
                    else
                        return response()->json([
                            'type' => 'info',
                            'message' => 'Lỗi giá vốn!',
                            'code' => 500
                        ]);  
                }
            } else {
                $result->giaVon = $request->giaVon;
                $result->save();
                if ($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Gán giá vốn " . number_format($request->giaVon) 
                    . " cho hợp đồng " . $sohd;
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->id;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Gán giá vốn " . number_format($request->giaVon) 
                    . " cho hợp đồng";
                    $his->ghiChu = "";
                    $his->save(); 

                    return response()->json([
                        'type' => 'info',
                        'message' => 'Đã gán giá vốn!',
                        'code' => 200,
                        'data' => $result
                    ]);  
                }
                else
                    return response()->json([
                        'type' => 'info',
                        'message' => 'Lỗi giá vốn!',
                        'code' => 500
                    ]);  
            }
        }
    }

    public function capNhatPhiVanChuyen(Request $request){ 
        $result = HopDong::find($request->id);
        $sohd = $result->code.".".$result->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($result->created_at)."/HĐMB-PA";
        if(Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) {
            if ($result->id_car_kho != null) {
                $car = KhoV2::find($result->id_car_kho);
                if ($car->xuatXe == true)
                    return response()->json([
                        'type' => 'warning',
                        'message' => 'Xe đã xuất kho không thể nhập chi phí vận chuyển!',
                        'code' => 500
                    ]);                   
                else {
                    $result->phiVanChuyen = $request->phiVanChuyen;
                    $result->save();
                    if ($result) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->noiDung = "Cập nhật chi phí vận chuyển " . number_format($request->phiVanChuyen) 
                        . " cho hợp đồng số: " . $sohd . " (Mã đề nghị ĐN/0".$request->id.")";
                        $nhatKy->save();
                        
                        $his = new HistoryHopDong();
                        $his->idDeNghi = $request->id;
                        $his->id_user = Auth::user()->id;
                        $his->ngay = Date("H:m:s d-m-Y");
                        $his->noiDung = "Cập nhật chi phí vận chuyển " . number_format($request->phiVanChuyen) 
                        . " cho hợp đồng ";
                        $his->ghiChu = "";
                        $his->save(); 

                        return response()->json([
                            'type' => 'info',
                            'message' => 'Đã cập nhật chi phí vận chuyển!',
                            'code' => 200,
                            'data' => $result
                        ]);  
                    }
                    else
                        return response()->json([
                            'type' => 'info',
                            'message' => 'Lỗi cập nhật chi phí vận chuyển!',
                            'code' => 500
                        ]);  
                }
            } else {
                $result->phiVanChuyen = $request->phiVanChuyen;
                $result->save();
                if ($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Cập nhật chi phí vận chuyển " . number_format($request->phiVanChuyen) 
                    . " cho hợp đồng " . $sohd;
                    $nhatKy->save();

                    $his = new HistoryHopDong();
                    $his->idDeNghi = $request->id;
                    $his->id_user = Auth::user()->id;
                    $his->ngay = Date("H:m:s d-m-Y");
                    $his->noiDung = "Cập nhật chi phí vận chuyển " . number_format($request->phiVanChuyen) 
                    . " cho hợp đồng ";
                    $his->ghiChu = "";
                    $his->save(); 

                    return response()->json([
                        'type' => 'info',
                        'message' => 'Đã cập nhật chi phí vận chuyển!',
                        'code' => 200,
                        'data' => $result
                    ]);  
                }
                else
                    return response()->json([
                        'type' => 'info',
                        'message' => 'Lỗi cập nhật chi phí vận chuyển!',
                        'code' => 500
                    ]);  
            }
        }
    }

    public function ganXeHDCho(Request $request){
        $result = HopDong::find($request->id); 
        if ($result->lead_check_cancel == true) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Hợp đồng này đã huỷ không thể gán xe!',
                'code' => 200
            ]);
        } 
        if($result->id_car_kho != null) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Hợp đồng này đã được gán xe!',
                'code' => 200,
                'data' => $result
            ]);
        } 

        if($result->hdWait == false) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Chỉ gán xe cho hợp đồng chờ!',
                'code' => 200,
                'data' => $result
            ]);
        } 
        
        if ($request->idXeGan == null) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Bạn chưa gán xe cho hợp đồng!',
                'code' => 200
            ]);
        }

        if((Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) && $result->requestCheck == true && $result->admin_check == true && $result->lead_check == true) {
            $result->id_car_kho = $request->idXeGan;
                $car = KhoV2::find($request->idXeGan);
                $car->type = "HD";
                $car->save();
            $result->hdWait = false;
            $temp = $result->code;
            $mau = $result->mau;
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Gán xe: ".$car->typeCarDetail->name." màu: ".$mau." cho hợp đồng chờ số ".$temp. " (Mã đề nghị ĐN/0".$request->id.") và chuyển sang thành hợp đồng chính thức";
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Gán xe: ".$car->typeCarDetail->name." màu: ".$mau." cho hợp đồng chờ và chuyển sang thành hợp đồng chính thức";
                $his->ghiChu = "";
                $his->save(); 

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã gán xe! Đã chuyển sang hợp đồng chính thức!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
    }

    public function duyetDeNghiLead(Request $request){
        $result = HopDong::find($request->id);
        if(Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('system')) {
            $result->lead_check = true;
            $code = $result->code;
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt hợp đồng";
                $nhatKy->noiDung = "[Quyền trưởng phòng] Đã phê duyệt hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->id.")";
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "[Quyền trưởng phòng] Đã phê duyệt hợp đồng số " . $code;
                $his->ghiChu = "";
                $his->save(); 

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã duyệt hợp đồng!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'type' => 'info',
            'message' => 'Lỗi! Không xác định!',
            'code' => 200,
            'data' => null
        ]);
    }

    public function duyetDeNghiLeadHuy(Request $request){
        $result = HopDong::find($request->id);
        if(Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('system')) {
                if ($result->id_car_kho != null && $result->hdWait != true) {
                    $car = KhoV2::find($result->id_car_kho);
                    if ($car->xuatXe == true)
                        return response()->json([
                            'type' => 'warning',
                            'message' => 'Xe đã xuất kho không thể hủy!',
                            'code' => 200
                        ]);
                    $car->type = "STORE";
                    $temp = $car->typeCarDetail->name;
                    $car->save();
                    if ($car) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt hợp đồng";
                        $nhatKy->noiDung = "Thực hiện chuyển xe ".$temp." từ hợp đồng số " . $result->code . " (Mã đề nghị ĐN/0".$request->id.") vào kho. Chuyển trạng thái xe sang STORE!";
                        $nhatKy->save();

                        $his = new HistoryHopDong();
                        $his->idDeNghi = $request->id;
                        $his->id_user = Auth::user()->id;
                        $his->ngay = Date("H:m:s d-m-Y");
                        $his->noiDung = "Thực hiện chuyển xe ".$temp." từ hợp đồng vào kho. Chuyển trạng thái xe sang STORE!";
                        $his->ghiChu = "";
                        $his->save();

                    }
                }
            $result->lead_check_cancel = true;
            $lydo = $result->lyDoCancel;
            $result->lyDoEdit = "";
            $result->id_car_kho = null;
            $code = $result->code;
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt hợp đồng";
                $nhatKy->noiDung = "Thực hiện hủy hợp đồng số " . $code . " <br/>Lý do hủy: " . $lydo . " (Mã đề nghị ĐN/0".$request->id.")";
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "[Quyền trưởng phòng] Thực hiện phê duyệt đề nghị hủy hợp đồng <br/>Lý do hủy: " . $lydo;
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã duyệt hủy hợp đồng!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
        return response()->json([
            'type' => 'info',
            'message' => 'Lỗi! Không xác định!',
            'code' => 200,
            'data' => null
        ]);
    }

    public function yeuCauSua(Request $request){
        $result = HopDong::find($request->idRequestEdit);
        $result->requestEditHD = true;
        $result->lyDoEdit = $request->lyDoChinhSua;
        $result->lyDoCancel = "";
        $result->requestCancel = false;
        $code = $result->code;
        $result->save();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
            $nhatKy->noiDung = "Gửi yêu cầu chỉnh sửa hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->idRequestEdit.")";
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $request->idRequestEdit;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Gửi yêu cầu cho trưởng phòng xin chỉnh sửa hợp đồng với lý do: " . $request->lyDoChinhSua;
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã gửi yêu cầu sửa!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function yeuCauHuy(Request $request){
        $result = HopDong::find($request->idRequestHuy);
        $result->requestCancel = true;
        $result->lyDoCancel = $request->lyDoHuy;
        $result->lyDoEdit = "";
        $result->requestEditHD = false;
        $code = $result->code;
        $result->save();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->noiDung = "Gửi yêu cầu hủy hợp đồng " . $code . " (Mã đề nghị ĐN/0".$request->idRequestHuy.")";
            $nhatKy->save();

            $his = new HistoryHopDong();
            $his->idDeNghi = $request->idRequestHuy;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Gửi yêu cầu cho trưởng phòng xin huỷ hợp đồng với lý do: " . $request->lyDoHuy;
            $his->ghiChu = "";
            $his->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã gửi yêu cầu hủy hợp đồng!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }


    public function duyetYeuCauSua(Request $request){
        $result = HopDong::find($request->id);
        if(Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) {
            $result->requestCheck = false;
            $result->admin_check = false;
            $code = $result->code;
            $lyDo = $result->lyDoEdit;
            $result->code = 0; 
                if ($result->id_car_kho != null && $result->hdWait != true) {
                    $car = KhoV2::find($result->id_car_kho);
                    $car->type = "STORE";
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->noiDung = "Thực hiện yêu cầu sửa hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->id.") <br/>Lý do chỉnh sửa: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $nhatKy->save();
                        $his = new HistoryHopDong();
                        $his->idDeNghi = $request->id;
                        $his->id_user = Auth::user()->id;
                        $his->ngay = Date("H:m:s d-m-Y");
                        $his->noiDung = "[Quyền Admin] Yêu cầu sale chỉnh sửa đề nghị thực hiện hợp đồng <br/>Lý do chỉnh sửa: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $his->ghiChu = "";
                        $his->save();
                    $car->save();
                }    
            $result->hdWait = false;           
            $result->id_car_kho = null;
            $temp = $result->lyDoEdit;
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "[Quyền Admin] Thực hiện cho phép sale sửa đề nghị thực hiện hợp đồng <br/>Lý do chỉnh sửa: " . $temp;
                $nhatKy->save();
                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "[Quyền Admin] Thực hiện cho phép sale sửa đề nghị thực hiện hợp đồng <br/>Lý do chỉnh sửa: " . $temp;
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã duyệt yêu cầu chỉnh sửa!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            }
        }
    }

    public function duyetYeuCauSuaLead(Request $request){
        $result = HopDong::find($request->id);
        if(Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('system')) {
            $result->requestCheck = false;
            $result->admin_check = false;
            $code = $result->code;
            $lyDo = $result->lyDoEdit;
            $result->code = 0;
                if ($result->id_car_kho != null && $result->hdWait != true) {
                    $car = KhoV2::find($result->id_car_kho);
                    if ($car->xuatXe == true)
                        return response()->json([
                            'type' => 'warning',
                            'message' => 'Xe đã xuất kho không thể thao tác trên hợp đồng này!',
                            'code' => 200
                        ]);
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->noiDung = "Trưởng bộ phận thực hiện phê duyệt yêu cầu sửa hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->id.") <br/>Lý do chỉnh sửa: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $nhatKy->save();
                        $his = new HistoryHopDong();
                        $his->idDeNghi = $request->id;
                        $his->id_user = Auth::user()->id;
                        $his->ngay = Date("H:m:s d-m-Y");
                        $his->noiDung = "Trưởng phòng từ chối phê duyệt hợp đồng từ Admin sale <br/>Lý do từ chối: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $his->ghiChu = "";
                        $his->save();
                    $car->type = "STORE";
                    $car->save();
                }    
            $result->hdWait = false;           
            $result->id_car_kho = null;
            $result->lead_check = false; 
            $result->requestCancel = false;
            $result->requestEditHD = false;
            $result->lyDoCancel = "";
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Trưởng bộ phận thực hiện duyệt yêu cầu sửa hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->id.") <br/>Lý do chỉnh sửa: " . $lyDo;
                $nhatKy->save();
                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Trưởng phòng thực hiện duyệt yêu cầu sửa hợp đồng <br/>Lý do chỉnh sửa: " . $lyDo;
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã duyệt yêu cầu chỉnh sửa hợp đồng!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            }
        }
    }

    public function huyDeNghi(Request $request){
        $result = HopDong::find($request->id);
        if(Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) {
            $result->admin_check = false;
                if ($result->id_car_kho != null && $result->hdWait != true) {
                    $car = KhoV2::find($result->id_car_kho);
                    $car->type = "STORE";
                    $car->save();
                }
            $result->id_car_kho = null;
            $result->hdWait = false;
            $result->hdDaily = false;
            $code = $result->code;
            $result->code = 0; 
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Hủy bỏ phê duyệt đề nghị thực hiện hợp đồng cho hợp đồng số " . $code . " (Mã đề nghị ĐN/0".$request->id.")";
                $nhatKy->save();

                $his = new HistoryHopDong();
                $his->idDeNghi = $request->id;
                $his->id_user = Auth::user()->id;
                $his->ngay = Date("H:m:s d-m-Y");
                $his->noiDung = "Từ chối phê duyệt đề nghị thực hiện hợp đồng";
                $his->ghiChu = "";
                $his->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã bỏ phê duyệt cho đề nghị!',
                    'code' => 200,
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            }
        }
    }

    public function checkTonKho($id){
        $exist = false;
        $result = HopDong::find($id);
        $car = KhoV2::select('*')->where([
            ['id_type_car_detail','=', $result->id_car_sale],
            ['color', '=' , $result->mau]
        ])->get();
       
        if ($car->count() <= 0) {
            $exist = false;
        } else {
            $exist = true;
        }
        if($result) {
            return response()->json([
                'message' => 'Đã kiểm tra tồn kho!',
                'code' => 200,
                'exist' => $exist,
                'data' => $result,
                'car' => $car,
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function checkTonKhoOk($id){
        $result = KhoV2::find($id);
        $info = $result->typeCarDetail;
        if(Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) {
            if($result) {
                return response()->json([
                    'message' => 'Đã gán xe!',
                    'code' => 200,
                    'data' => $result,
                    'info' => $info,
                    'namecar' => $result->typeCarDetail->name
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } 
    }


    public function inPdiXe($id) {
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/PDIXE.docx');
            $sale = HopDong::find($id);
            $kho = KhoV2::find($sale->id_car_kho);
            $year = (isset($kho) ? $kho->year : "Chưa có năm sản xuất");
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $tenXe = $car_detail->name;
            // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'PDI XE ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            $templateProcessor->setValues([
                'soHopDong' => $soHopDong,
                'ngayhd' => $arrdate[2],
                'thanghd' => $arrdate[1],
                'namhd' => $arrdate[0],
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'phone' => $sale->guest->phone,
                'carname' => $tenXe,
                'year' => $year,
                'seat' => $car->seat,
                'color' => $sale->mau,
                'vin' => $kho->vin,
                'frame' => $kho->frame
                // 'vin' => (isset($kho) ? $kho->vin : "Chưa có VIN"),
                // 'frame' => (isset($kho) ? $kho->frame : "Chưa có Số máy")
            ]);

        $pathToSave = 'template/PDIXEDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In PDI số hợp đồng " . $soHopDong;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In PDI";
        $his->ghiChu = "";
        $his->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inBHBB($id) {
        $outhd = "";
        $stt = "";
        $noidung = "";
        $sl = "";
        $tong = "";
        $k = 1;
        $templateProcessor = new TemplateProcessor('template/BH5MON.docx');
            $sale = HopDong::find($id);
            $package = $sale->package;
            foreach($package as $row) {                
                if ($row->type == 'free' && $row->free_kem == true) {
                    $stt .= $k . '<w:br/>';
                    $noidung .=  $row->name . '<w:br/>';                  
                    $sl .= '1<w:br/>';
                    $k++;
                }
            }
            $kho = KhoV2::find($sale->id_car_kho);
            $year = $kho->year;
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name;
            // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'BHBB VÀ 5 MÓN ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            $templateProcessor->setValues([
                'soHopDong' => $soHopDong,
                'ngayhd' => $arrdate[2],
                'thanghd' => $arrdate[1],
                'namhd' => $arrdate[0],
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'phone' => $sale->guest->phone,
                'carname' => $tenXe,
                'cost' => number_format($giaXe),
                'year' => $year,
                'seat' => $car->seat,
                'color' => $sale->mau,
                'vin' => $kho->vin,
                'frame' => $kho->frame,
                'stt' => $stt,
                'noiDung' => $noidung,
                'sl' => $sl,
                'tong' => ($k - 1)
            ]);

        $pathToSave = 'template/BH5MONDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In BHBB và 5 món số hợp đồng " . $soHopDong;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In BHBB và 5 món";
        $his->ghiChu = "";
        $his->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inPhuKien($id) {
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/PHUKIEN.docx');
            $sale = HopDong::find($id);
            $magiamgia = $sale->magiamgia;
            $kho = KhoV2::find($sale->id_car_kho);
            $year = $kho->year;
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name;
            // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'YÊU CẦU LẮP PHỤ KIỆN ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);

            // Exe phụ kiện bán và free
            $package = $sale->package;

            $sttpkban = "";
            $chietKhauBlank = "";
            $pkban = "";
            $pkbansl = "";
            $chietKhau = "";
            $pkbangia = "";
            $pkbangiathanhtien = "";
            $i = 1;

            $sttpkfree = "";
            $pkfree = "";
            $pkfreesl = "";
            $ghiChuFree = "";
            $pkfreegia = "";
            $j = 1;
            $tonggiaban = 0;
            $tongkm = 0;
            foreach($package as $row) {
                if ($row->type == 'pay') {
                    $sttpkban .= $i . '<w:br/>';
                    $chietKhauBlank .= '........ <w:br/>';          
                    ($magiamgia != 0) ? $chietKhau .= '5% <w:br/>' : " <w:br/>";                                
                    $pkbansl .= '1 <w:br/>';
                    $pkban .= $row->name . '<w:br/>';
                    $pkbangia .= number_format($row->cost) . '<w:br/>';
                    $pkbangiathanhtien .= ($magiamgia != 0) ? number_format($row->cost - ($row->cost*$magiamgia/100)) . '<w:br/>' : number_format($row->cost) . '<w:br/>';
                    $i++;
                    $tonggiaban += ($magiamgia != 0) ? ($row->cost - ($row->cost*$magiamgia/100)) : $row->cost;
                }
                if ($row->type == 'free' && $row->free_kem == false) {
                    $sttpkfree .= $j . '<w:br/>';
                    $pkfreesl .= '1 <w:br/>';
                    if ($row->mode && $row->mode == "GIABAN")
                        $ghiChuFree .= 'Giá bán <w:br/>';
                    elseif ($row->mode && $row->mode == "CTKM")
                        $ghiChuFree .= 'CTKM <w:br/>';
                    elseif ($row->mode && $row->mode == "TANGTHEM")
                        $ghiChuFree .= 'Tặng thêm <w:br/>';
                    else
                        $ghiChuFree .= 'Tặng thêm <w:br/>';
                    $pkfree .= $row->name . '<w:br/>';
                    $pkfreegia .= number_format($row->cost) . '<w:br/>';
                    $j++;
                    $tongkm += $row->cost;
                }
            }

            $templateProcessor->setValues([
                'soHopDong' => $soHopDong,
                'ngayhd' => $arrdate[2],
                'thanghd' => $arrdate[1],
                'namhd' => $arrdate[0],
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salephone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'phone' => $sale->guest->phone,
                'carname' => $tenXe,
                'cost' => number_format($giaXe),
                'year' => $year,
                'seat' => $car->seat,
                'color' => $sale->mau,
                'vin' => $kho->vin,
                'frame' => $kho->frame,
                'sttpkban' => $sttpkban,
                'pkban' => $pkban,
                'ghiChuFree' => $ghiChuFree,
                'pkbansl' => $pkbansl,
                'chietKhau' => $chietKhau,
                'pkbangia' => $pkbangia,
                'pkbangias' => $pkbangiathanhtien,
                'sttpkfree' => $sttpkfree,
                'pkfree' => $pkfree,
                'pkfreesl' => $pkfreesl,
                'pkfreegia' => $pkfreegia,
                'tonggiaban' => number_format($tonggiaban),
                'tongkm' => number_format($tongkm)
            ]);

        $pathToSave = 'template/PHUKIENDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In yêu cầu lắp phụ kiện số hợp đồng " . $soHopDong;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In yêu cầu lắp phụ kiện";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inPhuKienKemTheoXe($id) {
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/YEUCAUPK.docx');
            $sale = HopDong::find($id);
            $kho = KhoV2::find($sale->id_car_kho);
            $year = $kho->year;
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name;
            $outhd = 'YÊU CẦU TẶNG KÈM ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);

            // Exe phụ kiện bán và free
            $package = $sale->package;

            $sttpkban = "";
            $chietKhauBlank = "";
            $pkban = "";
            $pkbansl = "";
            $pkbangia = "";
            $i = 1;

            $sttpkfree = "";
            $loai = "";
            $pkfree = "";
            $pkfreesl = "";
            $ghiChuFree = "";
            $pkfreegia = "";
            $j = 1;
            $tonggiaban = 0;
            $tongkm = 0;
            foreach($package as $row) {
                // if ($row->type == 'pay') {
                //     $sttpkban .= $i . '<w:br/>';
                //     $chietKhauBlank .= '........ <w:br/>';                     
                //     $pkbansl .= '1 <w:br/>';
                //     $pkban .= $row->name . '<w:br/>';
                //     $pkbangia .= number_format($row->cost) . '<w:br/>';
                //     $i++;
                //     $tonggiaban += $row->cost;
                // }
                if ($row->type == 'free' && $row->free_kem == true && $row->mode != "GIABAN" && $row->mode != "TANGTHEM" && $row->mode != "CTKM") {
                    $sttpkfree .= $j . '<w:br/>';
                    $loai .= 'Kèm theo xe <w:br/>';
                    $pkfreesl .= '1 <w:br/>';
                    // if ($row->mode && $row->mode == "GIABAN")
                    //     $ghiChuFree .= 'Giá bán <w:br/>';
                    // elseif ($row->mode && $row->mode == "CTKM")
                    //     $ghiChuFree .= 'CTKM <w:br/>';
                    // elseif ($row->mode && $row->mode == "TANGTHEM")
                    //     $ghiChuFree .= 'Tặng thêm <w:br/>';
                    // else
                    //     $ghiChuFree .= 'Tặng thêm <w:br/>';
                    $pkfree .= $row->name . '<w:br/>';
                    $pkfreegia .= number_format($row->cost) . '<w:br/>';
                    $j++;
                    $tongkm += $row->cost;
                }
            }

            $templateProcessor->setValues([
                'soHopDong' => $soHopDong,
                'ngayhd' => $arrdate[2],
                'thanghd' => $arrdate[1],
                'namhd' => $arrdate[0],
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salephone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'phone' => $sale->guest->phone,
                'carname' => $tenXe,
                'cost' => number_format($giaXe),
                'year' => $year,
                'seat' => $car->seat,
                'color' => $sale->mau,
                'vin' => $kho->vin,
                'frame' => $kho->frame,
                'sttpkban' => $sttpkban,
                'pkban' => $pkban,
                'ghiChuFree' => $ghiChuFree,
                'pkbansl' => $pkbansl,
                'pkbangia' => $pkbangia,
                'sttpkfree' => $sttpkfree,
                'pkfree' => $pkfree,
                'pkfreesl' => $pkfreesl,
                'pkfreegia' => $pkfreegia,
                'tonggiaban' => number_format($tonggiaban),
                'tongkm' => number_format($tongkm),
                'loai' => $loai
            ]);

        $pathToSave = 'template/YEUCAUPKDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In yêu cầu phụ kiện kèm theo xe số hd " . $soHopDong;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In yêu cầu phụ kiện kèm theo xe";
        $his->ghiChu = "";
        $his->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inDeNghiRutHoSoXe($id) {
        $name = User::find(Auth::user()->id)->userDetail->surname;
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/DENGHIRUTHOSO.docx');
            $sale = HopDong::find($id);
            $kho = KhoV2::find($sale->id_car_kho);
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name;
            $outhd = 'ĐỀ NGHỊ RÚT HS VÀ XUẤT HĐ ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
            $templateProcessor->setValues([
                'soHopDong' => $soHopDong,
                'ngayhd' => $arrdate[2],
                'thanghd' => $arrdate[1],
                'namhd' => $arrdate[0],
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salephone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'mst' => $sale->guest->mst,
                'phone' => $sale->guest->phone,
                'carname' => $tenXe,
                'cost' => number_format($giaXe),
                'seat' => $car->seat,
                'color' => $sale->mau,
                'vin' => $kho->vin,
                'frame' => $kho->frame,
                'sodonhang' => $kho->soDonHang,
                'sobaolanh' => $kho->soBaoLanh,
                'adminsale' => $name,
                'giaVon' => number_format($car->giaVon),
                'giaBan' => number_format($sale->giaXe),
            ]);

        $pathToSave = 'template/DENGHIRUTHOSODOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In yêu cầu rút hồ sơ xe và xuất hoá đơn hợp đồng số " . $soHopDong;
        $nhatKy->save();

        $his = new HistoryHopDong();
        $his->idDeNghi = $id;
        $his->id_user = Auth::user()->id;
        $his->ngay = Date("H:m:s d-m-Y");
        $his->noiDung = "In yêu cầu rút hồ sơ xe và xuất hoá đơn";
        $his->ghiChu = "";
        $his->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function baoCaoHopDong(Request $request){
        // dd($request);
        $_from = \HelpFunction::revertDate($request->tu);
        $_to = \HelpFunction::revertDate($request->den);
        
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "KINH DOANH - BÁO CÁO HỢP ĐỒNG";
        $nhatKy->noiDung = "Xem báo cáo hợp đồng từ " . $_from . " đến " . $_to;
        $nhatKy->save();
        
        if (Auth::user()->hasRole('system') 
        || Auth::user()->hasRole('baocaohopdong') || Auth::user()->hasRole('xuatbaocao')) {
            switch($request->baoCao) {
                case 1: {
                    $hd = HopDong::orderBy('id','desc')->get();
                } break;
                case 2: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 3: {
                    $hd = HopDong::where([
                        ['requestCheck','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 4: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 5: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',true],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 6: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 7: {
                    $hd = HopDong::where([
                        ['lead_check_cancel','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 8: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 9: {
                    $hd = HopDong::select("hop_dong.*")
                    ->join("kho_v2 as k","k.id","=","hop_dong.id_car_kho")
                    ->where([
                        ['hop_dong.requestCheck','=',true],
                        ['hop_dong.admin_check','=',true],
                        ['hop_dong.lead_check','=',true],
                        ['hop_dong.hdWait','=',false],
                        ['hop_dong.lead_check_cancel','=',false],
                        ['k.xuatXe','=',true],
                        ['hop_dong.hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                default: $type = 0;
            }
        } else {
            switch($request->baoCao) {
                case 1: {
                    $hd = HopDong::where('id_user_create','=',Auth::user()->id)
                    ->orderBy('id','desc')->get();
                } break;
                case 2: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 3: {
                    $hd = HopDong::where([
                        ['requestCheck','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 4: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 5: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',true],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 6: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 7: {
                    $hd = HopDong::where([
                        ['lead_check_cancel','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 8: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 9: {
                    $hd = HopDong::select("hop_dong.*")
                    ->join("kho_v2 as k","k.id","=","hop_dong.id_car_kho")
                    ->where([
                        ['hop_dong.requestCheck','=',true],
                        ['hop_dong.admin_check','=',true],
                        ['hop_dong.lead_check','=',true],
                        ['hop_dong.hdWait','=',false],
                        ['hop_dong.lead_check_cancel','=',false],
                        ['hop_dong.hdDaily','=',false],
                        ['k.xuatXe','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                default: abort(403);
            }
        }        
        $i = 1;
        foreach($hd as $row) {
            if ($request->baoCao != 9 && (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                $codeCar = $row->carSale->typeCar->code;
                $guest = $row->guest->name;
                $phone = $row->guest->phone;
                $sale = $row->user->userDetail->surname;
                $loaihd = ($row->hdDaiLy) ? "<span class='text-bold'>Đại lý</span>" : "<span class='text-secondary'>Bán lẻ</span>";
                $isTienMat = ($row->isTienMat) ? "<span class='text-bold text-success'>Tiền mặt</span>" : "<span class='text-bold'>Ngân hàng</span>";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                $magiamgia = $row->magiamgia;

                $giaNiemYet = $row->giaNiemYet;
                $truTienMat = ($giaNiemYet > $giaXe) ? ($giaNiemYet - $giaXe) : 0;

                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $phiVanChuyen = $row->phiVanChuyen;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
                // $hh = $row->hoaHongMoiGioi;               
                // Support KT ----------
                $tangTB = 0;
                $tangBH = 0;
                $tangPK = 0;
                $tangCongDK = 0;
                $ngayNhanNo = 0;
                $phiLaiVay = 0;
                $phiLuuKho = 0;
                $hhSale = $row->hoaHongSale;                
                $pvc = $row->phiVanChuyen;
                $hasNhanNo = "";
                $giavonbh = 0;
                $hhcongdk = 0;
                $_giavonpk = 0;
                $loinhuanbaohiem = 0;
                $loinhuancongdk = 0;
                // $giavonpkban = 0;
                if ($row->id_car_kho != null) {
                    $ktKho = KhoV2::find($row->id_car_kho); 
                    $giavonbh = $ktKho->giavonbh;
                    $hhcongdk = $ktKho->hhcongdk;
                    $_giavonpk = $ktKho->giavonpk;
                    if ($ktKho->ghiChu != null)
                        $hasNhanNo = ($ktKho->ghiChu == 1) ? "" : " <i style='font-size: 10pt;'>Không nhận nợ</i>";
                    else
                        $hasNhanNo = " <i style='font-size: 10pt;'>Null</i>";
                    $phiLuuKho = $ktKho->xangLuuKho;                  
                    if ($ktKho->ngayNhanNo != null) {
                        $date_1 = strtotime($ktKho->ngayNhanNo);
                        if ($ktKho->ngayRutHoSo != null)
                            $date_2 = strtotime($ktKho->ngayRutHoSo);
                        else
                            $date_2 = time();
                        $datediff = $date_2 - $date_1;
                        $ngayNhanNo = round($datediff / (60 * 60 * 24)) + 1;
                        if (($ktKho->giaTriVay != null && $ktKho->giaTriVay != 0) && ($ktKho->laiSuatVay != null &&  $ktKho->laiSuatVay != 0)) {
                            // let countNgayNhanNo = Math.abs(CountTheDays(date_1, date_2)) + 1;
                            // formatNumber(Math.round((row.giaVon * (row.giaTriVay/100) * (row.laiSuatVay/100)) / 365) * countNgayNhanNo) + "</strong>";
                            $phiLaiVay = round(($giaVon * ($ktKho->giaTriVay / 100) * ($ktKho->laiSuatVay / 100)) / 365) * $ngayNhanNo;
                        }                       
                    }
                }                  
                // ---------------------               
                $package = $row->package;
                foreach($package as $row2) {                
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += $p->giaVon;
                        $khuyenMai += $p->giaVon;
                       } else {
                        $tangPK += $row2->cost;
                        $khuyenMai += $row2->cost;
                       }
                       // -----------------------
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                       // ---- Suport KT --------
                       if ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS") {
                        $tangBH = $row2->cost;
                       }
                       if ($row2->name == "Phí trước bạ") {
                        $tangTB = $row2->cost;
                       }
                       if ($row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $tangCongDK = $row2->cost;
                       }
                       // -----------------------
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS")) {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                        // $temp_bhpk = BHPK::find($row2->mapk);
                        // if ($temp_bhpk) {
                        //     $giavonpkban += $temp_bhpk->giaVon;
                        // }
                    }
                }

                $loinhuanbaohiem = $bhvc - $giavonbh;
                $loinhuancongdk = $dangky - ($dangky*$hhcongdk/100);
                $loinhuanpkban = ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))) - $_giavonpk;

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $phiVanChuyen);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "<span class='text-bold text-danger'>".round($tiSuat,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuat,2)."%</span>";

                // ---- Suport KT --------
                $laiGop = $loiNhuan - ($phiLuuKho + $phiLaiVay + $hhSale);
                $tiSuatLaiGop = ($giaVon) ? ($laiGop*100/$giaVon) : 0;
                $tiSuatLaiGop = ($tiSuatLaiGop < 3) ? "<span class='text-bold text-danger'>".round($tiSuatLaiGop,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuatLaiGop,2)."%</span>";
                // -----------------------
                $loinhuanfinal = $laiGop + $loinhuanbaohiem + $loinhuancongdk + $loinhuanpkban;
                $tiSuatFinal = ($giaVon) ? ($loinhuanfinal*100/$giaVon) : 0;
                $tiSuatFinal = ($tiSuatFinal < 3) ? "<span class='text-bold text-danger'>".round($tiSuatFinal,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuatFinal,2)."%</span>";
                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    $status = "<strong class='text-warning'>Hợp đồng đại lý</strong>";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "<strong class='text-danger'>Hợp đồng huỷ</strong>";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "<strong class='text-secondary'>Mới tạo</strong>";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "<strong class='text-info'>Đợi duyệt (admin)</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "<strong class='text-primary'>Đợi duyệt (Trưởng phòng)</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "<strong class='text-pink'>Hợp đồng chờ</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false)
                        $status = "<strong class='text-success'>Hợp đồng ký</strong>";
                }                
                // <td>ĐN/0".$row->id."/".$codeCar."</td>
                echo "<tr>
                    <td>".($i++)."</td>
                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                    <td>".$row->guest->nguon."</td>
                    <td>".$loaihd."</td>
                    <td>".$status."</td>
                    <td>".$sale."</td>
                    <td>".$guest."</td>
                    <td>".$dongxe."</td>
                    <td>".$mau."</td>
                    <td>".$isTienMat."</td>
                    <td>".number_format($giaNiemYet)."</td>
                    <td class='text-bold'>".number_format($giaXe)."</td>                    
                    <td class='text-bold text-secondary'>".number_format($giaVon)."".($row->isGiaVon ? "" : "<span style='font-size: 90%;'>(+)</span>")."</td>
                    <td class='text-bold' style='color: brown;'>".number_format($truTienMat)."</td>
                    <td class='text-bold'>".number_format($cpkhac)."</td>
                    <td class='text-bold text-warning'>".number_format($htvSupport)."</td>
                    <td>".number_format($tangTB)."</td>
                    <td>".number_format($tangBH)."</td>
                    <td>".number_format($tangPK)."</td>
                    <td>".number_format($tangCongDK)."</td>
                    <td>".number_format($khuyenMai)."</td>
                    <td>".number_format($bhvc)."</td>
                    <td>".number_format($giavonbh)."</td>
                    <td>".number_format($loinhuanbaohiem)."</td>
                    <td>".number_format(($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))))." ".($magiamgia != 0 ? "<span class='text-danger'>(-".$magiamgia."%)</span>" : "")."</td>
                    <td>".number_format($_giavonpk)."</td>
                    <td>".number_format($loinhuanpkban)."</td>
                    <td>".number_format($dangky)."</td>
                    <td>".number_format($dangky*$hhcongdk/100)."</td>
                    <td>".number_format($loinhuancongdk)."</td>
                    <td>".number_format($pvc)."</td>
                    <td class='text-bold text-success'>".number_format($loiNhuan)."</td>
                    <td>".$tiSuat."</td>
                    <td>".(($ngayXuatXe) ? "<span class='text-bold text-primary'>".\HelpFunction::revertDate($ngayXuatXe)."</span>" : "")."</td>
                    <td class='text-bold text-warning'>".($ngayNhanNo == 0 ? "" : $ngayNhanNo)."".$hasNhanNo."</td>
                    <td>".number_format($phiLaiVay)."</td>
                    <td>".number_format($phiLuuKho)."</td>
                    <td>".number_format($hhSale)."</td>
                    <td class='text-bold text-success'>".number_format($laiGop)."</td>
                    <td>".$tiSuatLaiGop."</td>
                    <td class='text-bold text-pink'>".number_format($loinhuanfinal)."</td>
                    <td>".$tiSuatFinal."</td>
                    <td>
                        <button data-idhopdong='".$row->id."' id='xemChiTiet' data-toggle='modal' data-target='#showModal' class='btn btn-success btn-sm'>Chi tiết</button>
                    </td>
                </tr>";
            }    

            $ngayGiaoXe = "";
            if ($row->id_car_kho != null) {
                $kho = KhoV2::find($row->id_car_kho);
                $ngayGiaoXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
            }      
            
            if ($ngayGiaoXe != "" && $request->baoCao == 9 && (strtotime(\HelpFunction::revertDate($ngayGiaoXe)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::revertDate($ngayGiaoXe)) <= strtotime($_to))) {
                $codeCar = $row->carSale->typeCar->code;
                $guest = $row->guest->name;
                $phone = $row->guest->phone;
                $sale = $row->user->userDetail->surname;
                $loaihd = ($row->hdDaiLy) ? "<span class='text-bold'>Đại lý</span>" : "<span class='text-secondary'>Bán lẻ</span>";
                $isTienMat = ($row->isTienMat) ? "<span class='text-bold text-success'>Tiền mặt</span>" : "<span class='text-bold'>Ngân hàng</span>";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                $magiamgia = $row->magiamgia;

                $giaNiemYet = $row->giaNiemYet;
                $truTienMat = ($giaNiemYet > $giaXe) ? ($giaNiemYet - $giaXe) : 0;

                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $phiVanChuyen = $row->phiVanChuyen;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
                // $hh = $row->hoaHongMoiGioi;               
                // Support KT ----------
                $tangTB = 0;
                $tangBH = 0;
                $tangPK = 0;
                $tangCongDK = 0;
                $ngayNhanNo = 0;
                $phiLaiVay = 0;
                $phiLuuKho = 0;
                $hhSale = $row->hoaHongSale;                
                $pvc = $row->phiVanChuyen;
                $hasNhanNo = "";
                $giavonbh = 0;
                $hhcongdk = 0;
                $hhcongdk = 0;
                $loinhuanbaohiem = 0;
                $loinhuancongdk = 0;
                // $giavonpkban = 0;
                if ($row->id_car_kho != null) {
                    $ktKho = KhoV2::find($row->id_car_kho); 
                    $giavonbh = $ktKho->giavonbh;
                    $hhcongdk = $ktKho->hhcongdk;
                    $_giavonpk = $ktKho->giavonpk;
                    if ($ktKho->ghiChu != null)
                        $hasNhanNo = ($ktKho->ghiChu == 1) ? "" : " <i style='font-size: 10pt;'>Không nhận nợ</i>"; 
                    else
                        $hasNhanNo = " <i style='font-size: 10pt;'>Null</i>";
                    $phiLuuKho = $ktKho->xangLuuKho;                  
                    if ($ktKho->ngayNhanNo != null) {
                        $date_1 = strtotime($ktKho->ngayNhanNo);
                        if ($ktKho->ngayRutHoSo != null)
                            $date_2 = strtotime($ktKho->ngayRutHoSo);
                        else
                            $date_2 = time();
                        $datediff = $date_2 - $date_1;
                        $ngayNhanNo = round($datediff / (60 * 60 * 24)) + 1;
                        if (($ktKho->giaTriVay != null && $ktKho->giaTriVay != 0) && ($ktKho->laiSuatVay != null &&  $ktKho->laiSuatVay != 0)) {
                            // let countNgayNhanNo = Math.abs(CountTheDays(date_1, date_2)) + 1;
                            // formatNumber(Math.round((row.giaVon * (row.giaTriVay/100) * (row.laiSuatVay/100)) / 365) * countNgayNhanNo) + "</strong>";
                            $phiLaiVay = round(($giaVon * ($ktKho->giaTriVay / 100) * ($ktKho->laiSuatVay / 100)) / 365) * $ngayNhanNo;
                        }                       
                    }
                }                  
                // ---------------------      
               
                $package = $row->package;
                foreach($package as $row2) {                
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += $p->giaVon;
                        $khuyenMai += $p->giaVon;
                       } else {
                        $tangPK += $row2->cost;
                        $khuyenMai += $row2->cost;
                       }
                       // -----------------------                    
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                        // ---- Suport KT --------
                        if ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS") {
                            $tangBH = $row2->cost;
                        }
                        if ($row2->name == "Phí trước bạ") {
                        $tangTB = $row2->cost;
                        }
                        if ($row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $tangCongDK = $row2->cost;
                        }
                        // -----------------------
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS")) {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                        // $temp_bhpk = BHPK::find($row2->mapk);
                        // if ($temp_bhpk) {
                        //     $giavonpkban += $temp_bhpk->giaVon;
                        // }
                    }
                }
                $loinhuanbaohiem = $bhvc - $giavonbh;
                $loinhuancongdk = $dangky - ($dangky*$hhcongdk/100);
                $loinhuanpkban = ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))) - $_giavonpk;

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $phiVanChuyen);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "<span class='text-bold text-danger'>".round($tiSuat,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuat,2)."%</span>";
                // ---- Suport KT --------
                $laiGop = $loiNhuan - ($phiLuuKho + $phiLaiVay + $hhSale);
                $tiSuatLaiGop = ($giaVon) ? ($laiGop*100/$giaVon) : 0;
                $tiSuatLaiGop = ($tiSuatLaiGop < 3) ? "<span class='text-bold text-danger'>".round($tiSuatLaiGop,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuatLaiGop,2)."%</span>";
                // -----------------------
                $loinhuanfinal = $laiGop + $loinhuanbaohiem + $loinhuancongdk + $loinhuanpkban;
                $tiSuatFinal = ($giaVon) ? ($loinhuanfinal*100/$giaVon) : 0;
                $tiSuatFinal = ($tiSuatFinal < 3) ? "<span class='text-bold text-danger'>".round($tiSuatFinal,2)."%</span>" : "<span class='text-bold text-info'>".round($tiSuatFinal,2)."%</span>";
                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    $status = "<strong class='text-warning'>Hợp đồng đại lý</strong>";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "<strong class='text-danger'>Hợp đồng huỷ</strong>";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "<strong class='text-secondary'>Mới tạo</strong>";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "<strong class='text-info'>Đợi duyệt (admin)</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "<strong class='text-primary'>Đợi duyệt (Trưởng phòng)</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "<strong class='text-pink'>Hợp đồng chờ</strong>";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false)
                        $status = "<strong class='text-success'>Hợp đồng ký</strong>";
                }       
                // <td>ĐN/0".$row->id."/".$codeCar."</td>
                echo "<tr>
                    <td>".($i++)."</td>
                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                    <td>".$row->guest->nguon."</td>
                    <td>".$loaihd."</td>
                    <td>".$status."</td>
                    <td>".$sale."</td>
                    <td>".$guest."</td>
                    <td>".$dongxe."</td>
                    <td>".$mau."</td>
                    <td>".$isTienMat."</td>
                    <td>".number_format($giaNiemYet)."</td> 
                    <td class='text-bold'>".number_format($giaXe)."</td>                    
                    <td class='text-bold text-secondary'>".number_format($giaVon)."".($row->isGiaVon ? "" : "<span style='font-size: 90%;'>(+)</span>")."</td>
                    <td class='text-bold' style='color: brown;'>".number_format($truTienMat)."</td>
                    <td class='text-bold'>".number_format($cpkhac)."</td>
                    <td class='text-bold text-warning'>".number_format($htvSupport)."</td>
                    <td>".number_format($tangTB)."</td>
                    <td>".number_format($tangBH)."</td>
                    <td>".number_format($tangPK)."</td>
                    <td>".number_format($tangCongDK)."</td>
                    <td>".number_format($khuyenMai)."</td>
                    <td>".number_format($bhvc)."</td>
                    <td>".number_format($giavonbh)."</td>
                    <td>".number_format($loinhuanbaohiem)."</td>
                    <td>".number_format(($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))))." ".($magiamgia != 0 ? "<span class='text-danger'>(-".$magiamgia."%)</span>" : "")."</td>
                    <td>".number_format($_giavonpk)."</td>
                    <td>".number_format($loinhuanpkban)."</td>
                    <td>".number_format($dangky)."</td>
                    <td>".number_format($dangky*$hhcongdk/100)."</td>
                    <td>".number_format($loinhuancongdk)."</td>
                    <td>".number_format($pvc)."</td>
                    <td class='text-bold text-success'>".number_format($loiNhuan)."</td>
                    <td>".$tiSuat."</td>
                    <td>".(($ngayXuatXe) ? "<span class='text-bold text-primary'>".\HelpFunction::revertDate($ngayXuatXe)."</span>" : "")."</td>
                    <td class='text-bold text-warning'>".($ngayNhanNo == 0 ? "" : $ngayNhanNo)."".$hasNhanNo."</td>
                    <td>".number_format($phiLaiVay)."</td>
                    <td>".number_format($phiLuuKho)."</td>
                    <td>".number_format($hhSale)."</td>
                    <td class='text-bold text-success'>".number_format($laiGop)."</td>
                    <td>".$tiSuatLaiGop."</td>
                    <td class='text-bold text-pink'>".number_format($loinhuanfinal)."</td>
                    <td>".$tiSuatFinal."</td>
                    <td>
                        <button data-idhopdong='".$row->id."' id='xemChiTiet' data-toggle='modal' data-target='#showModal' class='btn btn-success btn-sm'>Chi tiết</button>
                    </td>
                </tr>";
            }  
        }         
    }

    public function exportExcel($from,$to,$loai) {
        return Excel::download(new ExportBaoCaoHopDongController($from,$to,$loai), 'baocaohopdong.xlsx');
    }

    public function exportExcelCustom($from,$to,$loai) {
        return Excel::download(new ExportForHuyController($from,$to,$loai), 'baocaohopdongcustom.xlsx');
    }

    public function loadChiTietHopDong(Request $request){
        $hd = HopDong::find($request->idhopdong);
        $magiamgia = $hd->magiamgia;
        $maDeNghi = "ĐN/".$hd->id."/".$hd->carSale->typeCar->code;
        $ngayTao = \HelpFunction::getDateRevertCreatedAt($hd->created_at);
        $soHopDong = "Chưa gán";
        if ($hd->code != null)
            $soHopDong = $hd->code.".".$hd->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($hd->created_at)."/HĐMB-PA";
            
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "KINH DOANH - BÁO CÁO HỢP ĐỒNG";
        $nhatKy->noiDung = "Xem chi tiết hợp đồng " . $soHopDong . " Đề nghị số: " . $maDeNghi;
        $nhatKy->save();

        $tenNhanVien = $hd->user->userDetail->surname;
        $tenKhachHang = $hd->guest->name;
        $ngaySinh = $hd->guest->ngaySinh;
        $soDienThoai = $hd->guest->phone;
        $maSoThue = $hd->guest->mst;
        $cmnd = $hd->guest->cmnd;
        $ngayCap = $hd->guest->ngayCap;
        $noiCap = $hd->guest->noiCap;
        $diaChi = $hd->guest->address;
        $daiDien = $hd->guest->daiDien;
        $chucVu = $hd->guest->chucVu;
        //-----------
        $carSale = TypeCarDetail::find($hd->id_car_sale);
        $hinhThucThanhToan = ($hd->isTienMat) ? "Tiền mặt" : "Ngân hàng";
        //-------
        $namsx = "Chưa gán";
        $soKhung = "Chưa gán";
        $soMay = "Chưa gán";
        if ($hd->id_car_kho) {
            $kho = KhoV2::find($hd->id_car_kho);
            $namsx = $kho->year;
            $soKhung = $kho->vin;
            $soMay = $kho->frame;
        }
        $tenXeBan = $carSale->name;
        $mauXeBan = $hd->mau;
        $giaXeBan = $hd->giaXe;
        $tienDatCoc = $hd->tienCoc;        
        $phiVanChuyen = $hd->phiVanChuyen;    
        $chiTietXe = 'Màu: '.$hd->mau
        .'; Năm SX: '.$namsx
        .'; Hộp số: '.$carSale->gear
        .'; Chỗ ngồi: '.$carSale->seat
        .'; Động cơ: '.$carSale->machine
        .'; Nhiên liệu: '.$carSale->fuel;
        //----------
        $phi = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
        ->where([
            ['saleoffv2.id_hd','=', $hd->id],
            ['package.type','=','cost']
        ])->get();
        $tongCongPhi = 0;
        $truPhi = 0;
        foreach($phi as $row) {
            $tongCongPhi += $row->cost;
            if ($row->cost_tang)
                $truPhi += $row->cost;
        }            
        $cacLoaiPhi = $phi;
        
        //--------
        $phuKien = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
        ->where([
            ['saleoffv2.id_hd','=', $hd->id],
            ['package.type','=','pay']
        ])->get();
        $tongPhuKienBan = 0;
        $tongPhuKienBanGiam = 0;
        foreach($phuKien as $row) {
            $tongPhuKienBan += $row->cost; 
            $tongPhuKienBanGiam += ($magiamgia == 0 ? $row->cost : ($row->cost - ($row->cost*$magiamgia/100)));
        }
        $phuKienBan = $phuKien;
        //--------
        $phuKienKM = SaleOffV2::select('package.*')
        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
        ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
        ->where([
            ['saleoffv2.id_hd','=', $hd->id],
            ['package.type','=','free']
        ])->get();
        $tongPhuKienKhuyenMai = 0;
        foreach($phuKienKM as $row)
            $tongPhuKienKhuyenMai += $row->cost;
        $phuKienKhuyenMai = $phuKienKM;
        $tongGiaTriHopDong = ($hd->giaXe + $tongPhuKienBanGiam + $tongCongPhi) - $truPhi;
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => "Đã tải thông tin hợp đồng",
            'maDeNghi' => $maDeNghi,
            'ngayTao' => $ngayTao,
            'soHopDong' => $soHopDong,
            'tenNhanVien' => $tenNhanVien,
            'tenKhachHang' => $tenKhachHang,
            'ngaySinh' => \HelpFunction::revertDate($ngaySinh),
            'soDienThoai' => $soDienThoai,
            'maSoThue' => $maSoThue,
            'cmnd' => $cmnd,
            'ngayCap' => \HelpFunction::revertDate($ngayCap),
            'noiCap' => $noiCap,
            'diaChi' => $diaChi,
            'daiDien' => $daiDien,
            'chucVu' => $chucVu,
            'tenXeBan' => $tenXeBan,
            'tenXeBan2' => $tenXeBan,
            'soKhung' => $soKhung,
            'soMay' => $soMay,
            'mauXeBan' => $mauXeBan,
            'giaXeBan' => number_format($giaXeBan),
            'tienDatCoc' => number_format($tienDatCoc),
            'chiTietXe' => $chiTietXe,
            'cacLoaiPhi' => $cacLoaiPhi,
            'tongCongPhi' => number_format($tongCongPhi),
            'phuKienBan' => $phuKienBan,
            'tongPhuKienBan' => number_format($tongPhuKienBan),
            'magiamgia' => $magiamgia . "%",
            'tongPhuKienBanGiam' => number_format($tongPhuKienBanGiam),
            'phuKienKhuyenMai' => $phuKienKhuyenMai,
            'tongPhuKienKhuyenMai' => number_format($tongPhuKienKhuyenMai),
            'tongGiaTriHopDong' => number_format($tongGiaTriHopDong),
            'hinhThucThanhToan' => $hinhThucThanhToan,
            'phiVanChuyen' => $phiVanChuyen
        ]);
    }

    // Quản lý hợp đồng cho Kế toán
    public function getDanhSachHopDong() {
        $hdWait = "";
        $code = "";
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('ketoan')) {
            $result = HopDong::select('*')->where([
                ['lead_check','=',true],
                ['hdWait','=',false],
            ])->orderby('id','desc')->get();
            if($result) {
                echo "<option value='0'>Chọn</option>";
                foreach($result as $row){ 
                    if($row->hdWait == true) 
                        $hdWait = "(Hợp đồng chờ)";
                    else
                        $hdWait = "";

                    if($row->code == 0) 
                        $code = "";
                    else
                        $code = "[HĐ: ".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."/HĐMB-PA]";

                    if($row->lead_check_cancel	== true) 
                        echo "<option class='bg-danger' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã hủy) ".$hdWait."</option>";
                    elseif ($row->requestCheck == false)
                        echo "<option class='bg-secondary' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Chưa gửi) ".$hdWait."</option>";
                    elseif($row->requestCheck == true && $row->admin_check == false) 
                        echo "<option class='bg-success' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Admin chưa duyệt) ".$hdWait."</option>";
                    elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                        echo "<option class='bg-warning' value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Trưởng phòng chưa duyệt) ".$hdWait."</option>";
                    elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                        echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã duyệt) ".$hdWait."</option>";
                }
            } else {
                echo "<option value='0'>Không tìm thấy</option>";
            }
        } 
    }

    public function getQuanLyHopDong() {
        $xeList = TypeCarDetail::select('*')->orderBy('name','asc')->get();
        $hopdong = HopDong::select('*')->where('id_user_create', Auth::user()->id)
        ->orderby('id','desc')->get();
        return view('ketoan.quanlyhopdong', ['hopdong' => $hopdong, 'xeList' => $xeList]);
    }

    public function traCuuPanel() {
        $typecar = TypeCar::select("*")->get();
        return view('tracuu.phukien', ['typecar' => $typecar]);
    }

    public function getHistory($id) {
        $arr = [];

        $his = HistoryHopDong::select("*")
        ->where("idDeNghi", $id)
        ->orderBy("id","desc")
        ->get();
        foreach($his as $row) {
            $obj = "";
            $obj = (object) $obj;
            $obj->ngay = $row->ngay;
            $obj->user = $row->user->userDetail->surname;
            $obj->noiDung = $row->noiDung;
            $obj->ghiChu = $row->ghiChu;
            array_push($arr, $obj);
        }
        if($arr) {
            return response()->json([
                'message' => 'Load lịch sử cập nhật thành công!',
                'type' => "info",
                'data' => $arr,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Không tìm thấy thông tin hoặc lỗi máy chủ!',
                'type' => "error",
                'code' => 500
            ]);
        }
    }
}
