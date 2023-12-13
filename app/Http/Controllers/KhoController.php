<?php

namespace App\Http\Controllers;

use App\CarSale;
use App\NhatKy;
use App\KhoV2;
use App\HopDong;
use App\TypeCarDetail;
use App\TypeCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhoController extends Controller
{
    //
    public function index() {
        $type_detail = TypeCarDetail::all()->sortBy('name');
        return view('page.kho', ['typecar' =>  $type_detail]);
    }

    public function getList() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->where('car_sale.exist',1)->get();
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

    public function getListOut() {
        $result = CarSale::select('car_sale.*','t.name as ten')
            ->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')
            ->where([
            ['car_sale.exist','=',0],
            ['car_sale.order','=',0]
        ])->get();
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

    public function getListOrder() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->where([
            ['car_sale.order','=',1],
            ['car_sale.exist','=',0]
        ])->get();
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

    public function add(Request $request) {
        $package = new CarSale;

        $package->id_type_car_detail = $request->tenXe;
        $package->year = $request->nam;
        $package->vin = $request->vin;
        $package->frame = $request->frame;
        $package->color = $request->color;
        $package->gear = $request->gear;
        $package->machine = $request->machine;
        $package->seat = $request->seat;
        $package->fuel = $request->fuel;
//        $package->cost = $request->cost;
        if ($request->exist == 1) {
            $package->exist = 1;
            $package->order = 0;
        }
        if ($request->exist == 2) {
            $package->exist = 0;
            $package->order = 1;
        }
        $package->id_user_create = Auth::user()->id;
        $package->save();

        if($package) {
            return response()->json([
                'message' => 'Insert data successfully!',
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
        $result = CarSale::where('id', $request->id)->delete();
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

    public function editShow(Request $request) {
        $result = CarSale::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Show edit data successfully!',
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

    public function update(Request $request) {
        $exist = 0;
        $order = 0;
        if ($request->eexist == 1) {
            $exist = 1;
            $order = 0;
        }
        if ($request->eexist == 2) {
            $exist = 0;
            $order = 1;
        }
        $result = CarSale::where('id', $request->eid)->update([
            "id_type_car_detail" => $request->etenXe,
            "year" => $request->enam,
            "vin" => $request->evin,
            "frame" => $request->eframe,
            "color" => $request->ecolor,
            "gear" => $request->egear,
            "machine" => $request->emachine,
            "seat" => $request->eseat,
            "fuel" => $request->efuel,
//            "cost" => $request->ecost,
            'exist' => $exist,
            'order' => $order
        ]);
        if($result) {
            return response()->json([
                'message' => 'Updated successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }


    //-------------------------------------- KHO V2
    public function getKho() {
        $type_detail = TypeCarDetail::all()->sortBy('name');
        return view('khoxe.khoxe', ['typecar' =>  $type_detail]);
    }

    public function getKhoHD() {
        $type_detail = TypeCarDetail::all()->sortBy('name');
        return view('khoxe.xuatxe', ['typecar' =>  $type_detail]);
    }    

    public function getKhoList() {
        $result = KhoV2::select('kho_v2.*','t.name as ten', 't.fuel as fuel', 't.seat as seat', 't.machine as machine', 't.gear as gear')
        ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        ->where('kho_v2.xuatXe','=',false)
        ->orderBy('id', 'desc')->get();
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

    public function getKhoHDList() {
        $result = KhoV2::select('kho_v2.*','h.id as idhopdong','t.name as ten', 'h.lead_check as tpkd', 'ud.surname as saleban','g.name as khach')
        ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        ->join('hop_dong as h','h.id_car_kho','=','kho_v2.id')
        ->join('guest as g','g.id','=','h.id_guest')
        ->join('users as u','u.id','=','h.id_user_create')
        ->join('users_detail as ud','ud.id_user','=','u.id')
        ->where('kho_v2.type','=','HD')
        ->orderBy('xuatXe', 'asc')->get();
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

    public function addV2(Request $request) {
        $kho = new KhoV2;

        $kho->id_type_car_detail = $request->tenXe;
        $kho->year = $request->nam;
        $kho->vin = $request->vin;
        $kho->frame = $request->frame;
        $kho->gps = $request->gps;
        $kho->color = $request->color;
        $kho->type = $request->trangThai;
        $kho->soDonHang = $request->soDonHang;
        $kho->soBaoLanh = $request->soBaoLanh;
        $kho->ngayNhanXe = $request->ngayNhanXe;
        $kho->ngayDat = $request->ngayDat;
        $kho->nganHang = $request->nganHang;
        $kho->ghiChu = $request->ghiChu;

        $kho->viTri = $request->viTri;

        $kho->id_user_create = Auth::user()->id;
        $kho->save();

        if($kho) {
            $chiTietXe = TypeCarDetail::find($request->tenXe);
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kho xe - Quản lý kho";
            $nhatKy->noiDung = "Nhập xe ".$chiTietXe->name." vào kho trạng thái xe " . $request->trangThai;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã thêm!',
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

    public function deleteV2(Request $request) {
        $temp = KhoV2::where('id', $request->id)->first();
        $kho = KhoV2::where('id', $request->id)->delete();
        if($kho) {          
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kho xe - Quản lý kho";
            $nhatKy->noiDung = "Xóa xe trong kho. THÔNG TIN XE XÓA<br/>Xe: "
            .$temp->typeCarDetail->name." VIN: "
            .$temp->vin." Số máy: "
            .$temp->frame." GPS: "
            .$temp->gps." Trạng thái: ". $temp->type;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function editShowV2(Request $request) {
        $result = KhoV2::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã load dữ liệu!',
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

    public function updateV2(Request $request) {
        $check = KhoV2::find($request->eid);
        if ($check->type == "HD") {
            $kho = KhoV2::where('id', $request->eid)->update([
                // "id_type_car_detail" => $request->etenXe,
                "year" => $request->enam,
                "vin" => $request->evin,
                "frame" => $request->eframe,
                "gps" => $request->egps,
                // "color" => $request->ecolor,                
                "soDonHang" => $request->esoDonHang,
                "soBaoLanh" => $request->esoBaoLanh,
                "ngayNhanXe" => $request->engayNhanXe,
                "ngayDat" => $request->engayDat,
                "nganHang" => $request->enganHang,
                "ghiChu" => $request->eghiChu,
                "viTri" => $request->eviTri
            ]);
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kho xe - Quản lý kho";
            $nhatKy->noiDung = "Cập nhật thông tin xe trong kho. <br/>THÔNG TIN CŨ<br/>Xe: "
            .$check->typeCarDetail->name." VIN: "
            .$check->vin." Số máy: "
            .$check->frame." GPS: "
            .$check->gps." Vị trí: ". $check->viTri. "<br/>THÔNG TIN MỚI<br/>VIN: "
            .$request->evin." Số máy: "
            .$request->eframe." GPS: "
            .$request->egps." Vị trí: "
            .$request->eviTri;
            $nhatKy->save();
        }
        else {
                if ($request->etrangThai == "HD") {
                    $kho = KhoV2::where('id', $request->eid)->update([
                        "id_type_car_detail" => $request->etenXe,
                        "year" => $request->enam,
                        "vin" => $request->evin,
                        "frame" => $request->eframe,
                        "gps" => $request->egps,
                        "color" => $request->ecolor,                        
                        "soDonHang" => $request->esoDonHang,
                        "soBaoLanh" => $request->esoBaoLanh,
                        "ngayNhanXe" => $request->engayNhanXe,
                        "ngayDat" => $request->engayDat,
                        "nganHang" => $request->enganHang,
                        "ghiChu" => $request->eghiChu,
                        "viTri" => $request->eviTri
                    ]);
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Kho xe - Quản lý kho";
                    $nhatKy->noiDung = "Cập nhật thông tin xe trong kho. <br/>THÔNG TIN CŨ<br/>Xe: "
                    .$check->typeCarDetail->name." VIN: "
                    .$check->vin." Số máy: "
                    .$check->frame." GPS: "
                    .$check->gps." Vị trí: ". $check->viTri. "<br/>THÔNG TIN MỚI<br/>VIN: "
                    .$request->evin." Số máy: "
                    .$request->eframe." GPS: "
                    .$request->egps." Vị trí: "
                    .$request->eviTri;
                    $nhatKy->save();
                }
                else {
                    $typeCar = TypeCarDetail::find($request->etenXe);
                    $kho = KhoV2::where('id', $request->eid)->update([
                        "id_type_car_detail" => $request->etenXe,
                        "year" => $request->enam,
                        "vin" => $request->evin,
                        "frame" => $request->eframe,
                        "gps" => $request->egps,
                        "color" => $request->ecolor,                        
                        "type" => $request->etrangThai,
                        "soDonHang" => $request->esoDonHang,
                        "soBaoLanh" => $request->esoBaoLanh,
                        "ngayNhanXe" => $request->engayNhanXe,
                        "ngayDat" => $request->engayDat,
                        "nganHang" => $request->enganHang,
                        "ghiChu" => $request->eghiChu,
                        "viTri" => $request->eviTri
                    ]);
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Kho xe - Quản lý kho";
                    $nhatKy->noiDung = "Cập nhật thông tin xe trong kho. <br/>THÔNG TIN CŨ<br/>Xe: "
                    .$check->typeCarDetail->name." VIN: "
                    .$check->vin." Số máy: "
                    .$check->frame." GPS: "
                    .$check->gps." Vị trí: ". $check->viTri. " Trạng thái: ".$check->type."<br/>THÔNG TIN MỚI<br/>Xe: ".$typeCar->name." VIN: "
                    .$request->evin." Số máy: "
                    .$request->eframe." GPS: "
                    .$request->egps." Vị trí: "
                    .$request->eviTri." Trạng thái: " . $request->etrangThai;
                    $nhatKy->save();
                }
            }
        if($kho) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function updateV2OnlyHD(Request $request) {
        $check = HopDong::where('id_car_kho', $request->eid)->first();
        if ($request->xuatXe == 1 && $check->lead_check == false) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Trưởng phòng chưa duyệt không thể xuất xe!',
                'code' => 200
            ]);
        } elseif ($request->xuatXe == 1 && $check->lead_check == true) {
            if ($request->ngayGiaoXe == null)
            return response()->json([
                'type' => 'info',
                'message' => 'Nhập ngày giao xe!',
                'code' => 200
            ]);
            $temp = KhoV2::where('id', $request->eid)->first();
            // system check
            if ($temp->xuatXe == 1) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Xe đã xuất! Không thể thay đổi ngày giao xe!',
                    'code' => 200
                ]);
            }            
            // -------------
            $kho = KhoV2::where('id', $request->eid)->update([
                "xuatXe" => $request->xuatXe,
                "ngayGiaoXe" => $request->ngayGiaoXe
            ]);
            if($kho) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kế toán - Hợp đồng xe";
                $nhatKy->noiDung = "Cập nhật ngày giao xe<br/>THÔNG TIN CŨ<br/>Ngày giao xe: "
                .$temp->ngayGiaoXe." trạng thái xuất xe(1: Đã xuất; 0:Chưa xuất): ".$temp->xuatXe."<br/>THÔNG TIN MỚI<br/> Ngày giao xe: " . $request->ngayGiaoXe . " và trạng thái xuất xe(1: Đã xuất; 0:Chưa xuất) " . $request->xuatXe;
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã cập nhật!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } elseif ($request->xuatXe == 0) {
            // check system allow change status
            $temp = KhoV2::where('id', $request->eid)->first();
            if ($temp->xuatXe == 1 && !Auth::user()->hasRole('system')) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Bạn không thể hoàn trạng thái xuất xe vui lòng liên hệ quản trị viên!',
                    'code' => 200
                ]);
            }
            // -----------------
            $kho = KhoV2::where('id', $request->eid)->update([
                "xuatXe" => $request->xuatXe,
                "ngayGiaoXe" => null
            ]);
            if($kho) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kế toán - Hợp đồng xe";
                $nhatKy->noiDung = "Cập nhật lại thông tin xuất xe. Cập nhật từ trạng thái xuất xe sang không xuất xe";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã cập nhật!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
    }
   
    // check tồn kho cho sale
    public function getPageTonKho() {
        return view('hopdong.tonkho');
    }

    public function getTonKho() {
        // $result = KhoV2::select('kho_v2.*','t.name as ten')
        // ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        // ->where([
        //     ['kho_v2.type','=', 'HD']
        // ])
        // ->orWhere([
        //     ['kho_v2.type','=', 'STORE']
        // ])
        // ->orderBy('id', 'desc')->get();
        $result = KhoV2::select('kho_v2.*','t.name as ten')
        ->join('type_car_detail as t','kho_v2.id_type_car_detail','=','t.id')
        ->where([
            ['kho_v2.type','=', 'HD'],
            ['kho_v2.xuatXe','=', false],
        ])
        ->orWhere([
            ['kho_v2.type','=', 'STORE'],
            ['kho_v2.xuatXe','=', false],
        ])
        ->orderBy('id', 'desc')
        ->get();
        if($result) {
            return response()->json([
                'message' => 'Đã check tồn kho!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Không thể check tồn kho!',
                'code' => 500
            ]);
        }
    }

    // get report kho
    public function getReport() {
        $typeCar = TypeCar::all();
        // Xử lý 
        $modelGroup = []; 
        $tongStore = 0;
        $tongHD = 0;
        $modelRoot = TypeCar::all();
        foreach($modelRoot as $rowModel) {
            $models = TypeCarDetail::select("*")->where([
                ["isShow","=",true],
                ["id_type_car","=",$rowModel->id]
            ])->get();
            $tongStoreCar = 0;
            $tongHDCar = 0;
            foreach($models as $row) {
                $kho = KhoV2::select('*')->where([
                    ['id_type_car_detail','=',$row->id],
                    ['type','=','STORE']
                ])->get();
                $hd = KhoV2::select('*')->where([
                    ['id_type_car_detail','=',$row->id],
                    ['type','=','HD'],
                    ['xuatXe','=',false]
                ])->get();
                if ($kho) {
                    foreach($kho as $rowKho) {
                        $tongStore++;
                        $tongStoreCar++;
                    }
                }
                if ($hd) {
                    foreach($hd as $rowHD) {
                        $hdXe = HopDong::where('id_car_kho',$rowHD->id)->first();
                        if ($hdXe) {
                            $tongStore++;
                            $tongStoreCar++;
                            $tongHD++;
                            $tongHDCar++;
                        } 
                    }
                }      
            }
            $temp = [];
            $temp = (object) $temp;
            $temp->name = $rowModel->name;
            $temp->tongStore = $tongStoreCar;
            $temp->tongHD = $tongHDCar;
            array_push($modelGroup, $temp);
        }
        // ---------------------
        return view('khoxe.reportv2', ['typecar' => $typeCar, 'modelGroup' => $modelGroup, 'tongStore' => $tongStore, 'tongHD' => $tongHD]);
    }

    public function getReportAll() {
        $result = []; 
        $models = TypeCarDetail::select("*")->where("isShow",true)->orderBy('id_type_car','desc')->get();
        foreach($models as $row) {
            $kho = KhoV2::select('*')->where([
                ['id_type_car_detail','=',$row->id],
                ['type','=','STORE']
            ])->get();
            $hd = KhoV2::select('*')->where([
                ['id_type_car_detail','=',$row->id],
                ['type','=','HD'],
                ['xuatXe','=',false]
            ])->get();
            $temp = [];
            $temp = (object) $temp;
            $temp->name = $row->name;
            $temp->idTypeCarDetail = $row->id;
            if ($kho) {
                $store = [];
                foreach($kho as $rowKho) {
                    $tempKho = [];
                    $tempKho = (object) $tempKho;
                    $tempKho->mauSac = $rowKho->color;
                    $tempKho->isHD = false;
                    $tempKho->soHD = "";
                    $tempKho->ngayKy = "";
                    $tempKho->tenKhach = "";
                    $tempKho->sale = "";
                    $tempKho->tienMat = "";
                    array_push($store, $tempKho);
                }
                $temp->store = $store;
            }
            if ($hd) {
                $hopdong = [];
                foreach($hd as $rowHD) {
                    $tempKho = [];
                    $tempKho = (object) $tempKho;
                    $tempKho->mauSac = $rowHD->color;
                    $hdXe = HopDong::where('id_car_kho',$rowHD->id)->first();
                    if ($hdXe) {
                        $tempKho->isHD = true;
                        $tempKho->soHD = "[".$hdXe->code
                        ."."
                        .$hdXe->carSale->typeCar->code
                        ."/"
                        .\HelpFunction::getDateCreatedAt($hdXe->created_at)."]";
                        $tempKho->ngayKy = \HelpFunction::getDateRevertCreatedAt($hdXe->created_at);
                        $tempKho->tenKhach = $hdXe->guest->name;
                        $tempKho->sale = $hdXe->user->userDetail->surname;
                        $tempKho->tienMat = $hdXe->isTienMat ? "Tiền mặt" : "Ngân hàng";
                        array_push($hopdong, $tempKho);
                    } 
                }
                $temp->hopdong = $hopdong;
            }   
            // if ($checkKho || $checkHD)
            array_push($result, $temp);         
        }
        if ($models) {
            return response()->json([
                'message' => 'Get list successfull!',
                'code' => 200,
                'data' => $result
            ]);    
        } else {
            return response()->json([
                'message' => 'Get fail',
                'code' => 500,
                'data' => null
            ]);    
        }
    }

    public function getReportKho($chose,$ngayfrom,$ngayto) {
        $i = 1;
        $listPo = KhoV2::select('*')->where('type','like','P/O')->get();
        $po = KhoV2::select('*')->where('type','like','P/O')->count();
        $listMap = KhoV2::select('*')->where('type','like','MAP')->get();
        $map = KhoV2::select('*')->where('type','like','MAP')->count();
        $listOrder = KhoV2::select('*')->where('type','like','ORDER')->get();
        $order = KhoV2::select('*')->where('type','like','ORDER')->count();
        $complete = 0;
        $hdky = 0;
        $hdcho = 0;
        $hdhuy = 0;
        $hddaily = 0;
        $listStore = KhoV2::select('*')->where([
            ['type','like','STORE']
        ])->orWhere([
            ['type','like','HD'],
            ['xuatXe','=',false]
        ])->get();
        $store = KhoV2::select('*')->where([
            ['type','like','STORE']
        ])->orWhere([
            ['type','like','HD'],
            ['xuatXe','=',false]
        ])->count();
        $listComplete = KhoV2::select('*')->where('xuatXe',true)->get();
        $completeList = KhoV2::select('*')->where('xuatXe',true)->get();
        foreach($completeList as $row){
            if ((strtotime($row->ngayGiaoXe) >= strtotime($ngayfrom)) 
                &&  (strtotime($row->ngayGiaoXe) <= strtotime($ngayto))) {
                    $complete++;
                }
        }
        $hdkyList = HopDong::select('k.xuatXe','hop_dong.*')
        ->join('kho_v2 as k','k.id','=','hop_dong.id_car_kho')
        ->where([
            ['hop_dong.lead_check','=',true],
            ['hop_dong.lead_check_cancel','=',false],
            ['k.xuatXe','=',false]
        ])->get();
        foreach($hdkyList as $row){
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                    $hdky++;
            }
        }
        $hdchoList = HopDong::select('*')->where([
            ['lead_check','=',true],
            ['hdWait','=',true],
            ['lead_check_cancel','=',false]
        ])->get();
        foreach($hdchoList as $row){
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                    $hdcho++;
            }
        }
        $hdhuyList = HopDong::select('*')->where([
            ['lead_check_cancel','=',true]
        ])->get();
        foreach($hdhuyList as $row){
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                    $hdhuy++;
            }
        }

        $hddailyList = HopDong::select('k.xuatXe','hop_dong.*')
        ->join('kho_v2 as k','k.id','=','hop_dong.id_car_kho')
        ->where([
            ['hop_dong.lead_check','=',true],
            ['hop_dong.lead_check_cancel','=',false],
            ['k.xuatXe','=',false],
            ['hop_dong.hdDaiLy','=',true]
        ])->get();
        foreach($hddailyList as $row){
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                    $hddaily++;
            }
        }

        switch ($chose) {
            case 'ALL':
                {
                    echo "<h4>P/O: <span class='badge badge-secondary'>$po</span></h4>
                    <h4>MAP: <span class='badge badge-secondary'>$map</span> </h4>
                    <h4>ĐẶT HÀNG: <span class='badge badge-secondary'>$order</span> </h4>
                    <h4>TỒN KHO: <span class='badge badge-warning'>$store</span> </h4>
                    <h4>XUẤT XE: <span class='badge badge-success'>$complete</span> </h4>
                    <h4>HỢP ĐỒNG KÝ: <span class='badge badge-primary'>".($hdky - $hddaily)."</span> </h4>
                    <h4>HỢP ĐỒNG KÝ ĐẠI LÝ: <span class='badge badge-primary'>$hddaily</span> </h4>
                    <h4>HỢP ĐỒNG CHỜ: <span class='badge badge-info'>$hdcho</span> </h4>
                    <h4>HỢP ĐỒNG HỦY: <span class='badge badge-danger'>$hdhuy</span> </h4>";
                }
                break;
            case 'PO':
                {
                    echo "<h4>P/O: <span class='badge badge-secondary'>$po</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                          </tr>";
                    foreach($listPo as $row)
                        echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->typeCarDetail->name."</td>
                            <td>".$row->color."</td>
                        </tr>";
                    echo "</table>";    
                }
                break;
            case 'MAP':
                {
                    echo "<h4>MAP: <span class='badge badge-secondary'>$map</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                          </tr>";
                    foreach($listMap as $row)
                        echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->typeCarDetail->name."</td>
                            <td>".$row->color."</td>
                        </tr>";
                    echo "</table>";    
                }    
                break;
            case 'ORDER':
                {
                    echo "<h4>ĐẶT HÀNG: <span class='badge badge-secondary'>$order</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                          </tr>";
                    foreach($listOrder as $row)
                        echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->typeCarDetail->name."</td>
                            <td>".$row->color."</td>
                        </tr>";
                    echo "</table>";    
                }    
                break;
            case 'STORE':
                {
                    echo "<h4>TỒN KHO: <span class='badge badge-secondary'>$store</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                              <th>Vị trí kho</th>
                          </tr>";
                    foreach($listStore as $row)
                        echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->typeCarDetail->name."</td>
                            <td>".$row->color."</td>
                            <td>".$row->viTri."</td>
                        </tr>";
                    echo "</table>";    
                }    
                break;
            case 'COMPLETE':
                {
                    echo "<h4>XUẤT XE: <span class='badge badge-secondary'>$complete</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ngày giao</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                          </tr>";
                    foreach($listComplete as $row) {
                        if ((strtotime($row->ngayGiaoXe) >= strtotime($ngayfrom)) 
                        &&  (strtotime($row->ngayGiaoXe) <= strtotime($ngayto))) {
                            echo "<tr>
                                <td>".$i++."</td>
                                <td>".\HelpFunction::revertDate($row->ngayGiaoXe)."</td>
                                <td>".$row->typeCarDetail->name."</td>
                                <td>".$row->color."</td>
                            </tr>";
                        }
                    }
                    echo "</table>";    
                }    
                break;
            case 'HD':
                {
                    echo "<h4>HỢP ĐỒNG KÝ: <span class='badge badge-secondary'>$hdky</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ngày ký</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                              <th>Sale bán</th>
                              <th>Tiền cọc</th>
                          </tr>";
                    foreach($hdkyList as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                            echo "<tr>
                                <td>".$i++."</td>
                                <td>".\HelpFunction::revertCreatedAt($row->created_at)."</td>
                                <td>".$row->carSale->name."</td>
                                <td>".$row->mau."</td>
                                <td>".$row->user->userDetail->surname."</td>
                                <td>".number_format($row->tienCoc)."</td>
                            </tr>";
                        }
                    }
                    echo "</table>";    
                }    
                break;
            case 'HDWAIT':
                {
                    echo "<h4>HỢP ĐỒNG KÝ CHỜ: <span class='badge badge-secondary'>$hdcho</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ngày ký</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                              <th>Sale bán</th>
                              <th>Tiền cọc</th>
                          </tr>";
                    foreach($hdchoList as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                            echo "<tr>
                                <td>".$i++."</td>
                                <td>".\HelpFunction::revertCreatedAt($row->created_at)."</td>
                                <td>".$row->carSale->name."</td>
                                <td>".$row->mau."</td>
                                <td>".$row->user->userDetail->surname."</td>
                                <td>".number_format($row->tienCoc)."</td>
                            </tr>";
                        }
                    }
                    echo "</table>";    
                }    
                break;
            case 'HDCANCEL':
                {
                    echo "<h4>HỢP ĐỒNG HỦY: <span class='badge badge-secondary'>$hdhuy</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ngày ký</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                              <th>Sale bán</th>
                              <th>Lý do hủy</th>
                              <th>Tiền cọc</th>
                          </tr>";
                    foreach($hdhuyList as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                            echo "<tr>
                                <td>".$i++."</td>
                                <td>".\HelpFunction::revertCreatedAt($row->created_at)."</td>
                                <td>".$row->carSale->name."</td>
                                <td>".$row->mau."</td>
                                <td>".$row->user->userDetail->surname."</td>
                                <td>".$row->lyDoCancel."</td>
                                <td>".number_format($row->tienCoc)."</td>
                            </tr>";
                        }
                    }
                    echo "</table>";    
                }    
                break;
            case 'HDDAILY':
                {
                    echo "<h4>HỢP ĐỒNG KÝ ĐẠI LÝ: <span class='badge badge-secondary'>$hddaily</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ngày ký</th>
                              <th>Thông tin xe</th>
                              <th>Màu sắc</th>
                              <th>Sale bán</th>
                              <th>Tiền cọc</th>
                          </tr>";
                    foreach($hddailyList as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($ngayfrom)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($ngayto))) {
                            echo "<tr>
                                <td>".$i++."</td>
                                <td>".\HelpFunction::revertCreatedAt($row->created_at)."</td>
                                <td>".$row->carSale->name."</td>
                                <td>".$row->mau."</td>
                                <td>".$row->user->userDetail->surname."</td>
                                <td>".number_format($row->tienCoc)."</td>
                            </tr>";
                        }
                    }
                    echo "</table>";     
                }    
                break;
            default:
                break;
        }
    }

     // get report hợp đồng
     public function getReportHopDong() {
        return view('khoxe.baocaohopdong');
    }
}
