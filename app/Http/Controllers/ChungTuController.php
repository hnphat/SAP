<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChungTu;
use App\NhatKy;
use Illuminate\Support\Facades\Auth;

class ChungTuController extends Controller
{
     // Chứng từ/mộc
     public function showChungTuMoc() {
        return view('ketoan.chungtumoc');
    }
    
    public function showXemChungTu() {
        return view('ketoan.xemchungtu');
    }

    public function loadChungTu() {
        $result = ChungTu::select("*")->orderBy('id', 'desc')->get();
        if ($result) 
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $result
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }

    public function xemChungTu() {
        $result = ChungTu::select('*')->where([
            ['allow','=',true]
        ])->orderBy('id', 'desc')->get();
        if ($result) 
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $result
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }

    public function postBieuMau(Request $request) {
        $check = ChungTu::where('slug',\HelpFunction::changeTitle($request->noiDung))->exists();
        
        if (!$check) {
            $bm = new ChungTu();
            if ($request->hasFile == 'on') {
                $this->validate($request,[
                    'file'  => 'required|mimes:png,jpg,PNG,JPG,doc,docx,pdf,txt,xls,xlsx,ppt,pptx|max:20480',
                ]);        
                if ($files = $request->file('file')) {
                    $etc = strtolower($files->getClientOriginalExtension());
                    $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
                    while(file_exists("upload/chungtu/" . $name)) {
                        $name = rand() . "-" . $name . "." . $etc;
                    }
                    $bm->gio = $request->gio;
                    $bm->ngay = $request->ngay;
                    $bm->noiDung = $request->noiDung;
                    $bm->slug = \HelpFunction::changeTitle($request->noiDung);
                    $bm->url = $name;
                    $bm->soLuong = $request->soLuong;
                    $bm->nguoiYeuCau = $request->nguoiYeuCau;
                    $bm->boPhan = $request->boPhan;
                    $bm->ghiChu = $request->ghiChu;
                    $bm->allow = $request->allow;
                    $bm->user_create = Auth::user()->id;
                    $bm->save();
                    $files->move('upload/chungtu/', $name);                     
                    if ($bm) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->chucNang = "Kế toán - Chứng từ/mộc";
                        $nhatKy->noiDung = "Bổ sung chứng từ " . $request->noiDung . " Số lượng: " 
                        . $request->soLuong . " Người yêu cầu: " . $request->nguoiYeuCau . " Bộ phận: " 
                        . $request->boPhan . " Cho phép hiển thị: " . $request->allow . " Tệp đính kèm: Có";
                        $nhatKy->save();
                        return response()->json([
                            "type" => 'success',
                            "message" => 'File: Đã upload file và nội dung',
                            "code" => 200
                        ]);
                    }                       
                    else
                        return response()->json([
                            'message' => 'Lỗi upload!',
                            'code' => 500
                        ]);
                }
            } else {
                $bm->gio = $request->gio;
                $bm->ngay = $request->ngay;
                $bm->noiDung = $request->noiDung;
                $bm->slug = \HelpFunction::changeTitle($request->noiDung);
                $bm->soLuong = $request->soLuong;
                $bm->nguoiYeuCau = $request->nguoiYeuCau;
                $bm->boPhan = $request->boPhan;
                $bm->ghiChu = $request->ghiChu;
                $bm->allow = $request->allow;
                $bm->user_create = Auth::user()->id;
                $bm->save();
                if ($bm) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kế toán - Chứng từ/mộc";
                    $nhatKy->noiDung = "Bổ sung chứng từ " . $request->noiDung . " Số lượng: " 
                    . $request->soLuong . " Người yêu cầu: " . $request->nguoiYeuCau . " Bộ phận: " 
                    . $request->boPhan . " Cho phép hiển thị: " . $request->allow . " Tệp đính kèm: Không";
                    $nhatKy->save();
                    return response()->json([
                        "type" => 'success',
                        "message" => 'Đã upload nội dung, không có file đính kèm',
                        "code" => 200
                    ]);
                }                   
                else
                    return response()->json([
                        'message' => 'Lỗi upload!',
                        'code' => 500
                    ]);
            }
        }        
        return response()->json([
            "type" => 'danger',
            "message" => 'Duplicated file or Error required file',
            "code" => 500
        ]);
    }

    public function deleteChungTu(Request $request) {
        $bm = ChungTu::find($request->id);
        $temp = $bm->noiDung;
        $name = $bm->url;
        if ($name !== null && file_exists('upload/chungtu/' . $name))
            unlink('upload/chungtu/'.$name);
        $bm->delete();
       
        if ($bm) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Kế toán - Chứng từ/mộc";
            $nhatKy->noiDung = "Xóa chứng từ " . $temp;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa!',
                'code' => 200,
                'data' => $bm
            ]);    
        }           
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa file từ máy chủ!',
                'code' => 500
            ]);
    }

    public function getEditChungTu($id) {
        $result = ChungTu::find($id);
        if ($result) 
            return response()->json([
                'type' => 'success',
                'message' => 'Đã load!',
                'code' => 200,
                'data' => $result
            ]);
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Không thể load dữ liệu!',
                'code' => 500
            ]);
    }

    public function updateChungTu(Request $request) {
        $bm = ChungTu::find($request->eid);
        $bm->gio = $request->egio;
        $bm->ngay = $request->engay;
        $bm->noiDung = $request->enoiDung;
        $bm->slug = \HelpFunction::changeTitle($request->enoiDung);
        $bm->soLuong = $request->esoLuong;
        $bm->nguoiYeuCau = $request->enguoiYeuCau;
        $bm->boPhan = $request->eboPhan;
        $bm->ghiChu = $request->eghiChu;
        $bm->allow = $request->eallow;
        $bm->save();
        if ($bm) {

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Kế toán - Chứng từ/mộc";
            $nhatKy->noiDung = "Cập nhật lại nội dung chứng từ " . $request->enoiDung . " Số lượng: " 
            . $request->esoLuong . " Người yêu cầu: " . $request->enguoiYeuCau . " Bộ phận: " 
            . $request->eboPhan . " Cho phép hiển thị: " . $request->eallow;
            $nhatKy->save();

            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật',
                "code" => 200
            ]);
        }            
        else
            return response()->json([
                "type" => 'error',
                "message" => 'Không thể cập nhật',
                "code" => 500
            ]);
    }
}
