<?php

namespace App\Http\Controllers;

use App\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    //
    public function getPanel() {
        return view('danhgia.danhgia');
    }

    public function getList() {
        if (Auth::user()->hasRole('system')) {
            $dg = DanhGia::select("danh_gia.*","d.surname as surname")
            ->join("users as u","u.id","=","danh_gia.id_user")
            ->join("users_detail as d","u.id","=","d.id_user")
            ->orderBy('danh_gia.id','desc')
            ->get();
        } else {
            $dg = DanhGia::select("danh_gia.*","d.surname as surname")
            ->join("users as u","u.id","=","danh_gia.id_user")
            ->join("users_detail as d","u.id","=","d.id_user")
            ->where('danh_gia.id_user', Auth::user()->id)
            ->orderBy('danh_gia.id','desc')
            ->get();
        }

        if ($dg) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã tải danh sách đánh giá!',
                'code' => 200,
                'data' => $dg
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi tải danh sách đánh giá!',
                'code' => 500
            ]);
        } 
    }

    public function post(Request $request) {
        $dg = new DanhGia();
        $dg->ngay = Date('d');
        $dg->thang = Date('m');
        $dg->nam = Date('Y');
        $dg->soBaoGia = strtoupper($request->soBaoGia);
        $dg->id_user = Auth::user()->id;
        $dg->save();
        if ($dg) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã thêm!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi thêm đánh giá!',
                'code' => 500
            ]);
        } 
    }
}
