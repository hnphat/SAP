<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\NhatKy;
use App\DeNghiCapXang;
use App\EventReal;
use App\HopDong;
use App\KhoV2;
use App\XeLaiThu;
use Carbon\Carbon;
use App\Mail\DuyetCapXangTBP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class DeNghiCapXangController extends Controller
{
    //
    public function showCapXang() {
        $lead = User::all();
        // if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('hcns'))
        //     $deNghi = DeNghiCapXang::select("*")->orderBy('id', 'DESC')->take(10)->get();
        // else    
        //     $deNghi = DeNghiCapXang::where('id_user', Auth::user()->id)->orderBy('id', 'DESC')->get();
        // return view('capxang.denghiv2', ['lead' => $lead, 'deNghi' => $deNghi]);      
        return view('capxang.denghiv2', ['lead' => $lead]);
    }

    public function postDeNghi(Request $request) {
        $userDuyetEmail = User::find($request->leadCheck);
        $emailDuyet = ($userDuyetEmail) ? $userDuyetEmail->email : "";
        $nguoiDuyet = ($userDuyetEmail) ? $userDuyetEmail->userDetail->surname : "";
        $nguoiYeuCau = User::find(Auth::user()->id)->userDetail->surname;
        $xeDangKy = $request->loaiXe . " " . $request->bienSo;
        switch ($request->capChoXe) {
            case 1:
                $lyDo = "Xe mới (lưu kho/showroom)";
                break;   
            case 2:
                $lyDo = "Xe lái thử";
                break;   
            case 3:
                $lyDo = "Xe cứu hộ";
                break;
            case 4:
                $lyDo = "Xe dịch vụ";
                break;
            case 5:
                $lyDo = "Xe bảo dưỡng lưu động";
                break;
            case 6:
                $lyDo = "Xe công tác";
                break;
            case 7:
                $lyDo = "Xe đi thị trường";
                break;
            case 8:
                $lyDo = "Xe sự kiện";
                break;          
            default:
                $lyDo = "";
                break;
        }
        $nhienLieu = $request->loaiNhienLieu;
        $soLit = $request->soLit;
        $khach = $request->khachHang;
        $ghiChu = $request->ghiChu;
        $ngayDangKy = Date('d-m-Y');

        $deNghi = new DeNghiCapXang;
        $deNghi->id_user = Auth::user()->id;
        $deNghi->fuel_type = $request->loaiNhienLieu;
        $deNghi->fuel_num = $request->soLit;
        $deNghi->fuel_car = $request->loaiXe;
        $deNghi->fuel_guest = $request->khachHang;
        $deNghi->fuel_frame = $request->bienSo;
        $deNghi->fuel_lyDo = $lyDo;
        $deNghi->fuel_ghiChu = $request->ghiChu;
        $deNghi->fuel_km = $request->km;
        $deNghi->lead_id = $request->leadCheck;
        $deNghi->save();
        // $eventReal = new EventReal;
        // $eventReal->name = "Cá nhân làm đề nghị";
        // $eventReal->save();
        if ($deNghi) {
            $code = $deNghi->id;
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Đề nghị cấp nhiên liệu";
            $nhatKy->noiDung = "Thêm đề nghị cấp nhiên liệu loại xe: ".$request->loaiXe." <br/>khách hàng: "
            .$request->khachHang." <br/>biển số: ".$request->bienSo." <br/>lý do cấp: " . $request->lyDoCap;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();

            //-----
            $jsonString = file_get_contents('upload/cauhinh/app.json');
            $data = json_decode($jsonString, true); 
                // if ($userDuyetEmail)
                //     Mail::to($emailDuyet)->send(new DuyetCapXangTBP([$nguoiDuyet,$nguoiYeuCau,$code,$ngayDangKy,$xeDangKy,$lyDo,$nhienLieu,$soLit,$khach,$ghiChu]));
                // Mail::to($data['emailNhienLieu'])->send(new DuyetCapXangTBP(['Phòng hành chính',$nguoiYeuCau,$code,$ngayDangKy,$xeDangKy,$lyDo,$nhienLieu,$soLit,$khach,$ghiChu]));
            //-----

             return response()->json([
                'message' => ' Đã gửi thông tin đề nghị!',
                'code' => 200
             ]);
        } else {
            return response()->json([
                'message' => 'Error!',
                'code' => 500
            ]);
        }
    }

    public function del(Request $request) {
        $deNghi = DeNghiCapXang::find($request->id);
        $temp = $deNghi;
        $deNghi->delete();
        $eventReal = new EventReal;
        $eventReal->name = "Cá nhân xóa đề nghị";
        $eventReal->save();
        if($deNghi) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Đề nghị cấp nhiên liệu";
            $nhatKy->noiDung = "Xóa đề nghị cấp nhiên liệu loại xe: ".$temp->fuel_car." <br/>khách hàng: "
            .$temp->fuel_guest." <br/>biển số: ".$temp->fuel_frame." <br/>lý do cấp: " . $temp->fuel_lyDo;
            $nhatKy->ghiChu = Carbon::now();
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

    public function showDuyetCapXang() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns'))
            $deNghi = DeNghiCapXang::select('*')->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        elseif (Auth::user()->hasRole('lead'))   
            $deNghi = DeNghiCapXang::where('lead_id', Auth::user()->id)->orderBy('id', 'DESC')->take($data["maxRecordApply"])->get();
        return view('capxang.duyet', ['deNghi' => $deNghi]);
    }

    public function allowCapXang(Request $request) {
        $car = DeNghiCapXang::where('id', $request->id)->update([
           'fuel_allow' => true
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Hành chính duyệt cấp xăng";
        $eventReal->save();
        if($car) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Phê duyệt đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
            $nhatKy->ghiChu = Carbon::now();
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

    public function kiemTraCapXang(Request $request) {
        $car = DeNghiCapXang::find($request->id);
        $bienSo = $car->fuel_frame;
        $checkFist = DeNghiCapXang::select("*")->where([
            ["fuel_frame", "like", "%". $bienSo . "%"],
            ["id", "!=", $request->id],
            ["fuel_allow","=",true]
        ])->orderBy("id", "desc")->exists();
        if ($checkFist) {
            $check = DeNghiCapXang::select("*")->where([
                ["fuel_frame", "like", "%". $bienSo . "%"],
                ["id", "!=", $request->id],
                ["fuel_allow","=",true]
            ])->orderBy("id", "desc")->first();
            $check->ngayNew = $check->created_at ? \HelpFunction::revertCreatedAt($check->created_at) : "Không có";
            $check->truongBP = $check->userLead->userDetail->surname;
            if($check) {           
                return response()->json([
                    'message' => 'Đã kiểm tra đề nghị cấp xăng',
                    'code' => 200,
                    'data' => $check
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => $check
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500,
                'data' => null
            ]);
        }        
    }

    public function cancelCapXang(Request $request) {
        $car = DeNghiCapXang::where('id', $request->id)->delete();
        $eventReal = new EventReal;
        $eventReal->name = "HÀnh chính hủy cấp xăng";
        $eventReal->save();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Hủy đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                'message' => 'Đã hủy đề nghị cấp xăng',
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
        $deNghi = DeNghiCapXang::find($id);
        if ($deNghi->lead_id !== null)
            $tbp = User::find($deNghi->lead_id)->userDetail->surname;
        else 
            $tbp = "";
        $deNghi->printed = true;
        $deNghi->save();
        return view('capxang.in', ['car' => $deNghi, 'tbp' => $tbp]);
    }

    public function leadAllowCapXang(Request $request) {
        $car = DeNghiCapXang::where('id', $request->id)->update([
            'lead_check' => true
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Trưởng bộ phận duyệt";
        $eventReal->save();
        if($car) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Phê duyệt đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
            $nhatKy->ghiChu = Carbon::now();
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

    public function getXeLuuKho(Request $request) {
        $arr = [];

        $result = KhoV2::select("*")->where([
            ["xuatXe","=",false],
            ["type","=","HD"]
        ])->orWhere([
            ["xuatXe","=",false],
            ["type","=","STORE"]
        ])->get();
        foreach ($result as $row) {
           $temp = (object)[];
           $temp->soKhung = $row->vin;
           $temp->tenXe = $row->typeCarDetail->name;
           $temp->lyDo = "";
           array_push($arr,$temp);
        }
        if($result) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy thông tin',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getXeLaiThu(Request $request) {
        $arr = [];

        $result = XeLaiThu::select("*")->where([
            ["active","=",true],
            ["status","!=","S"]
        ])->get();

        foreach ($result as $row) {
           $temp = (object)[];
           $temp->soKhung = $row->number_car;
           $temp->tenXe = $row->name;
           $temp->mauXe = $row->mau;
           $temp->lyDo = "";
           array_push($arr,$temp);
        }
        if($result) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy thông tin',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getXeLaiThuMore(Request $request) {
        $arr = [];

        $result = XeLaiThu::select("*")->where([
            ["active","=",true]
        ])->get();

        foreach ($result as $row) {
           $temp = (object)[];
           $temp->soKhung = $row->number_car;
           $temp->tenXe = $row->name;
           $temp->mauXe = $row->mau;
           $temp->lyDo = "";
           array_push($arr,$temp);
        }
        if($result) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy thông tin',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getXeHopDong(Request $request) {
        $arr = [];
        $result = HopDong::select('*')->where([
            ['id_user_create','=', Auth::user()->id],
            ['requestCheck','=',true],
            ['admin_check','=',true],
            ['lead_check','=',true],
            ['id_car_kho','!=',null],
        ])->orderby('id','desc')->get();
        foreach ($result as $row) {
            if($row->code == 0) 
                $code = "";
            else
                $code = "[HĐ: ".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."/HĐMB-PA]";
           $temp = (object)[];
           $temp->id = $row->id;
           $temp->thongTinKhachHang = $code . "; KH: ".$row->guest->name;
           array_push($arr,$temp);
        }
        if($result) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy danh sách hợp đồng xe',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getXeHopDongChiTiet(Request $request) {
        $arr = [];
        $row = HopDong::find($request->id);
        if($row) {        
            if($row->code == 0) 
                $code = "";
            else
                $code = "[HĐ: ".$row->code.".".$row->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($row->created_at)."/HĐMB-PA]";    
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy thông tin hợp đồng',
                'code' => 200,
                'thongtinxe' => $row->carSale->typeCar->name,
                'sokhung' => KhoV2::find($row->id_car_kho)->vin,
                'thongtinkhachhang' => $code . "; KH: ".$row->guest->name."; Điện thoại: ".$row->guest->phone."; Địa chỉ: " .$row->guest->address
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function loadDeNghiNhienLieu() {
        $arr = [];
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('hcns'))
            $deNghi = DeNghiCapXang::select("*")->orderBy('id', 'DESC')->take(10)->get();
        else    
            $deNghi = DeNghiCapXang::where('id_user', Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($deNghi as $row) {
            $temp = (object)[];
            $temp->ngay = \HelpFunction::revertCreatedAt($row->created_at);
            $temp->username = $row->user->userDetail->surname;
            // $temp->xe_bienso = $row->fuel_car . " - " .$row->fuel_frame;
            $temp->xe = $row->fuel_car;
            $temp->bienso = $row->fuel_frame;
            $temp->nhienlieu = $row->fuel_type == 'X' ? "Xăng" : "Dầu";
            $temp->solit = $row->fuel_num;
            $temp->khachhang = $row->fuel_guest;
            $temp->lydo = $row->fuel_lyDo;
            $temp->ghichu = $row->fuel_ghiChu;
            if ($row->lead_id !== null) {
                $temp->nguoiduyet = $row->userLead->userDetail->surname . " " . ($row->lead_check ? "<span class='badge badge-success'>Đã duyệt</span>" : "<span class='badge badge-secondary'>Chưa duyệt</span>"); 
            } else {
                $temp->nguoiduyet = "";
            }
            $temp->hanhchinh = $row->fuel_allow ? "<span class='badge badge-success'>Đã duyệt</span>" : "<span class='badge badge-secondary'>Chưa duyệt</span>";
            if ($row->fuel_allow == false 
            && (!\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') 
            && !\Illuminate\Support\Facades\Auth::user()->hasRole('hcns'))) {
                $temp->action = "<a href='#' id='del' data-id='".$row->id."' class='btn btn-danger btn-xs'>Xóa</a>";
            } else if ($row->fuel_allow == true && $row->printed == false) {
                $temp->action = "<a href='".route('xang.in', ['id' => $row->id])."' class='btn btn-success btn-xs'>IN PHIẾU</a>";
            } else if ($row->fuel_allow == true && $row->printed == true 
            && (\Illuminate\Support\Facades\Auth::user()->hasRole('system') 
                || \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') 
                || \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))) {
                $temp->action = "<a href='".route('xang.in', ['id' => $row->id])."' class='btn btn-success btn-xs'>IN PHIẾU</a>";
            } else {
                $temp->action = "";
            }
            array_push($arr,$temp);
        }        
        if ($deNghi) 
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $arr
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }
}
