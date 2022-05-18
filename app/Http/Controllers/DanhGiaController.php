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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('qlcovan')) {
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
        $dg->soBaoGia = ($request->soBaoGia) ? strtoupper($request->soBaoGia) : null;
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

    public function delete(Request $request) {
        $dg = DanhGia::find($request->id);
        $temp_anh = $dg->chuKy;
        if ($temp_anh != null && file_exists('upload/sign/' . $temp_anh))
        unlink('upload/sign/'.$temp_anh); 
        $dg->delete();
        if ($dg) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã xoá!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Không thể xoá!',
                'code' => 500
            ]);
        } 
    }


    public function postSign(Request $request) {    
        // Xử lý upload chữ ký
        $folderPath = public_path('upload/sign/');        
        $image_parts = explode(";base64,", $request->signed);              
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]);           
        $name = uniqid() . '.'.$image_type;
        $file = $folderPath . $name;
        file_put_contents($file, $image_base64);        
        // kết thúc xử lý upload chữ ký

        $dg = DanhGia::find($request->idDanhGia);
        $temp_anh = $dg->chuKy;
        if ($temp_anh != null && file_exists('upload/sign/' . $temp_anh))
        unlink('upload/sign/'.$temp_anh); 
        $dg->diemVeSinh = $request->diemVeSinh;
        $dg->veSinhLai = $request->veSinhLai;
        $dg->chuKy = $name;
        $dg->save();
        if($dg) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật đánh giá',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi: Không thể cập nhật!',
                'code' => 500
            ]);
        }  
    } 
}
