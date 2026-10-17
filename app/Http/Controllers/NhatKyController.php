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
        if ($request->ajax()) {
            $query = NhatKy::query()
                ->leftJoin("users_detail as d", "nhat_ky.id_user", "=", "d.id_user")
                ->select([
                    "nhat_ky.id",
                    "nhat_ky.ghiChu",
                    "nhat_ky.chucNang",
                    "nhat_ky.noiDung",
                    "d.surname as name"
                ]);

            return Datatables::of($query)
                ->filterColumn('id', function($q, $keyword) {
                    $q->where('nhat_ky.id', 'like', "%{$keyword}%");
                })
                ->filterColumn('name', function($q, $keyword) {
                    $q->where('d.surname', 'like', "%{$keyword}%");
                })
                ->orderColumn('name', function($q, $order) {
                    $q->orderBy('d.surname', $order);
                })
                ->make(true);
        }
        return view('nhatky.nhatky');
    }

    public function loadNhatKyNangCao(Request $request) {
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
        return view('nhatky.nhatkyold', ['nk' => $nhatKy, 'noiDung' => $noiDung, 'soLuong' => $soLuong]);
    }
}
