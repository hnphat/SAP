<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\LoaiPhep;
use App\User;
use App\Luong;
use App\XinPhep;
use App\TangCa;
use App\Nhom;
use App\NhomUser;
use App\QuanLyTangCa;
use App\XacNhanCong;
use App\NhatKy;
use App\BienBanKhenThuong;
use App\ChamCongChiTiet;
use App\ChamCongOnline;
use Carbon\Carbon;
use App\Mail\EmailXinPhep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Excel;
use DataTables;

class TestController extends Controller
{
    public function onlineChamCong() {
        return view("testfeature.test");
    }

    public function kiemTraTrangThaiViTri(Request $request) {
        $ipClient = $request->ip();
        $ipCheck1 = "115.78.73.52";
        $ipCheck2 = "203.210.232.175";
        if ($ipClient == $ipCheck1 || $ipClient == $ipCheck2) {
            return response()->json([
                'type' => 'success',
                'ipWan' => request()->ip(),
                'result' => 1, // Đang ở công ty
                'code' => 200
            ]); 
        } else {
            return response()->json([
                'type' => 'info',
                'ipWan' => request()->ip(),
                'result' => 0, // Không ở công ty
                'code' => 200
            ]);  
        }
    }

    public function postOnlineChamCong(Request $request) {
        $getStatusDevice = $request->statusDevice;
        $getStatusPos = $request->statusPos;
        $getBuoiChamCong = $request->buoiChamCong;
        $getLoaiChamCong = $request->loaiChamCong;
        $getTimerNow = $request->getNowTimer;
         
        // if ($getStatusDevice != 1) {
        //     return response()->json([
        //         'type' => 'error',
        //         'message' => 'Thiết bị không hợp lệ. Vui lòng sử dụng thiết bị đã đăng ký',
        //         'code' => 500
        //     ]);
        // }

        if ($getStatusPos != 1) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn đang không ở Công ty, hãy truy cập wifi của Công ty và thử lại!',
                'code' => 500,
                'key' => "s2"
            ]);
        }

        // Tự xác định buổi chấm công và loại chấm công
        $buoiXacDinh = 0;
        $loaiXacDinh = 0;
        $getInfoChamCong = ChamCongOnline::select("*")->where([
            [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
            ['id_user','=',Auth::user()->id]
        ])->get();
        $soLuongDaCham = count($getInfoChamCong);
        if ($soLuongDaCham == 0) {
            $buoiXacDinh = 1; // buổi sáng
            $loaiXacDinh = 1; // vào
        } else if ($soLuongDaCham == 1) {
            $buoiXacDinh = 1; // buổi sáng
            $loaiXacDinh = 2; // ra
        } else if ($soLuongDaCham == 2) {
            $buoiXacDinh = 2; // buổi chiều
            $loaiXacDinh = 1; // vào
        } else if ($soLuongDaCham == 3) {
            $buoiXacDinh = 2; // buổi chiều
            $loaiXacDinh = 2; // ra
        } else if ($soLuongDaCham == 4) {
            $buoiXacDinh = 3; // buổi tối
            $loaiXacDinh = 1; // vào
        } else if ($soLuongDaCham == 5) {
            $buoiXacDinh = 3; // buổi tối
            $loaiXacDinh = 2; // ra
        } else {
            return response()->json([
                'type' => 'error',               
                'code' => 500,
                'message' => 'Hôm nay bạn đã chấm công đủ số lần quy định (06 lần/ngày), không thể chấm công thêm!',
                'key' => "s22"
            ]);  
        }

        $chamcong = new ChamCongOnline();
        $chamcong->id_user = Auth::user()->id;
        $chamcong->buoichamcong = $buoiXacDinh;
        $chamcong->loaichamcong = $loaiXacDinh;
        $chamcong->thoigianchamcong = $getTimerNow;
        // Xử lý đã chấm rồi hay chưa và xử lý hack vị trí
        $ipClient = $request->ip();
        $ipCheck1 = "115.78.73.52";
        $ipCheck2 = "203.210.232.175";
        if ($ipClient == $ipCheck1 || $ipClient == $ipCheck2) {
        } else {
            return response()->json([
                'type' => 'error',               
                'code' => 500,
                'message' => 'Bạn đang không ở Công ty, hãy truy cập wifi của Công ty và thử lại!',
                'key' => "s2"
            ]);  
        }
        // Xử lý upload
        $folderPath = public_path('upload/chamcongonline/');        
        $image_parts = explode(";base64,", $request->imageCaptured);              
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]);           
        $name = Auth::user()->name ."_" . uniqid() . '.'.$image_type;
        $file = $folderPath . $name;
        file_put_contents($file, $image_base64);        
        // kết thúc xử lý upload 

        // ---------------------------
        $chamcong->hinhanh = $name;
        $chamcong->save();
        if ($chamcong) {
            // Xử lý bổ sung giờ công
            // $getAll = ChamCongOnline::where([
            //     ['id_user','=',Auth::user()->id],
            //     [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')]
            // ])->get();
            // $vaoSang = null;
            // $raSang = null;
            // $vaoChieu = null;
            // $raChieu = null;
            // foreach($getAll as $row) {
            //     if ($row->buoichamcong == 1 && $row->loaichamcong == 1) {
            //         $vaoSang = $row->thoigianchamcong;
            //     }
            //     if ($row->buoichamcong == 1 && $row->loaichamcong == 2) {
            //         $raSang = $row->thoigianchamcong;
            //     }
            //     if ($row->buoichamcong == 2 && $row->loaichamcong == 1) {
            //         $vaoChieu = $row->thoigianchamcong;
            //     }
            //     if ($row->buoichamcong == 2 && $row->loaichamcong == 2) {
            //         $raChieu = $row->thoigianchamcong;
            //     }                
            // }
            // $ngay = Date('d');
            // $thang = Date('m');
            // $nam = Date('Y');
            // $checkChamCong = ChamCongChiTiet::where([
            //     ['ngay','=',$ngay],
            //     ['thang','=',$thang],
            //     ['nam','=',$nam],
            //     ['id_user','=',Auth::user()->id]
            // ])->exists();
            // if ($checkChamCong) {
            //     $chiTiet = ChamCongChiTiet::where([
            //         ['ngay','=',$ngay],
            //         ['thang','=',$thang],
            //         ['nam','=',$nam],
            //         ['id_user','=',Auth::user()->id]
            //     ])
            //     ->update([
            //         'vaoSang' => $vaoSang,
            //         'raSang' => $raSang,
            //         'vaoChieu' => $vaoChieu,
            //         'raChieu' => $raChieu
            //     ]);
            // } else {
            //     $chiTiet = ChamCongChiTiet::insert([
            //         'id_user' => Auth::user()->id,
            //         'ngay' => $ngay,
            //         'thang' => $thang,
            //         'nam' => $nam,
            //         'vaoSang' => $vaoSang,
            //         'raSang' => $raSang,
            //         'vaoChieu' => $vaoChieu,
            //         'raChieu' => $raChieu
            //     ]);
            // }  
            return response()->json([
                'type' => 'success',
                'message' => 'Đã ghi nhận giờ công lúc ' . $getTimerNow .  " ngày " . Date('d-m-Y'),
                'code' => 200,
                'key' => "random"
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi chấm công online. Vui lòng thử lại',
                'code' => 500
            ]);
        }
    }

    public function loadLichSuChamCong(Request $request) {
        $result = null;        
        $arr = [];
        $result = ChamCongOnline::select("*")
        ->where("id_user",Auth::user()->id)
        ->orderBy('id','desc')
        ->get();
        foreach($result as $row) {
            $dateChamCong = \HelpFunction::getDateRevertCreatedAt($row->created_at);
            $nowDate = Date('d-m-Y');
            if (strtotime($dateChamCong) == strtotime($nowDate)) {
                array_push($arr, $row);
            }
        }
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }
}
