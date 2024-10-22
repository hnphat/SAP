<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChungTu;
use App\NhatKy;
use App\NhomUser;
use App\Nhom;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChungTuController extends Controller
{
    // Chứng từ/mộc
    public function showChungTuMoc() {
        return view('ketoan.chungtumoc');
    }

    public function getDeNghiMoc() {
        return view('hanhchinh.denghimoc');
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

    public function postBieuMauUpdate(Request $request) {
        $temp = ChungTu::find($request->eid);
        $userName = User::find($temp->user_create)->userDetail->surname;
        $bm = ChungTu::find($request->eid);
        $bm->noiDung = $request->enoiDung;
        $bm->slug = \HelpFunction::changeTitle($request->enoiDung);
        $bm->soLuong = $request->esoLuong;
        $bm->nguoiKy = $request->elanhDao;
        $bm->ghiChu = $request->eghiChu;
        if (Auth::user()->hasRole("system")) {
            $bm->allow = $request->eallow;
        }
        $bm->save();                                     
        if ($bm) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý dấu/mộc";
            $nhatKy->noiDung = "Cập nhật chứng từ nội dung: " . $temp->noiDung . " thành " . $request->enoiDung ."; Số lượng: " 
            . $temp->soLuong . " thành " . $request->esoLuong . "; Lãnh đạo ký: " 
            . $temp->nguoiKy . " thành " . $request->elanhDao . "; Chuyển trạng thái: " 
            . ($temp->allow ? "Đã tiếp nhận" : "Chưa tiếp nhận") . " thành "
            . ($request->eallow ? "Đã tiếp nhận" : "Chưa tiếp nhận") . "; Người yêu cầu: " . $userName;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật chứng từ',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi!',
                "code" => 500
            ]);
        }
    }

    public function upFile(Request $request) {
        $bm = ChungTu::find($request->eidUp);
        $this->validate($request,[
            'edinhKem'  => 'required|mimes:png,jpg,PNG,JPG,doc,docx,pdf|max:20480',
        ]);      

        if ($files = $request->file('edinhKem')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName());
            if ($name !== null && file_exists('upload/chungtu/' . $name . "." . $etc))
                unlink('upload/chungtu/'.$name);

            $name = rand() . "-" . $name . "." . $etc;    

            $bm->url = $name;
            $bm->save();                                     
            if ($bm) {
                $files->move('upload/chungtu/', $name);    
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Quản lý dấu/mộc";
                $nhatKy->noiDung = "Bổ sung file scan đường dẫn: " . url("upload/chungtu/" . $name);
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();
                return response()->json([
                    "type" => 'success',
                    "message" => 'Đã upload file thành công',
                    "code" => 200
                ]);
            }                       
            else
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi upload!',
                    'code' => 500
                ]);      
            }
    }

    public function checkBlock(Request $request) {
        $temp = ChungTu::find($request->id);
        $bm = ChungTu::find($request->id);
        $bm->allow = true;
        $bm->save();                                     
        if ($bm) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý dấu/mộc";
            $nhatKy->noiDung = "Tiếp nhận yêu cầu đóng dấu/mộc nội dung: " . $temp->noiDung;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã tiếp nhận yêu cầu',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi tiếp nhận!',
                "code" => 500
            ]);
        }
    }

    public function postBieuMauUpdateClient(Request $request) {
        // $temp = ChungTu::find($request->eid);
        // $userName = User::find($temp->user_create)->userDetail->surname;
        $bm = ChungTu::find($request->eid);
        $bm->noiDung = $request->enoiDung;
        $bm->slug = \HelpFunction::changeTitle($request->enoiDung);
        $bm->soLuong = $request->esoLuong;
        $bm->nguoiKy = $request->elanhDao;
        $bm->ghiChu = $request->eghiChu;
        $bm->save();                                     
        if ($bm) {
            // $nhatKy = new NhatKy();
            // $nhatKy->id_user = Auth::user()->id;
            // $nhatKy->thoiGian = Date("H:m:s");
            // $nhatKy->chucNang = "Hành chính - Quản lý dấu/mộc";
            // $nhatKy->noiDung = "Cập nhật chứng từ nội dung: " . $temp->noiDung . " thành " . $request->enoiDung ."; Số lượng: " 
            // . $temp->soLuong . " thành " . $request->esoLuong . "; Lãnh đạo ký: " 
            // . $temp->nguoiKy . " thành " . $request->elanhDao . "; Chuyển trạng thái: " 
            // . ($temp->allow ? "Đã tiếp nhận" : "Chưa tiếp nhận") . " thành "
            // . ($request->eallow ? "Đã tiếp nhận" : "Chưa tiếp nhận") . "; Người yêu cầu: " . $userName;
            // $nhatKy->ghiChu = Carbon::now();
            // $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật chứng từ',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi!',
                "code" => 500
            ]);
        }
    }

    public function deleteChungTu(Request $request) {
        $bm = ChungTu::find($request->id);
        if ($bm->allow != true) {
            $temp = $bm->noiDung;
            $name = $bm->url;
            if ($name !== null && file_exists('upload/chungtu/' . $name))
                unlink('upload/chungtu/'.$name);
            $bm->delete();        
            if ($bm) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị đóng mộc";
                $nhatKy->noiDung = "Xóa: " . $temp;
                $nhatKy->ghiChu = Carbon::now();
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
                    'message' => 'Lỗi không thể xoá từ máy chủ!',
                    'code' => 500
                ]);
        } else
            return response()->json([
                'type' => 'error',
                'message' => 'Chứng từ đã duyệt đóng dấu không thể xoá!',
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
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->noiDung = "Cập nhật lại nội dung chứng từ: " . $request->enoiDung . " <br/>Số lượng: " 
            . $request->esoLuong . " <br/>Người yêu cầu: " . $request->enguoiYeuCau . " <br/>Bộ phận: " 
            . $request->eboPhan . " <br/>Cho phép hiển thị: " . $request->eallow;
            $nhatKy->ghiChu = Carbon::now();
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

    // Chứng từ new

    public function loadDeNghiDongMoc() {
        $result = ChungTu::select("*")->where('user_create',Auth::user()->id)->orderBy('id', 'desc')->get();
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


    public function postChungTu(Request $request) {
        $check = ChungTu::where('slug',\HelpFunction::changeTitle($request->noiDung))->exists();        
        if (!$check) {
            $bm = new ChungTu();
            $bm->ngay = Carbon::now();
            $bm->noiDung = $request->noiDung;
            $bm->slug = \HelpFunction::changeTitle($request->noiDung);
            $bm->soLuong = $request->soLuong;
            $bm->nguoiKy = $request->lanhDao;
            $bm->ghiChu = $request->ghiChu;
            $nhom = NhomUser::where('id_user',Auth::user()->id)->first();
            if ($nhom) {
                $tenNhom = Nhom::find($nhom->id_nhom);
                $bm->boPhan = $tenNhom->name;
            }
            $bm->user_create = Auth::user()->id;
            $bm->nguoiYeuCau = Auth::user()->userDetail->surname;
            $bm->save();
            if ($bm) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Hành chính - Đề nghị đóng mộc";
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->noiDung = "Đề nghị đóng mộc nội dung: " . $request->noiDung . "<br/>Số lượng: " 
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
        return response()->json([
            "type" => 'danger',
            "message" => 'Lỗi: Nội dung văn bản trùng tên với các nội dung văn bản trước!',
            "code" => 500
        ]);
    }

    public function deleteFileScan(Request $request) {
        $bm = ChungTu::find($request->id);
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
            $temp = $bm->noiDung;
            $name = $bm->url;
            // if ($name !== null && file_exists('upload/chungtu/' . $name))
            //     unlink('upload/chungtu/'.$name);
            $bm->url = null;
            $bm->save();        
            if ($bm) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Quản lý dấu/mộc";
                $nhatKy->noiDung = "Cập nhật lại file scan. Nội dung ".$temp."; File cũ tạm ẩn " . url('upload/chungtu/' . $name);
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã cập nhật!',
                    'code' => 200,
                    'data' => $bm
                ]);    
            }           
            else
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi không thể cập nhật từ máy chủ!',
                    'code' => 500
                ]);
        } else
            return response()->json([
                'type' => 'error',
                'message' => 'Chứng từ đã duyệt đóng dấu không thể xoá!',
                'code' => 500
            ]);       
    }
}
