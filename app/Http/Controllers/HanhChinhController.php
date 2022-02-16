<?php

namespace App\Http\Controllers;

use App\BieuMau;
use App\UsersDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HanhChinhController extends Controller
{
    //

    public function showBieuMau() {
        return view('hanhchinh.bieumau');
    }

    public function getBieuMau() {
        $result = BieuMau::select('*')
        ->where('type','!=', 'CT')
        ->orderBy('id', 'desc')->get();
        if ($result) 
            return response()->json([
                'message' => 'Tải file thành công!',
                'code' => 200,
                'data' => $result
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải filetừ máy chủ!',
                'code' => 500
            ]);
    }

    public function getEditBieuMau($id) {
        $result = BieuMau::find($id);
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

    public function postBieuMau(Request $request) {
        $bm = new BieuMau();
        $this->validate($request,[
            'file'  => 'required|mimes:doc,docx,pdf,txt,xls,xlsx,ppt,pptx|max:20480',
        ]);
    
        if ($files = $request->file('file')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/bieumau/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $bm->tieuDe = $request->tieuDe;
            $bm->slug = \HelpFunction::changeTitle($request->tieuDe);
            $bm->url = $name;
            $bm->moTa = $request->moTa;
            $bm->type = $request->loaiFile;
            $bm->ghiChu = $request->ghiChu;
            $bm->allow = $request->allow;
            $bm->ngayTao = Date('d-m-Y');
            $bm->user_create = Auth::user()->id;
            $bm->save();
            $files->move('upload/bieumau/', $name);
            
            return response()->json([
                "type" => 'success',
                "message" => 'File: Đã upload file',
                "code" => 200,
                "file" => $files
            ]);
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function updateBieuMau(Request $request) {
        $bm = BieuMau::find($request->eid);
        $bm->tieuDe = $request->etieuDe;
        $bm->slug = \HelpFunction::changeTitle($request->etieuDe);
        $bm->moTa = $request->emoTa;
        $bm->type = $request->eloaiFile;
        $bm->ghiChu = $request->eghiChu;
        $bm->allow = $request->eallow;
        $bm->user_create = Auth::user()->id;
        $bm->save();
        if ($bm)    
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật',
                "code" => 200
            ]);
        else
            return response()->json([
                "type" => 'error',
                "message" => 'Không thể cập nhật',
                "code" => 500
            ]);
    }

    public function deleteBieuMau(Request $request) {
        $bm = BieuMau::find($request->id);
        $name = $bm->url;
        if (file_exists('upload/bieumau/' . $name))
            unlink('upload/bieumau/'.$name);
        $bm->delete();
        if ($bm) 
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa file!',
                'code' => 200,
                'data' => $bm
            ]);
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa file từ máy chủ!',
                'code' => 500
            ]);
    }

    // BIỂU MẪU CHỈ XEM
    public function showXemBieuMau() {
        return view('hanhchinh.xembieumau');
    }

    public function getXemBieuMau() {
        $result = BieuMau::select('*')->where([
            ['allow','=',true],
            ['type','=','BM']
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

    // THÔNG BÁO CHỈ XEM
    public function showXemThongBao() {
        return view('hanhchinh.xemthongbao');
    }

    public function getXemThongBao() {
        $result = BieuMau::select('*')->where([
            ['allow','=',true],
            ['type','=','TB']
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

    // Chứng từ/mộc
    public function showChungTuMoc() {
        return view('ketoan.chungtumoc');
    }
    
    public function showXemChungTu() {
        return view('ketoan.xemchungtu');
    }

    public function loadChungTu() {
        $result = BieuMau::select('*')->where([
            ['type','=','CT']
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

    public function xemChungTu() {
        $result = BieuMau::select('*')->where([
            ['allow','=',true],
            ['type','=','CT']
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

    // BẢNG GIÁ XE KINH DOANH
    public function showBangGiaXe() {
        return view('hopdong.banggiaxe');
    }

    public function showXemBangGiaXe() {
        return view('hopdong.xembanggiaxe');
    }


    public function getBangGia() {
        if (Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('system')) {
            $result = BieuMau::select('*')->where([
                    ['type','=','GX']
            ])->orderBy('id', 'desc')->get();
        }
        else {
            $result = BieuMau::select('*')->where([
                ['allow','=',true],
                ['type','=','GX']
            ])->orderBy('id', 'desc')->get();   
        }        
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

    public function postBangGia(Request $request) {
        $bm = new BieuMau();
        $this->validate($request,[
            'file'  => 'required|mimes:doc,docx,pdf,txt,xls,xlsx,ppt,pptx|max:20480',
        ]);
    
        if ($files = $request->file('file')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/bieumau/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $bm->tieuDe = $request->tieuDe;
            $bm->slug = \HelpFunction::changeTitle($request->tieuDe);
            $bm->url = $name;
            $bm->moTa = $request->moTa;
            $bm->type = 'GX';
            $bm->ghiChu = $request->ghiChu;
            $bm->allow = $request->allow;
            $bm->ngayTao = Date('d-m-Y');
            $bm->user_create = Auth::user()->id;
            $bm->save();
            $files->move('upload/bieumau/', $name);
            
            return response()->json([
                "type" => 'success',
                "message" => 'File: Đã upload file',
                "code" => 200,
                "file" => $files
            ]);
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function getEditBangGia($id) {
        $result = BieuMau::find($id);
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

    public function updateBangGia(Request $request) {
        $bm = BieuMau::find($request->eid);
        $bm->tieuDe = $request->etieuDe;
        $bm->slug = \HelpFunction::changeTitle($request->etieuDe);
        $bm->moTa = $request->emoTa;
        $bm->ghiChu = $request->eghiChu;
        $bm->allow = $request->eallow;
        $bm->user_create = Auth::user()->id;
        $bm->save();
        if ($bm)    
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật',
                "code" => 200
            ]);
        else
            return response()->json([
                "type" => 'error',
                "message" => 'Không thể cập nhật',
                "code" => 500
            ]);
    }

     // THÔNG BÁO NỘI BỘ KINH DOANH
     public function showSaleThongBao() {
        return view('hopdong.thongbaonb');
    }

    public function showXemSaleThongBao() {
        return view('hopdong.xemthongbaonb');
    }


    public function getSaleThongBao() {
        if (Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('system')) {
            $result = BieuMau::select('*')->where([
                    ['type','=','TBKD']
            ])->orderBy('id', 'desc')->get();
        }
        else {
            $result = BieuMau::select('*')->where([
                ['allow','=',true],
                ['type','=','TBKD']
            ])->orderBy('id', 'desc')->get();   
        }        
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

    public function postSaleThongBao(Request $request) {
        $bm = new BieuMau();
        $this->validate($request,[
            'file'  => 'required|mimes:doc,docx,pdf,txt,xls,xlsx,ppt,pptx|max:20480',
        ]);
    
        if ($files = $request->file('file')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/bieumau/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $bm->tieuDe = $request->tieuDe;
            $bm->slug = \HelpFunction::changeTitle($request->tieuDe);
            $bm->url = $name;
            $bm->moTa = $request->moTa;
            $bm->type = 'TBKD';
            $bm->ghiChu = $request->ghiChu;
            $bm->allow = $request->allow;
            $bm->ngayTao = Date('d-m-Y');
            $bm->user_create = Auth::user()->id;
            $bm->save();
            $files->move('upload/bieumau/', $name);
            
            return response()->json([
                "type" => 'success',
                "message" => 'File: Đã upload file',
                "code" => 200,
                "file" => $files
            ]);
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function getEditSaleThongBao($id) {
        $result = BieuMau::find($id);
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

    public function updateSaleThongBao(Request $request) {
        $bm = BieuMau::find($request->eid);
        $bm->tieuDe = $request->etieuDe;
        $bm->slug = \HelpFunction::changeTitle($request->etieuDe);
        $bm->moTa = $request->emoTa;
        $bm->ghiChu = $request->eghiChu;
        $bm->allow = $request->eallow;
        $bm->user_create = Auth::user()->id;
        $bm->save();
        if ($bm)    
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật',
                "code" => 200
            ]);
        else
            return response()->json([
                "type" => 'error',
                "message" => 'Không thể cập nhật',
                "code" => 500
            ]);
    }

    // Hồ sơ nhân viên
    public function getHoSo() {
        $hoso = UsersDetail::all();
        return view('hanhchinh.hoso', ['hs' => $hoso]);
    }

    public function getHoSoWithName(Request $request) {
        $hoSo = UsersDetail::where('surname','like', "%".$request->name."%")->first();

        if ($hoSo) {
            return response()->json([
                'code' => 200,
                'result' => "<tr><td>".$hoSo->surname."</td><td>".$hoSo->phone."</td></tr>"
            ]);
        } else {
            return response()->json([
                'code' =>  500
            ]);
        }
    }
}
