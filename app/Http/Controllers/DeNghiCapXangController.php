<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\NhatKy;
use App\DeNghiCapXang;
use App\EventReal;
use Illuminate\Support\Facades\Auth;

class DeNghiCapXangController extends Controller
{
    //
    public function showCapXang() {
        $lead = User::all();
        $deNghi = DeNghiCapXang::where('id_user', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('capxang.denghi', ['lead' => $lead, 'deNghi' => $deNghi]);
    }

    public function postDeNghi(Request $request) {
        $deNghi = new DeNghiCapXang;
        $deNghi->id_user = Auth::user()->id;
        $deNghi->fuel_type = $request->loaiNhienLieu;
        $deNghi->fuel_num = $request->soLit;;
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

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Hành chính - Đề nghị cấp nhiên liệu";
            $nhatKy->noiDung = "Thêm đề nghị cấp nhiên liệu loại xe: ".$request->loaiXe." khách hàng: "
            .$request->khachHang." biển số: ".$request->bienSo." lý do cấp: " . $request->lyDoCap;
            $nhatKy->save();

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
            $nhatKy->chucNang = "Hành chính - Đề nghị cấp nhiên liệu";
            $nhatKy->noiDung = "Xóa đề nghị cấp nhiên liệu loại xe: ".$temp->fuel_car." khách hàng: "
            .$temp->fuel_guest." biển số: ".$temp->fuel_frame." lý do cấp: " . $temp->fuel_lyDo;
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns'))
            $deNghi = DeNghiCapXang::select('*')->orderBy('id', 'DESC')->get();
        elseif (Auth::user()->hasRole('lead'))   
            $deNghi = DeNghiCapXang::where('lead_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
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
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Phê duyệt đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
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
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Hủy đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
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
            $nhatKy->chucNang = "Hành chính - Phê duyệt đề nghị nhiên liệu";
            $nhatKy->noiDung = "Phê duyệt đề nghị cấp nhiên liệu CODE: HAGI-CX-0" . $request->id;
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
