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
        ->take(1500)
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

    public function getTraCuu() {
        return view('nhatky.tracuunangcao');
    }

    public function loadNhatKy(Request $request) {
        $noiDung = $request->str_find;
        $soLuong = $request->num_row;
        $nhatKy = NhatKy::select("nhat_ky.*","d.surname as name")
        ->join("users_detail as d","nhat_ky.id_user","=","d.id_user")
        ->where("nhat_ky.noiDung","LIKE","%".$noiDung."%")
        ->orWhere("nhat_ky.chucNang","LIKE","%".$noiDung."%")
        ->orWhere("d.surname","LIKE","%".$noiDung."%")
        ->orderBy('nhat_ky.id', 'desc')
        ->take($soLuong)
        ->get();
        // if ($nhatKy->count() == 0) {
        //     $nhatKy2 = NhatKy::select("nhat_ky.*","d.surname as name")
        //     ->join("users_detail as d","nhat_ky.id_user","=","d.id_user")
        //     ->where("nhat_ky.chucNang","LIKE","%".$noiDung."%")
        //     ->orderBy('nhat_ky.id', 'desc')
        //     ->take($soLuong)
        //     ->get();
        //     if ($nhatKy2->count() == 0)  {
        //         $nhatKy3 = NhatKy::select("nhat_ky.*","d.surname as name")
        //         ->join("users_detail as d","nhat_ky.id_user","=","d.id_user")
        //         ->where("d.surname","LIKE","%".$noiDung."%")
        //         ->orderBy('nhat_ky.id', 'desc')
        //         ->take($soLuong)
        //         ->get();
        //         if ($nhatKy3->count() == 0) {
        //             return view('nhatky.tracuunangcao');            
        //         } else 
        //         return view('nhatky.tracuunangcao', ['nk' => $nhatKy3, 'noiDung' => $noiDung, 'soLuong' => $soLuong]);
        //     } else 
        //     return view('nhatky.tracuunangcao', ['nk' => $nhatKy2, 'noiDung' => $noiDung, 'soLuong' => $soLuong]);
        // } else 
        return view('nhatky.tracuunangcao', ['nk' => $nhatKy, 'noiDung' => $noiDung, 'soLuong' => $soLuong]);
    }
}
