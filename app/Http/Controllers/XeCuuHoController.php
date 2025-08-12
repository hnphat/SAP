<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\XeCuuHo;
use App\NhatKy;
use App\User;

class XeCuuHoController extends Controller
{
    //
    public function index()
    {
        // Logic to display the list of xe cứu hộ
        return view('xecuuho.xecuuho');
    }

    public function danhsach()
    {
        $data = XeCuuHo::all();
        if ($data) 
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $data
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }

    public function post(Request $request) {
        $obj = new XeCuuHo();
        $obj->id_user = Auth::user()->id;
        $obj->khachHang = $request->khachHang;
        // $obj->sdt = $request->sdt;
        $obj->yeuCau = $request->yeuCau;
        $obj->hinhThuc = $request->hinhThuc;
        $obj->diaDiemDi = $request->diaDiemDi;
        $bm->save();
        if ($bm) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->noiDung = "Thêm mới: " . $request->noiDung . "<br/>Số lượng: " 
            . $request->soLuong;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã đề nghị đóng mộc',
                "code" => 200
            ]);
        }                   
        else
            return response()->json([
                "type" => 'error',
                'message' => 'Lỗi!',
                'code' => 500
            ]);
    }
}
