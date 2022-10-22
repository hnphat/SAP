<?php

namespace App\Http\Controllers;

use App\User;
use App\NhatKy;
use Illuminate\Http\Request;
use DataTables;

class NhatKyController extends Controller
{
    public function getList() {
        // $nhatKy = NhatKy::select("*")
        // ->orderBy('id', 'desc')
        // ->take(1500)
        // ->get();
        // return view('nhatky.nhatkyold', ['nk' => $nhatKy]);
        return view('nhatky.nhatky');
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
        return view('nhatky.tracuunangcao', ['nk' => $nhatKy, 'noiDung' => $noiDung, 'soLuong' => $soLuong]);
    }

    public function loadNhatKyV2(Request $request) {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true); 
        if ($request->ajax()) {
            $nhatKy = NhatKy::select("nhat_ky.*","d.surname as name")
            ->join("users_detail as d","nhat_ky.id_user","=","d.id_user")
            ->orderBy('nhat_ky.id', 'desc')
            ->take($data["maxRecord"])
            ->get();
            return Datatables::of($nhatKy)
                   ->make(true);
        }
        return view('nhatky.nhatky');
    }
}
