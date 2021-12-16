<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BieuMau;
use Illuminate\Support\Facades\Auth;

class HanhChinhController extends Controller
{
    //

    public function showBieuMau() {
        return view('hanhchinh.bieumau');
    }

    public function getBieuMau() {
        $result = BieuMau::all();
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
        ])->get();
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
        ])->get();
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
}
