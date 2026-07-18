<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GuestBaoHiem;
use Illuminate\Support\Facades\Auth;
use App\NhatKy;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Excel;

use App\TypeCar;
use App\BaoHiemHopDong;
use App\User;
use App\GuestChamSoc;

class ChamSocController extends Controller
{
    //
    public function getChamSocKhachHangPanel() {
        return view('baohiem.chamsockhachhang');
    }

    public function getListChamSocKhachHang(Request $request) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        
        $data = GuestChamSoc::select('guest_cham_soc.*', 'd.surname as creator')
            ->leftJoin('users as u', 'u.id', '=', 'guest_cham_soc.id_user_create')
            ->leftJoin('users_detail as d', 'd.id_user', '=', 'u.id')
            ->whereBetween('guest_cham_soc.created_at', [$from, $to])
            ->orderBy('guest_cham_soc.id', 'desc')
            ->get();
            
        return response()->json([
            'type' => 'success',
            'message' => 'Đã tải danh sách chăm sóc khách hàng!',
            'code' => 200,
            'data' => $data
        ]);
    }
}
