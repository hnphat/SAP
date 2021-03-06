<?php

namespace App\Http\Controllers;

use App\CarSale;
use App\NhatKy;
use App\KhoV2;
use App\HopDong;
use App\TypeCarDetail;
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
            $nhatKy->chucNang = "Kho xe - Qu???n l?? kho";
            $nhatKy->noiDung = "Nh???p xe ".$chiTietXe->name." v??o kho tr???ng th??i xe " . $request->trangThai;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => '???? th??m!',
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
            $nhatKy->chucNang = "Kho xe - Qu???n l?? kho";
            $nhatKy->noiDung = "X??a xe trong kho. TH??NG TIN XE X??A<br/>Xe: "
            .$temp->typeCarDetail->name." VIN: "
            .$temp->vin." S??? m??y: "
            .$temp->frame." GPS: "
            .$temp->gps." Tr???ng th??i: ". $temp->type;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => '???? x??a!',
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
                'message' => '???? load d??? li???u!',
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
            $nhatKy->chucNang = "Kho xe - Qu???n l?? kho";
            $nhatKy->noiDung = "C???p nh???t th??ng tin xe trong kho. <br/>TH??NG TIN C??<br/>Xe: "
            .$check->typeCarDetail->name." VIN: "
            .$check->vin." S??? m??y: "
            .$check->frame." GPS: "
            .$check->gps." V??? tr??: ". $check->viTri. "<br/>TH??NG TIN M???I<br/>VIN: "
            .$request->evin." S??? m??y: "
            .$request->eframe." GPS: "
            .$request->egps." V??? tr??: "
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
                    $nhatKy->chucNang = "Kho xe - Qu???n l?? kho";
                    $nhatKy->noiDung = "C???p nh???t th??ng tin xe trong kho. <br/>TH??NG TIN C??<br/>Xe: "
                    .$check->typeCarDetail->name." VIN: "
                    .$check->vin." S??? m??y: "
                    .$check->frame." GPS: "
                    .$check->gps." V??? tr??: ". $check->viTri. "<br/>TH??NG TIN M???I<br/>VIN: "
                    .$request->evin." S??? m??y: "
                    .$request->eframe." GPS: "
                    .$request->egps." V??? tr??: "
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
                    $nhatKy->chucNang = "Kho xe - Qu???n l?? kho";
                    $nhatKy->noiDung = "C???p nh???t th??ng tin xe trong kho. <br/>TH??NG TIN C??<br/>Xe: "
                    .$check->typeCarDetail->name." VIN: "
                    .$check->vin." S??? m??y: "
                    .$check->frame." GPS: "
                    .$check->gps." V??? tr??: ". $check->viTri. " Tr???ng th??i: ".$check->type."<br/>TH??NG TIN M???I<br/>Xe: ".$typeCar->name." VIN: "
                    .$request->evin." S??? m??y: "
                    .$request->eframe." GPS: "
                    .$request->egps." V??? tr??: "
                    .$request->eviTri." Tr???ng th??i: " . $request->etrangThai;
                    $nhatKy->save();
                }
            }
        if($kho) {
            return response()->json([
                'type' => 'success',
                'message' => '???? c???p nh???t!',
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
                'message' => 'Tr?????ng ph??ng ch??a duy???t kh??ng th??? xu???t xe!',
                'code' => 200
            ]);
        } elseif ($request->xuatXe == 1 && $check->lead_check == true) {
            if ($request->ngayGiaoXe == null)
            return response()->json([
                'type' => 'info',
                'message' => 'Nh???p ng??y giao xe!',
                'code' => 200
            ]);
            $temp = KhoV2::where('id', $request->eid)->first();
            // system check
            if ($temp->xuatXe == 1) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Xe ???? xu???t! Kh??ng th??? thay ?????i ng??y giao xe!',
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
                $nhatKy->chucNang = "K??? to??n - H???p ?????ng xe";
                $nhatKy->noiDung = "C???p nh???t ng??y giao xe<br/>TH??NG TIN C??<br/>Ng??y giao xe: "
                .$temp->ngayGiaoXe." tr???ng th??i xu???t xe(1: ???? xu???t; 0:Ch??a xu???t): ".$temp->xuatXe."<br/>TH??NG TIN M???I<br/> Ng??y giao xe: " . $request->ngayGiaoXe . " v?? tr???ng th??i xu???t xe(1: ???? xu???t; 0:Ch??a xu???t) " . $request->xuatXe;
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => '???? c???p nh???t!',
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
                    'message' => 'B???n kh??ng th??? ho??n tr???ng th??i xu???t xe vui l??ng li??n h??? qu???n tr??? vi??n!',
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
                $nhatKy->chucNang = "K??? to??n - H???p ?????ng xe";
                $nhatKy->noiDung = "C???p nh???t l???i th??ng tin xu???t xe. C???p nh???t t??? tr???ng th??i xu???t xe sang kh??ng xu???t xe";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => '???? c???p nh???t!',
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
   
    // check t???n kho cho sale
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
                'message' => '???? check t???n kho!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Kh??ng th??? check t???n kho!',
                'code' => 500
            ]);
        }
    }

    // get report kho
    public function getReport() {
        return view('khoxe.report');
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
                    <h4>?????T H??NG: <span class='badge badge-secondary'>$order</span> </h4>
                    <h4>T???N KHO: <span class='badge badge-warning'>$store</span> </h4>
                    <h4>XU???T XE: <span class='badge badge-success'>$complete</span> </h4>
                    <h4>H???P ?????NG K??: <span class='badge badge-primary'>".($hdky - $hddaily)."</span> </h4>
                    <h4>H???P ?????NG K?? ?????I L??: <span class='badge badge-primary'>$hddaily</span> </h4>
                    <h4>H???P ?????NG CH???: <span class='badge badge-info'>$hdcho</span> </h4>
                    <h4>H???P ?????NG H???Y: <span class='badge badge-danger'>$hdhuy</span> </h4>";
                }
                break;
            case 'PO':
                {
                    echo "<h4>P/O: <span class='badge badge-secondary'>$po</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
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
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
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
                    echo "<h4>?????T H??NG: <span class='badge badge-secondary'>$order</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
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
                    echo "<h4>T???N KHO: <span class='badge badge-secondary'>$store</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
                              <th>V??? tr?? kho</th>
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
                    echo "<h4>XU???T XE: <span class='badge badge-secondary'>$complete</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ng??y giao</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
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
                    echo "<h4>H???P ?????NG K??: <span class='badge badge-secondary'>$hdky</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ng??y k??</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
                              <th>Sale b??n</th>
                              <th>Ti???n c???c</th>
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
                    echo "<h4>H???P ?????NG K?? CH???: <span class='badge badge-secondary'>$hdcho</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ng??y k??</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
                              <th>Sale b??n</th>
                              <th>Ti???n c???c</th>
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
                    echo "<h4>H???P ?????NG H???Y: <span class='badge badge-secondary'>$hdhuy</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ng??y k??</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
                              <th>Sale b??n</th>
                              <th>L?? do h???y</th>
                              <th>Ti???n c???c</th>
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
                    echo "<h4>H???P ?????NG K?? ?????I L??: <span class='badge badge-secondary'>$hddaily</span></h4>
                    <table class='table table-striped table-bordered'>
                          <tr>
                              <th>STT</th>
                              <th>Ng??y k??</th>
                              <th>Th??ng tin xe</th>
                              <th>M??u s???c</th>
                              <th>Sale b??n</th>
                              <th>Ti???n c???c</th>
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

     // get report h???p ?????ng
     public function getReportHopDong() {
        return view('khoxe.baocaohopdong');
    }
}
