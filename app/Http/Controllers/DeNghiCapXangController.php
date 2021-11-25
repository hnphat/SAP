<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\DeNghiCapXang;
use App\EventReal;
use Illuminate\Support\Facades\Auth;

class DeNghiCapXangController extends Controller
{
    //
    public function showCapXang() {
        $lead = User::all();
        $deNghi = DeNghiCapXang::where('id_user', Auth::user()->id)->get();
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
        $deNghi->ghiChu = $request->ghiChu;
        $deNghi->fuel_km = $request->km;
        $deNghi->lead_id = $request->leadCheck;
        $deNghi->duongDi = "Từ " . $request->from . " đến " . $request->to;
        $deNghi->save();
        $eventReal = new EventReal;
        $eventReal->name = "Cá nhân làm đề nghị";
        $eventReal->save();
        if ($deNghi) {
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
        $deNghi = DeNghiCapXang::where('id',$request->id)->delete();
        $eventReal = new EventReal;
        $eventReal->name = "Cá nhân xóa đề nghị";
        $eventReal->save();
        if($deNghi) {
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
            $deNghi = DeNghiCapXang::all();
        elseif (Auth::user()->hasRole('lead'))   
            $deNghi = DeNghiCapXang::where('lead_id', Auth::user()->id)->get();
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
