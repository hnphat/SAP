<?php

namespace App\Http\Controllers;

use App\User;
use App\NhatKy;
use Illuminate\Http\Request;


class NhatKyController extends Controller
{
    public function getList() {
        $nhatKy = NhatKy::select("*")
        ->orderBy('id', 'desc')
        ->take(500)
        ->get();
        return view('nhatky.nhatky', ['nk' => $nhatKy]);
    }

    public function loadList() {
        $nhatKy = NhatKy::select("nhat_ky.*","d.surname as name")
        ->join("users_detail as d","nhat_ky.id_user","=","d.id_user")
        ->orderBy('nhat_ky.id', 'desc')
        ->take(500)
        ->get();

        if ($nhatKy)
            return response()->json([
                'code' => 200,
                'type' => 'info',
                'message' => 'Đã tải dữ liệu',
                'data' => $nhatKy
            ]);
        else 
            return response()->json([
                'code' => 500,
                'type' => 'info',
                'message' => 'Lỗi tải dữ liệu'
            ]);
    }
}
