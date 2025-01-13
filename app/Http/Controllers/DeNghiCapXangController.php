<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\NhatKy;
use App\DeNghiCapXang;
use App\EventReal;
use Carbon\Carbon;
use App\Mail\DuyetCapXangTBP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class DeNghiCapXangController extends Controller
{
    //
    public function showCapXang() {
        $lead = User::all();
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('adminsale') || Auth::user()->hasRole('hcns'))
            $deNghi = DeNghiCapXang::select("*")->orderBy('id', 'DESC')->get();
        else    
            $deNghi = DeNghiCapXang::where('id_user', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('capxang.denghiv2', ['lead' => $lead, 'deNghi' => $deNghi]);
    }

    public function postDeNghi(Request $request) {
        $userDuyetEmail = User::find($request->leadCheck);
        $emailDuyet = ($userDuyetEmail) ? $userDuyetEmail->email : "";
        $nguoiDuyet = ($userDuyetEmail) ? $userDuyetEmail->userDetail->surname : "";
        $nguoiYeuCau = User::find(Auth::user()->id)->userDetail->surname;
        $xeDangKy = $request->loaiXe . " " . $request->bienSo;
        $lyDo = $request->lyDoCap;
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
        $deNghi->fuel_lyDo = $request->lyDoCap;
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
                if ($userDuyetEmail)
                    Mail::to($emailDuyet)->send(new DuyetCapXangTBP([$nguoiDuyet,$nguoiYeuCau,$code,$ngayDangKy,$xeDangKy,$lyDo,$nhienLieu,$soLit,$khach,$ghiChu]));
                Mail::to($data['emailNhienLieu'])->send(new DuyetCapXangTBP(['Phòng hành chính',$nguoiYeuCau,$code,$ngayDangKy,$xeDangKy,$lyDo,$nhienLieu,$soLit,$khach,$ghiChu]));
            //-----

            return redirect()
            ->route('capxang.denghi')
            ->with('succ','Đã gửi đề nghị cấp nhiên liệu!');
        } else {
            return redirect()
            ->route('capxang.denghi')
            ->with('err','Không thể gửi đề nghị cấp nhiên liệu!');
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
}
