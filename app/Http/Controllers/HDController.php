<?php

namespace App\Http\Controllers;

use App\BhPkPackage;
use App\PackageV2;
use App\SaleOffV2;
use App\CarSale;
use App\Guest;
use App\RequestHD;
use App\Sale;
use App\KhoV2;
use App\HopDong;
use App\SaleOff;
use App\NhatKy;
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
        $result = Guest::where('id_type_guest',1)
            ->where('id_user_create', Auth::user()->id)
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
        $result = Guest::where('id_type_guest',2)
            ->where('id_user_create', Auth::user()->id)
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
                if ($row->type == 'pay')
                    $sumpk += $row->cost;
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $kho->year,
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
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->mau .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $kho->year,
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

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
                if ($row->type == 'pay')
                    $sumpk += $row->cost;
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
                'donGia' => number_format($sale->giaXe),
                'thanhTien' => number_format($gisaleaXe->giaXe),
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
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

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
                if ($row->type == 'pay')
                    $sumpk += $row->cost;
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
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
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

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
                if ($row->type == 'pay')
                    $sumpk += $row->cost;
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
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
                $sum += $row->cost;
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
                    '- ' . $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD<w:br/>' .
                    '- Xe mới 100%, Hộp số: ' . (($car->gear == 'AT') ? 'TỰ ĐỘNG' : 'SÀN') . '<w:br/>' .
                    '- Động cơ ' . $car->machine . 'L, Màu sơn: ' . $sale->color .'<w:br/>' .
                    '- Trang bị kèm theo xe gồm: Theo tiêu chuẩn nhà sản xuất<w:br/>' .
                    '- Năm SX: ' . $sale->year,
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In hợp đồng " . $logSoHd;
        $nhatKy->save();

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
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In phụ lục hợp đồng " . $logSoHd;
        $nhatKy->save();

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
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
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
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In phụ lục hợp đồng " . $logSoHd;
        $nhatKy->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inDeNghiCaNhan($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/DENGHI.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $tongChiPhi = 0;
            $i = 2;
            $j = 1;
            $k = 1;
            $dem = 0;
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
                if ($row->type == 'free') {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPK .=  number_format($row->cost) . '<w:br/>';
                    $dsqt .=  $row->name . ";";
                    $tongPhuKienFree += $row->cost;
                    $k++;
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'ĐNTHHĐ ' . $sale->guest->name;
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
                'thanhTien' => number_format($giaXe),
                'stt' => $stt,
                'cpKhac' => $other,
                'niemYet' => number_format($sale->giaNiemYet),
                'donGiaPK' => number_format($tongPhuKien),
                'tongPhiPhuKien' => number_format($giaXe + $tongChiPhi + $tongPhuKien),
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
                'tongPhuKienBan' => number_format($tongPhuKien),
            ]);

        $pathToSave = 'template/DENGHIDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In đề nghị thực hiện hợp đồng " . $logSoHd;
        $nhatKy->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inDeNghiCongTy($id) {
        $outhd = "";
        $logSoHd = "";
        $templateProcessor = new TemplateProcessor('template/DENGHICONGTY.docx');
            // Set data from database
            $sale = HopDong::find($id);
            $tongChiPhi = 0;
            $i = 2;
            $j = 1;
            $k = 1;
            $dem = 0;
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
                if ($row->type == 'free') {
                    $sttPK .= $k . '<w:br/>';
                    $pkfree .=  $row->name . '<w:br/>';
                    $chiPhiChiTietPK .=  number_format($row->cost) . '<w:br/>';
                    $dsqt .=  $row->name . ";";
                    $tongPhuKienFree += $row->cost;
                    $k++;
                }
            }
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $giaXe = $sale->giaXe;
            $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'ĐNTHHĐ ' . $sale->guest->name;
            $arrdate = \HelpFunction::getArrCreatedAt($sale->created_at);
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
                'cpKhac' => $other,
                'niemYet' => number_format($sale->giaNiemYet),
                'donGiaPK' => number_format($tongPhuKien),
                'tongPhiPhuKien' => number_format($giaXe + $tongChiPhi + $tongPhuKien),
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
                'tongPhuKienBan' => number_format($tongPhuKien),
            ]);

        $pathToSave = 'template/DENGHICONGTYDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In đề nghị thực hiện hợp đồng " . $logSoHd;
        $nhatKy->save();

        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
    //------------------------- HD V2
    public function getHDDeNghi() {
        $xeList = TypeCarDetail::select('*')->orderBy('name','asc')->get();
        return view('hopdong.denghi', ['xeList' => $xeList]);
    }

    public function getHDQuanLyDeNghi() {
        $xeList = TypeCarDetail::select('*')->orderBy('name','asc')->get();
        $hopdong = HopDong::select('*')->where('id_user_create', Auth::user()->id)
        ->orderby('id','desc')->get();
        return view('hopdong.quanlydenghi', ['hopdong' => $hopdong, 'xeList' => $xeList]);
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('tpkd'))
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

                // if($row->lead_check_cancel	== true) 
                //     echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCarDetail->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã hủy) ".$hdWait."</option>";
                // elseif ($row->requestCheck == false)
                //     echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCarDetail->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Chưa gửi) ".$hdWait."</option>";
                // elseif($row->requestCheck == true && $row->admin_check == false) 
                //     echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCarDetail->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Admin chưa duyệt) ".$hdWait."</option>";
                // elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                //     echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCarDetail->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Trưởng phòng chưa duyệt) ".$hdWait."</option>";
                // elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                //     echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCarDetail->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã duyệt) ".$hdWait."</option>";

                    if($row->lead_check_cancel	== true) 
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã hủy) ".$hdWait."</option>";
                elseif ($row->requestCheck == false)
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Chưa gửi) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == false) 
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Admin chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == false) 
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Trưởng phòng chưa duyệt) ".$hdWait."</option>";
                elseif($row->requestCheck == true && $row->admin_check == true && $row->lead_check == true) 
                    echo "<option value='".$row->id."'>[ĐN: ĐN/0".$row->id."/".$row->carSale->typeCar->code."]".$code."[KH: ".$row->guest->name."][Sale: ".$row->user->userDetail->surname."] (Đã duyệt) ".$hdWait."</option>";
            }
        } else {
            echo "<option value='0'>Không tìm thấy</option>";
        }
    }

    public function taoMau(Request $request) {
        $code = new HopDong();
        $code->id_user_create = Auth::user()->id;
        $code->id_car_sale = $request->chonXe;
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
            $nhatKy->chucNang = "Kinh doanh - Đề nghị thực hiện hợp đồng";
            $nhatKy->noiDung = "Thực hiện khởi tạo hợp đồng mới, danh mục chi phí đăng ký, danh mục quà tặng 05 món theo xe ";
            $nhatKy->save();

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
        $pkban = SaleOffV2::select('saleoffv2.*','package.name as name','package.cost as cost')
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

    public function addPkCost(Request $request){
        $check = HopDong::find($request->idHD3);
        if ($check->lead_check != 1) {
            $pkpay = new PackageV2;
            $pkpay->name = $request->namePkCost;
            $pkpay->cost = $request->giaPkCost;
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
                    $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                    $nhatKy->noiDung = "Bổ sung chi phí cho đề nghị thực hiện hợp đồng mã định danh: ".$request->idHD3."(không phải mã hợp đồng chính thức) Nội dung: " . $request->namePkCost . " Giá: " . round($request->giaPkCost,2);
                    $nhatKy->save();

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
        if ($check->admin_check != 1) {
            $pkpay = PackageV2::find($request->idPkCost);
            $pkpay->name = $request->endpk;
            $pkpay->cost = $request->egiapk;
            $pkpay->save();
            if($pkpay) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Điều chỉnh nội dung chi phí cho mã định danh: ".$request->idSaleHD."(không phải mã hợp đồng) Nội dung: " . $request->endpk . " Giá: " . round($request->egiapk,2);
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
        return response()->json([
            'type' => 'warning',
            'message' => 'Quản lý đã phê duyệt không thể chỉnh sửa nội dung!',
            'code' => 200
        ]);
    }

    public function addPkPay(Request $request){
        $check = HopDong::find($request->idHD);
        if ($check->lead_check != 1) {
            $pkpay = new PackageV2;
            $pkpay->name = $request->namePkPay;
            $pkpay->cost = $request->giaPkPay;
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
                    $nhatKy->noiDung = "Thêm phụ kiện bán cho mã định danh: ".$request->idHD."(không phải mã hợp đồng) Nội dung: " . $request->namePkPay . " Giá: " . round($request->giaPkPay,2);
                    $nhatKy->save();

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
            $pkpay->cost = $request->giaPkFree;
            $pkpay->id_user_create = Auth::user()->id;
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
                    $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                    $nhatKy->noiDung = "Thêm phụ kiện tặng mã định dang: ".$request->idHD2."(không phải mã hợp đồng) Nội dung: " . $request->namePkFree . " Giá: " . round($request->giaPkFree,2);
                    $nhatKy->save();

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
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa phụ kiện bán cho mã định danh: ".$request->sale."(không phải mã hợp đồng) Nội dung: " . $temp->name . " Giá: " . round($temp->cost,2);
                $nhatKy->save();

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
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa phụ kiện tặng cho mã định danh: ".$request->sale."(không phải mã hợp đồng) Nội dung: " . $temp->name . " Giá: " . round($temp->cost,2);
                $nhatKy->save();
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
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
                $nhatKy->noiDung = "Xóa chi phí cho mã định danh: ".$request->sale."(không phải mã hợp đồng) Nội dung: " . $temp->name . " Giá: " . round($temp->cost,2);
                $nhatKy->save();

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
        $sum = 0;
        $package = $sale->package;
        foreach($package as $row) {
            if ($row->type == 'free') continue;
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
                'sohd' => $sohd
            ]);
        } else {
            return response()->json([
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
        $result->hoaHongMoiGioi = $request->hoaHongMoiGioi;
        $result->hoTen = $request->hoTen;
        $result->CMND2 = $request->cmnd;
        $result->dienThoai = $request->dienThoai;
        $result->id_car_sale = $request->xeBan;
        $result->mau = $request->mauSac;
        $result->save();
        if($result) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
            $nhatKy->noiDung = "Gửi đề nghị thực hiện hợp đồng cho mã định danh: ".$request->idHopDong."(không phải mã hợp đồng)";
            $nhatKy->save();

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

    public function xoaDeNghi(Request $request){
        $result = HopDong::find($request->id);
        if($result->admin_check == false && $result->lead_check == false) {
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
        $check = HopDong::select('*')->where('code',$request->sohd)->exists(); 
        if($check) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Số hợp đồng đã tồn tại!',
                'code' => 200,
                'data' => $result
            ]);
        } 

        if((Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('system')) && $result->requestCheck == true) {
            if ($request->wait == 1) {
                $result->admin_check = true;
                $result->hdWait = true;    
                $result->code = $request->sohd; 
                $result->save();
                if($result) {

                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->noiDung = "Phê duyệt hợp đồng chờ <br/>Số hợp đồng ".$request->sohd. " đã được gán!";
                    $nhatKy->save();
        
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
                $result->save();
                if($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                    $nhatKy->noiDung = "Phê duyệt đề nghị thực hiện hợp đồng<br/>Số hợp đồng ".$request->sohd. " đã được gán!";
                    $nhatKy->save();
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

    public function ganXeHDCho(Request $request){
        $result = HopDong::find($request->id); 
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
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Gán xe: ".$car->typeCarDetail->name." màu: ".$mau." cho hợp đồng chờ số ".$temp. " và chuyển sang thành hợp đồng chính thức";
                $nhatKy->save();
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
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt hợp đồng";
                $nhatKy->noiDung = "Đã phê duyệt hợp đồng số " . $code;
                $nhatKy->save();
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
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt hợp đồng";
                        $nhatKy->noiDung = "Thực hiện chuyển xe ".$temp." từ hợp đồng số " . $result->code . " vào kho. Chuyển trạng thái xe sang STORE!";
                        $nhatKy->save();
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
                $nhatKy->noiDung = "Thực hiện hủy hợp đồng số " . $code . " Lý do hủy: " . $lydo;
                $nhatKy->save();
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
            $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
            $nhatKy->noiDung = "Gửi yêu cầu chỉnh sửa hợp đồng số " . $code . " Lý do chỉnh sửa: " . $request->lyDoChinhSua;
            $nhatKy->save();
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
            $nhatKy->noiDung = "Gửi yêu cầu hủy hợp đồng " . $code . " Lý do hủy: " . $request->lyDoHuy;
            $nhatKy->save();
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
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->noiDung = "Thực hiện yêu cầu sửa hợp đồng số " . $code . " Lý do chỉnh sửa: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $nhatKy->save();
                    $car->save();
                }    
            $result->hdWait = false;           
            $result->id_car_kho = null;
            $temp = $result->lyDoEdit;
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Thực hiện duyệt yêu cầu sửa hợp đồng số " . $code . " Lý do chỉnh sửa: " . $temp;
                $nhatKy->save();
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
                        $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                        $nhatKy->noiDung = "Thực hiện yêu cầu sửa hợp đồng số " . $code . " Lý do chỉnh sửa: " . $lyDo . " Chuyển xe ".$car->typeCarDetail->name." vào kho và trạng thái xe thành STORE";
                        $nhatKy->save();
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
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Thực hiện duyệt yêu cầu sửa hợp đồng số " . $code . " Lý do chỉnh sửa: " . $lyDo;
                $nhatKy->save();
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
            $code = $result->code;
            $result->code = 0; 
            $result->save();
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Phê duyệt đề nghị";
                $nhatKy->noiDung = "Hủy bỏ phê duyệt đề nghị thực hiện hợp đồng cho hợp đồng số " . $code;
                $nhatKy->save();
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
            $year = $kho->year;
            $soHopDong = $sale->code.".".$sale->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($sale->created_at)."/HĐMB-PA";
            $car_detail = $sale->carSale;
            $car = $sale->carSale;
            $tenXe = $car_detail->name;
            // $tenXe = $car_detail->name . ' ' . $car->machine . $car->gear . ' CKD';
            $outhd = 'PDI XE và CẤP HOA ' . $sale->guest->name;
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
            ]);

        $pathToSave = 'template/PDIXEDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In PDI số hợp đồng " . $soHopDong;
        $nhatKy->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inBHBB($id) {
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/BH5MON.docx');
            $sale = HopDong::find($id);
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
                'frame' => $kho->frame
            ]);

        $pathToSave = 'template/BH5MONDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In BHBB và 5 món số hợp đồng " . $soHopDong;
        $nhatKy->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }

    public function inPhuKien($id) {
        $outhd = "";
        $templateProcessor = new TemplateProcessor('template/PHUKIEN.docx');
            $sale = HopDong::find($id);
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
            $pkban = "";
            $pkbansl = "";
            $pkbangia = "";
            $i = 1;

            $sttpkfree = "";
            $pkfree = "";
            $pkfreesl = "";
            $pkfreegia = "";
            $j = 1;

            foreach($package as $row) {
                if ($row->type == 'pay') {
                    $sttpkban .= $i . '<w:br/>';
                    $pkbansl .= '1 <w:br/>';
                    $pkban .= $row->name . '<w:br/>';
                    $pkbangia .= number_format($row->cost) . '<w:br/>';
                    $i++;
                }
                if ($row->type == 'free') {
                    $sttpkfree .= $j . '<w:br/>';
                    $pkfreesl .= '1 <w:br/>';
                    $pkfree .= $row->name . '<w:br/>';
                    $pkfreegia .= number_format($row->cost) . '<w:br/>';
                    $j++;
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
                'pkbansl' => $pkbansl,
                'pkbangia' => $pkbangia,
                'sttpkfree' => $sttpkfree,
                'pkfree' => $pkfree,
                'pkfreesl' => $pkfreesl,
                'pkfreegia' => $pkfreegia
            ]);

        $pathToSave = 'template/PHUKIENDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Kinh doanh - Quản lý đề nghị";
        $nhatKy->noiDung = "In yêu cầu lắp phụ kiện số hợp đồng " . $soHopDong;
        $nhatKy->save();
        return response()->download($pathToSave,$outhd . '.docx',$headers);
    }
}
