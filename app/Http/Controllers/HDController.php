<?php

namespace App\Http\Controllers;

use App\BhPkPackage;
use App\CarSale;
use App\Guest;
use App\Sale;
use App\SaleOff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;

class HDController extends Controller
{
    //
    public function index() {
        $guest = Guest::all();
        $xeList = CarSale::where('order', 1)->orWhere('exist',1)->orderBy('id_type_car_detail','asc')->get();
        return view('page.hd', ['guest' => $guest, 'xeList' => $xeList]);
    }

    public function getList() {
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

    public function getListCode() {
        $result = Sale::select('sale.id','g.name as surname', 'c.cost','t.name','sale.complete','sale.admin_check','sale.lead_sale_check')->join('guest as g','sale.id_guest','=','g.id')->join('car_sale as c','sale.id_car_sale','=','c.id')->join('type_car_detail as t','c.id_type_car_detail','=','t.id')->orderby('sale.id','desc')->get();
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
        $code = new Sale();
        $code->id_guest = $request->idGuest;
        $code->id_car_sale = $request->idCarSale;
        $code->tamUng = $request->tamUng;
        $code->id_user_create = Auth::user()->id;
        $code->save();
        if($code) {
            return response()->json([
                'message' => 'Tạo mã hợp đồng thành công!',
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

    public function getGuestPersonal(){
        $result = Guest::where('id_type_guest',1)->get();
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
        $result = Guest::where('id_type_guest',2)->get();
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
        $result = Sale::select('sale.id')->join('guest as g','sale.id_guest','=','g.id')->join('car_sale as c','sale.id_car_sale','=','c.id')->join('type_car_detail as t','c.id_type_car_detail','=','t.id')->orderby('sale.id','desc')->get();
        if($result) {
            if($result) {
                echo "<option value='0'>Chọn</option>";
                foreach($result as $row){
                    echo "<option value='".$row->id."'>HAGI-0".$row->id."/HDMB-PA</option>";
                }
            } else {
                echo "<option value='0'>Không tìm thấy</option>";
            }
        }
    }

    public function detailHD($id) {
        $result = Sale::select('sale.id as idsale','sale.tamUng','g.*','g.name as surname', 'c.*','t.name as name_car')->join('guest as g','sale.id_guest','=','g.id')->join('car_sale as c','sale.id_car_sale','=','c.id')->join('type_car_detail as t','c.id_type_car_detail','=','t.id')->where('sale.id', $id)->orderby('sale.id','desc')->first();
//        $pkban = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where('sale_off.id_sale', $id)->get();
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

    public function getpkpay($id) {
        $pkban = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
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

    public function deletePkPay(Request $request) {
        $result = SaleOff::where([
            ['id_sale','=', $request->sale],
            ['id_bh_pk_package','=', $request->id]
        ])->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete PK Pay successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getpkfree($id) {
        $pkban = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
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

    public function getpkcost($id) {
        $pkcost = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
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

    public function deletePkFree(Request $request) {
        $result = SaleOff::where([
            ['id_sale','=', $request->sale],
            ['id_bh_pk_package','=', $request->id]
        ])->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete PK Free successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function deletePkCost(Request $request) {
        $result = SaleOff::where([
            ['id_sale','=', $request->sale],
            ['id_bh_pk_package','=', $request->id]
        ])->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete PK Cost successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function addPkPay(Request $request){
        $pkpay = new BhPkPackage();
        $pkpay->name = $request->namePkPay;
        $pkpay->cost = $request->giaPkPay;
        $pkpay->profit = $request->hoaHongPkPay;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'pay';
        $pkpay->save();
        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $request->idHD;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
            if($saleOff) {
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

    public function addPkFree(Request $request){
        $pkpay = new BhPkPackage();
        $pkpay->name = $request->namePkFree;
        $pkpay->cost = $request->giaPkFree;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'free';
        $pkpay->save();
        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $request->idHD2;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
            if($saleOff) {
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

    public function addPkCost(Request $request){
        $pkpay = new BhPkPackage();
        $pkpay->name = $request->namePkCost;
        $pkpay->cost = $request->giaPkCost;
        $pkpay->profit = $request->hoaHongPkCost;
        $pkpay->id_user_create = Auth::user()->id;
        $pkpay->type = 'cost';
        $pkpay->save();
        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $request->idHD3;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
            if($saleOff) {
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

    public function getTotal($id){
        $sale = Sale::find($id);
        $sum = 0;
        $package = $sale->package;
        foreach($package as $row) {
            if ($row->type == 'free') continue;
            $sum += $row->cost;
        }
        echo $sum + $sale->carSale->cost;
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
        $templateProcessor = new TemplateProcessor('template/CN_HD_TM_NO_PK.docx');
        $hasPK = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CN_HD_TM.docx');
            // Set data from database
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
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
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'phuKien' => number_format($sum),
                'giaPhuKien' => number_format($sum),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        } else {
            // Không phụ kiện
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
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
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        }
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        return response()->download($pathToSave);
    }
    public function cnnh($id) {
        $templateProcessor = new TemplateProcessor('template/CN_HD_NH_NO_PK.docx');
        $hasPK = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CN_HD_NH.docx');
            // Set data from database
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
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
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'phuKien' => number_format($sum),
                'giaPhuKien' => number_format($sum),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        } else {
            // Không phụ kiện
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
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
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        }
        $pathToSave = 'template/CN_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        return response()->download($pathToSave);
    }
    public function cttm($id) {
        $templateProcessor = new TemplateProcessor('template/CT_HD_TM_NO_PK.docx');
        $hasPK = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CT_HD_TM.docx');
            // Set data from database
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'phuKien' => number_format($sum),
                'giaPhuKien' => number_format($sum),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        } else {
            // Không phụ kiện
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        }
        $pathToSave = 'template/CT_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        return response()->download($pathToSave);
    }
    public function ctnh($id) {
        $templateProcessor = new TemplateProcessor('template/CT_HD_NH_NO_PK.docx');
        $hasPK = SaleOff::select('package.*')->join('bh_pk_package as package','sale_off.id_bh_pk_package','=','package.id')->join('sale as s','sale_off.id_sale','=','s.id')->where([
            ['sale_off.id_sale','=', $id],
            ['package.type','=','pay']
        ])->exists();

        if ($hasPK) {
            $templateProcessor = new TemplateProcessor('template/CT_HD_NH.docx');
            // Set data from database
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'phuKien' => number_format($sum),
                'giaPhuKien' => number_format($sum),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        } else {
            // Không phụ kiện
            $sale = Sale::find($id);
            $sum = 0;
            $pkfree = "";
            $package = $sale->package;
            foreach($package as $row) {
                if ($row->type == 'free')
                    $pkfree .=  $row->name . ', ';
                if ($row->type == 'free' || $row->type == 'cost') continue;
                $sum += $row->cost;
            }
            $car_detail = $sale->carSale->typeCarDetail;
            $car = $sale->carSale;
            // Cá nhân
            $templateProcessor->setValues([
                'soHopDong' => 'HAGI-0' . $sale->id . "/HDMB-PA",
                'ngay' => Date('d'),
                'thang' => Date('m'),
                'nam' => Date('Y'),
                'sale' => $sale->user->userDetail->surname,
                'salePhone' => $sale->user->userDetail->phone,
                'guest' => $sale->guest->name,
                'diaChi' => $sale->guest->address,
                'dienThoai' => $sale->guest->phone,
                'tenDaiDien' => $sale->guest->daiDien,
                'chucVu' => $sale->guest->chucVu,
                'mst' => $sale->guest->mst,
                'noiDung' => $car_detail->name . ' <w:br/>Số khung: '.
                    $car->vin . ' <w:br/>Số máy: ' . $car->frame,
                'donGia' => number_format($car->cost),
                'thanhTien' => number_format($car->cost),
                'tongCong' => number_format($sum + $car->cost),
                'quaTang' => $pkfree,
                'tamUng' => number_format($sale->tamUng),
                'tamUngBangChu' => \HelpFunction::convert($sale->tamUng),
            ]);
        }
        $pathToSave = 'template/CT_HD_TM_DOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        return response()->download($pathToSave);
    }
}
