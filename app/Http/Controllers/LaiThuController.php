<?php

namespace App\Http\Controllers;

use App\DangKySuDung;
use App\User;
use App\NhatKy;
use App\EventReal;
use App\XeLaiThu;
use App\Mail\TraXe;
use Carbon\Carbon;
use App\Mail\DuyetXeDemoTBP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use Exception;

class LaiThuController extends Controller
{
    public function index()
    {
        $car = XeLaiThu::select('*')->get();
        return view('laithu.car', ['car' => $car]);
    }

    public function store(Request $request)
    {
        $car = new XeLaiThu();
        $car->name = $request->tenXe;
        $car->number_car = $request->bienSo;
        $car->mau = $request->color;
        $car->save();
        if($car) {
            return redirect()->route('laithu.list')->with('succ','Đã thêm xe lái thử');
        } else {
            return redirect()->route('laithu.list')->with('err','Không thể thêm xe lái thử');
        }
    }

    public function destroy(Request $request)
    {
        //
        $car = XeLaiThu::where('id',$request->id)->delete();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản lý xe demo - Quản lý xe";
            $nhatKy->noiDung = "Xóa xe: ";
            $nhatKy->save();
            return response()->json([
                'message' => 'Delete successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function change(Request $request) {
        $check = XeLaiThu::find($request->id);
        if ($check->status == 'DSD' || $check->status == 'S')
            return response()->json([
                'message' => 'Xe đang được sếp/người khác sử dụng không thể chuyển trạng thái!',
                'code' => 200
            ]);
        else {
            $stt = ($check->status == 'T') ? 'DSC' : 'T';
            $car = XeLaiThu::where('id', $request->id)->update([
                'status' => $stt
            ]);
            if($car) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản lý xe demo - Quản lý xe";
                $nhatKy->noiDung = "Chuyển trạng thái xe: ";
                $nhatKy->save();
                return response()->json([
                    'message' => 'Đã chuyển trạng thái xe!',
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

    public function setBoss(Request $request) {
        $check = XeLaiThu::find($request->id);
        if ($check->status == 'DSD' || $check->status == 'DSC')
            return response()->json([
                'message' => 'Xe đang được sử dụng/sửa chữa không thể chuyển trạng thái!',
                'code' => 200
            ]);
        else {
            $car = XeLaiThu::where('id', $request->id)->update([
                'status' => 'S'
            ]);
            if($car) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản lý xe demo - Quản lý xe";
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->noiDung = "Chuyển trạng thái xe cho lãnh đạo sử dụng: ";
                $nhatKy->save();
                return response()->json([
                    'message' => 'Đã chuyển trạng thái xe cho lãnh đạo!',
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

    public function setBlank(Request $request) {
        $car = XeLaiThu::where('id', $request->id)->update([
            'status' => 'T'
        ]);
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản lý xe demo - Quản lý xe";
            $nhatKy->noiDung = "Xác nhận lãnh đạo trả xe: ";
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã chuyển trạng thái xe cho lãnh đạo!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }


    public function showNow(Request $request) {
        $check = XeLaiThu::find($request->id);
        $stt = ($check->active == true) ? false : true;
        $car = XeLaiThu::where('id', $request->id)->update([
            'active' => $stt
        ]);
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản lý xe demo - Quản lý xe";
            $nhatKy->noiDung = "Chuyển trạng thái hiển thị xe: " . $stt;
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã chuyển trạng thái hiển thị xe!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function showReg() {
        $car = XeLaiThu::select("*")->where('active',true)->get();
        $lead = User::all();
        if (Auth::user()->hasRole('system')) {
            $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->get();
            $traXe = DangKySuDung::select('*')->where('allow', true)->orderBy('id', 'DESC')->get();
        } else {
            $reg = DangKySuDung::select('*')->where('id_user_reg', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $traXe = DangKySuDung::select('*')->where([
                ['allow', true],
                ['id_user_reg', Auth::user()->id]
            ])->orderBy('id', 'DESC')->get();
        }
        return view('laithu.reg', ['lead' => $lead,'car' => $car, 'reg' => $reg, 'traXe' => $traXe]);
    }

    public function showPay() {
        $car = XeLaiThu::all();
        $lead = User::all();
        if (Auth::user()->hasRole('system')) {
            $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->get();
            $traXe = DangKySuDung::select('*')->where('allow', true)->orderBy('id', 'DESC')->get();
        } else {
            $reg = DangKySuDung::select('*')->where('id_user_reg', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $traXe = DangKySuDung::select('*')->where([
                ['allow', true],
                ['id_user_reg', Auth::user()->id]
            ])->orderBy('id', 'DESC')->get();
        }
        return view('laithu.pay', ['lead' => $lead,'car' => $car, 'reg' => $reg, 'traXe' => $traXe]);
    }

    public function pay($id) {
        $car = DangKySuDung::find($id);
        if($car) {
            return response()->json([
                'message' => 'Get data successfully!',
                'code' => 200,
                'data' => $car
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function postPay(Request $request) {   
        $reg = DangKySuDung::find($request->_idOff);
        $nguoiYeuCau = User::find($reg->id_user_reg)->userDetail->surname;
        $ngayDi = $reg->time_go . " " . \HelpFunction::revertDate($reg->date_go);
        $ngayTra = Date('H:i d-m-Y');
        $xeDangKy = $reg->xeLaiThu->name. " " .$reg->xeLaiThu->number_car;
        $km = $request->_km;
        $kmXang = $request->_xang;      
          

        $carname = $reg->xeLaiThu->name. " " .$reg->xeLaiThu->number_car;
        $reg->tra_km_current = $request->_km;
        $reg->tra_fuel_current = $request->_xang;
        $str = "";
        if ($request->veSinh == 1)
            $str .= "Vệ sinh: Sạch(" . $request->ghiChuVeSinh . ")";
        else 
            $str .= "Vệ sinh: Dơ(" . $request->ghiChuVeSinh . ")";

        if ($request->benNgoai == 1)
            $str .= "; Bên ngoài: Bình thường(" . $request->ghiChuBenNgoai . ")";
        else 
            $str .= "; Bên ngoài: Trầy(" . $request->ghiChuBenNgoai . ")";
        $reg->tra_car_status = $str;

        $hoSo = "";
        if ($request->_caVet == "on") {
            $hoSo .= "Cà vẹt (giấy đi đường); ";
        }
        if ($request->_dangKiem == "on") {
            $hoSo .= "Đăng kiểm; ";
        }
        if ($request->_BHTX == "on") {
            $hoSo .= "BH thân xe; ";
        }
        if ($request->_BHTNDS == "on") {
            $hoSo .= "BH TNNS; ";
        }
        if ($request->_chiaKhoaChinh == "on") {
            $hoSo .= "Chìa khóa chính; ";
        }
        if ($request->_chiaKhoaPhu == "on") {
            $hoSo .= "Chìa khóa phụ; ";
        }

        $reg->hoSoVe = $hoSo;

        $reg->request_tra = true;
        if ($reg->date_return == null)
            $reg->date_return = Date('H:i d-m-Y');
        $reg->save();
        if($reg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản lý xe demo - trả xe";
            $nhatKy->noiDung = "Gửi yêu cầu trả xe: " . $carname;
            $nhatKy->save();

            //---
                $jsonString = file_get_contents('upload/cauhinh/app.json');
                $data = json_decode($jsonString, true); 
                Mail::to($data['emailTraXe'])->send(new TraXe(['Phòng hành chính',$nguoiYeuCau,$ngayDi,$ngayTra,$xeDangKy,$km,$kmXang,$str,$hoSo]));
            //---

            return redirect()->route('laithu.pay')->with('succ','Đã gửi yêu cầu trả xe!');
        } else {
            return redirect()->route('laithu.pay')->with('err','Không thể gửi yêu cầu trả xe!');
        }
    }

    public function postReg(Request $request) {
        $userDuyetEmail = User::find($request->tbpCheck);
        $emailDuyet = ($userDuyetEmail) ? $userDuyetEmail->email : "";
        $nguoiDuyet = ($userDuyetEmail) ? $userDuyetEmail->userDetail->surname : "";
        $nguoiYeuCau = User::find(Auth::user()->id)->userDetail->surname;
        $lyDo = $request->lyDo;
        $ngayDangKy = Date('d-m-Y');
        $km = $request->km;
        $kmXang = $request->xang;
        $batDau = $request->timeHourGo . " " . \HelpFunction::revertDate($request->timeGo);
        $ketThuc = $request->timeReturn . " " . \HelpFunction::revertDate($request->dateDuKien);

        $check = XeLaiThu::find($request->xe);
        $str = "";
        if ($check->status == 'T') {
            $reg = new DangKySuDung();
            $reg->id_user_reg = Auth::user()->id;
            $reg->id_xe_lai_thu = $request->xe;
            $reg->lyDo = $request->lyDo;
            $reg->km_current = $request->km;
            $reg->fuel_current = $request->xang;
            if ($request->veSinh == 1)
                $str .= "Vệ sinh: Sạch(" . $request->ghiChuVeSinh . ")";
            else 
                $str .= "Vệ sinh: Dơ(" . $request->ghiChuVeSinh . ")";

            if ($request->benNgoai == 1)
                $str .= "; Bên ngoài: Bình thường(" . $request->ghiChuBenNgoai . ")";
            else 
                $str .= "; Bên ngoài: Trầy(" . $request->ghiChuBenNgoai . ")";

            $reg->car_status = $str;
            $reg->time_go = $request->timeHourGo;
            $reg->date_go = $request->timeGo;
            $reg->date_duKien = $request->timeReturn . " " . \HelpFunction::revertDate($request->dateDuKien);
            if ($request->fuelRequest == 'on') {
                $reg->fuel_request = true;
                $reg->fuel_type = $request->fuelType;
                $reg->fuel_num = $request->fuelNum;
                $reg->fuel_lyDo = $request->fuelLyDo;
                $reg->id_user_check = $request->leadCheck;
            }
            $reg->id_lead_check = $request->tbpCheck;
            try {
                $reg->save();
            } catch(Exception $e) {
                return redirect()->route('laithu.reg')->with('err','Không thể đăng ký xe lái thử có lỗi trùng lắp');
            }
            if($reg) {
                $check = DangKySuDung::find($reg->id);
                $carname = $check->xeLaiThu->name. " " .$check->xeLaiThu->number_car;
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản lý xe demo - đăng ký xe";
                $nhatKy->noiDung = "Gửi yêu cầu sử dụng xe: " . $carname;
                $nhatKy->save();

                //---
                $jsonString = file_get_contents('upload/cauhinh/app.json');
                $data = json_decode($jsonString, true); 
                if ($userDuyetEmail)
                    Mail::to($emailDuyet)->send(new DuyetXeDemoTBP([$nguoiDuyet,$nguoiYeuCau,$ngayDangKy,$carname,$lyDo,$km,$kmXang,$str,$batDau,$ketThuc]));
                Mail::to($data['emailDuyetXe'])->send(new DuyetXeDemoTBP(['Phòng hành chính',$nguoiYeuCau,$ngayDangKy,$carname,$lyDo,$km,$kmXang,$str,$batDau,$ketThuc]));
                //---
                return redirect()->route('laithu.reg')->with('succ','Đã đăng ký xe lái thử');
            } else {
                return redirect()->route('laithu.reg')->with('err','Không thể đăng ký xe lái thử');
            }
        } elseif($check->status == 'DSC') {
            return redirect()->route('laithu.reg')->with('err','Xe này đăng trong tình trạng sửa chữa không thể đăng ký sử dụng');
        } elseif($check->status == 'S') {
            return redirect()->route('laithu.reg')->with('err','Xe này sếp đang sử dụng nên không thể đăng ký sử dụng');
        } else {
            return redirect()->route('laithu.reg')->with('err','Xe này đang được sử dụng bởi ' .$check->user->userDetail->surname. ' không thể đăng ký');
        }
    }

    public function delReg(Request $request)
    {
        $check = DangKySuDung::find($request->id);
        $carname = $check->xeLaiThu->name. " " .$check->xeLaiThu->number_car;
        if ($check->allow == true) {
            return response()->json([
                'message' => 'Xe đã được duyệt, không thể xóa!',
                'code' => 200
            ]);
        }
        if ($check->fuel_allow == true) {
            return response()->json([
                'message' => 'Yêu cầu cấp xăng đã được duyệt, không thể xóa!',
                'code' => 200
            ]);
        } else {
            $car = DangKySuDung::where('id',$request->id)->delete();
            if($car) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản lý xe demo - đăng ký xe";
                $nhatKy->noiDung = "Xóa đề nghị sử dụng xe: " . $carname;
                $nhatKy->save();
                return response()->json([
                    'message' => 'Đã xóa đề nghị sử dụng xe!',
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

    public function showDuyet() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);
        $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        $traXe = DangKySuDung::select('*')->where('request_tra', true)->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        return view('laithu.duyet', ['reg' => $reg, 'traXe' => $traXe]);
    }

    public function showDuyetTBP() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);
        $reg = DangKySuDung::select('*')->where('id_lead_check', Auth::user()->id)->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        return view('laithu.duyettbp', ['reg' => $reg]);
    }

    public function showDuyetPay() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);
        $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        $traXe = DangKySuDung::select('*')->where('request_tra', true)->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        return view('laithu.duyettra', ['reg' => $reg, 'traXe' => $traXe]);
    }

    public function showCapXang() {
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $reg = DangKySuDung::select('*')->where('fuel_request', true)->orderBy('id', 'DESC')->get();
        } elseif (Auth::user()->hasRole('lead')) {
            $reg = DangKySuDung::select('*')->where([
             ['fuel_request', true],
             ['id_user_check',Auth::user()->id]
            ])->orderBy('id', 'DESC')->get();
        }
        return view('laithu.capxang', ['reg' => $reg]);
    }

    public function getPayId($id) {
        $getReg = DangKySuDung::find($id);
         if ($getReg) {
                return response()->json([
                    'type' => 'success',
                    'message' => ' Đã lấy thông tin duyệt!',
                    'code' => 200,
                    'data' => $getReg
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' Lỗi! Máy chủ!',
                    'code' => 500
                ]);
            }
    }  

    public function allowLaiThu(Request $request) {
        $regInfo = DangKySuDung::find($request->_id);
        $name = $regInfo->user->userDetail->surname;
        $carname = $regInfo->xeLaiThu->name. " " .$regInfo->xeLaiThu->number_car;
        $check = XeLaiThu::find($regInfo->id_xe_lai_thu);
        $hoSo = "";
        if ($request->_caVet == "on") {
            $hoSo .= "Cà vẹt (giấy đi đường); ";
        }
        if ($request->_dangKiem == "on") {
            $hoSo .= "Đăng kiểm; ";
        }
        if ($request->_BHTX == "on") {
            $hoSo .= "BH thân xe; ";
        }
        if ($request->_BHTNDS == "on") {
            $hoSo .= "BH TNNS; ";
        }
        if ($request->_chiaKhoaChinh == "on") {
            $hoSo .= "Chìa khóa chính; ";
        }
        if ($request->_chiaKhoaPhu == "on") {
            $hoSo .= "Chìa khóa phụ; ";
        }

        if ($check->mau != 'Xe tải' && Auth::user()->hasRole('car')) {
            if ($check->status == 'T') {
                $reg = DangKySuDung::where('id', $request->_id)->update([
                    "allow" => true,
                    "hoSoDi" => $hoSo
                ]);

                $upCar = XeLaiThu::where('id', $regInfo->id_xe_lai_thu)->update([
                    'id_user_use' => $regInfo->id_user_reg,
                    'status' => 'DSD',
                    'duKien' => $regInfo->date_duKien
                ]);

                $eventReal = new EventReal;
                $eventReal->name = "Allow Demo";
                $eventReal->save();

                if($reg && $upCar) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->ghiChu = Carbon::now();
                    $nhatKy->chucNang = "Quản lý xe demo - duyệt đăng ký";
                    $nhatKy->noiDung = "Phê duyệt sử dụng xe lái thử<br/>Người yêu cầu: ".$name."<br/>Xe: " . $carname;
                    $nhatKy->save();
                    return response()->json([
                        'message' => 'Đã phê duyệt sử dụng xe lái thử!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } elseif($check->status == 'DSC') {
                return response()->json([
                    'message' => 'Xe đang được sửa chữa!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Xe đang được sử dụng!',
                    'code' => 200
                ]);
            }
        } 
     
        if ($check->mau == 'Xe tải' && Auth::user()->hasRole('tpdv')) {
            if ($check->status == 'T') {
                $reg = DangKySuDung::where('id', $request->_id)->update([
                    "allow" => true,
                    "hoSoDi" => $hoSo
                ]);

                $upCar = XeLaiThu::where('id', $regInfo->id_xe_lai_thu)->update([
                    'id_user_use' => $regInfo->id_user_reg,
                    'status' => 'DSD',
                    'duKien' => $regInfo->date_duKien
                ]);

                if($reg && $upCar) {
                    return response()->json([
                        'message' => 'Đã phê duyệt sử dụng xe lái thử!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            } elseif($check->status == 'DSC') {
                return response()->json([
                    'message' => 'Xe đang được sửa chữa!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Xe đang được sử dụng!',
                    'code' => 200
                ]);
            }
        }

        return response()->json([
            'message' => 'Không có quyền duyệt xe này!',
            'code' => 200
        ]);      
    }

    public function allowLaiThuTBP(Request $request) {
        $regInfo = DangKySuDung::find($request->id);
        $name = $regInfo->user->userDetail->surname;
        $car = $regInfo->xeLaiThu->name. " " .$regInfo->xeLaiThu->number_car;
        $regInfo->id_lead_check_status = true;
        $regInfo->save();
           if($regInfo) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản lý xe demo - duyệt đăng ký (TBP)";
                $nhatKy->noiDung = "Trưởng bộ phận phê duyệt sử dụng xe lái thử <br/>Người đề nghị: ".$name." <br/>Xe: " . $car;
                $nhatKy->save();
                return response()->json([
                    'message' => 'Đã phê duyệt sử dụng xe lái thử!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
    }

    public function approve(Request $request) {
        $car = DangKySuDung::find($request->id);
        $name = $car->user->userDetail->surname;
        $carname = $car->xeLaiThu->name. " " .$car->xeLaiThu->number_car;
        $check = $car->xeLaiThu;
         if ($check->mau != 'Xe tải' && Auth::user()->hasRole('car')) {
             $car->tra_allow = true;
            $car->request_tra = true;
            $car->save();
            $upCar = XeLaiThu::where('id', $car->id_xe_lai_thu)->update([
                'status' => 'T',
                'duKien' => null
            ]);

            $eventReal = new EventReal;
            $eventReal->name = "Allow Demo";
            $eventReal->save();

            if($car && $upCar) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản lý xe demo - duyệt trả";
                $nhatKy->noiDung = "Duyệt trả xe<br>Người trả: ".$name."<br/>Xe: " .$carname;
                $nhatKy->save();
                return response()->json([
                    'message' => 'Nhận xe thành công',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
         }

         if ($check->mau == 'Xe tải' && Auth::user()->hasRole('tpdv')) {
             $car->tra_allow = true;
            $car->request_tra = true;
            $car->save();
            $upCar = XeLaiThu::where('id', $car->id_xe_lai_thu)->update([
                'status' => 'T',
                'duKien' => null
            ]);

            $eventReal = new EventReal;
            $eventReal->name = "Allow Demo";
            $eventReal->save();

            if($car && $upCar) {
                return response()->json([
                    'message' => 'Nhận xe thành công',
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
            'message' => 'Không có quyền nhận trả xe này!',
            'code' => 200
         ]);   
        
    }

    public function getStatus() {
        $car = XeLaiThu::all();
        return view('laithu.status',['car' => $car]);
    }

    public function showQR($id) {
        $car = DangKySuDung::find($id);
        if ($car !== null && $car->allow == true) {
            return view('showqr', ['car' => $car]);
        } else {
            return "<h2>LỖI KHÔNG TỒN TẠI</h2>";
        }
    }

    public function allowCapXang(Request $request) {
        $regInfo = DangKySuDung::where('id', $request->id)->first();
        $name = $regInfo->user->userDetail->surname;
        $carname = $regInfo->xeLaiThu->name. " " .$regInfo->xeLaiThu->number_car;
        $car = DangKySuDung::where('id', $request->id)->update([
           'fuel_allow' => true
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Allow Demo";
        $eventReal->save();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Hành chính - duyệt cấp nhiên liệu";
            $nhatKy->noiDung = "Duyệt đề nghị cấp nhiên liệu<br/>Người yêu cầu: ".$name."<br/>Xe: ".$carname;
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã duyệt đề nghị cấp nhiên liệu',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function cancelCapXang(Request $request) {
        $regInfo = DangKySuDung::where('id', $request->id)->first();
        $name = $regInfo->user->userDetail->surname;
        $carname = $regInfo->xeLaiThu->name. " " .$regInfo->xeLaiThu->number_car;
        $car = DangKySuDung::where('id', $request->id)->update([
           'fuel_allow' => false,
           'fuel_request' => false
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Allow Demo";
        $eventReal->save();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Hành chính - duyệt cấp nhiên liệu";
            $nhatKy->noiDung = "Không duyệt/Hủy đề nghị cấp nhiên liệu<br/>Người yêu cầu: ".$name."<br/>Xe: ".$carname;
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã hủy đề nghị cấp nhiên liệu',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }


    public function leadAllowCapXang(Request $request) {
        $regInfo = DangKySuDung::where('id', $request->id)->first();
        $name = $regInfo->user->userDetail->surname;
        $carname = $regInfo->xeLaiThu->name. " " .$regInfo->xeLaiThu->number_car;
        $car = DangKySuDung::where('id', $request->id)->update([
            'lead_check' => true
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Allow Demo";
        $eventReal->save();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Hành chính - duyệt cấp nhiên liệu";
            $nhatKy->noiDung = "Trưởng bộ phận duyệt đề nghị cấp nhiên liệu<br/>Người yêu cầu: ".$name."<br/>Xe: ".$carname;
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã duyệt đề nghị cấp xăng',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function inXang($id) {
        $car = DangKySuDung::find($id);
        $tbp = User::find($car->id_user_check)->userDetail->surname;
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->chucNang = "Hành chính - đề nghị cấp nhiên liệu";
        $nhatKy->noiDung = "In phiếu cấp nhiên liệu";
        $nhatKy->save();
        return view('laithu.in', ['car' => $car, 'content' => $car, 'tbp' => $tbp]);
    }

    public function getEdit(Request $request) {
        $xe = XeLaiThu::find($request->id);
        if($xe) {
            return response()->json([
                'type' => "success",
                'message' => 'Đã load dữ liệu',
                'code' => 200,
                'data' => $xe
            ]);
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function update(Request $request){
        $xe = XeLaiThu::find($request->idCar);
        $name = $xe->name;
        $num = $xe->number_car;
        $mau = $xe->mau;
        $xe->name = $request->etenXe;
        $xe->number_car= $request->ebienSo;
        $xe->mau = $request->ecolor;
        $xe->save();
        if($xe) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản lý xe demo";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Cập nhật thông tin xe từ <br/>$name sang "
            .$request->etenXe."<br/>$num sang "
            .$request->ebienSo."<br/>$mau sang "
            .$request->ecolor;
            $nhatKy->save();
            return response()->json([
                'type' => "success",
                'message' => 'Đã cập nhật thông tin xe',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }
}
