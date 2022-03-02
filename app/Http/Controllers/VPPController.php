<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NhomSP;
use App\DanhMucSP;
use App\NhatKy;
use App\PhieuNhap;
use App\NhapSP;
use App\PhieuXuat;
use App\XuatSP;
use Illuminate\Support\Facades\Auth;

class VPPController extends Controller
{
    // Quản lý nhóm hàng hóa
    public function nhomHangPanel(){
        return view('vpp.quanlynhomhang');
    }

    public function loadNhomHang(){
        $nhom = NhomSP::all();
        if ($nhom)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Load thành công",
                'data' => $nhom
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không có dữ liệu'
            ]);
    }

    public function postNhomHang(Request $request){
        $nhom = new NhomSP();
        $nhom->tenNhom = $request->tenNhom;
        $nhom->save();
        if ($nhom) {
                
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý nhóm hàng";
            $nhatKy->noiDung = "Thêm nhóm hàng: " . $request->tenNhom;
            $nhatKy->save(); 

                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã thêm"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể thêm nhóm hàng'
            ]);        
    }

    public function deleteNhomHang(Request $request) {
        $nhom = NhomSP::find($request->id);
        $name = $nhom->tenNhom;       
        $nhom->delete();
        if ($nhom) {             
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý nhóm hàng";
            $nhatKy->noiDung = "Xóa nhóm hàng: " . $name ;
            $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã xóa"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xóa'
            ]);
    }

    public function loadEditNhomHang($id){
        $nhom = NhomSP::find($id);
        if ($nhom) 
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => 'Đã load',
                'data' => $nhom
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể load'
            ]);
    }

    public function updateNhomHang(Request $request){
        $nhom = NhomSP::find($request->eid);
        $name = $nhom->tenNhom;
        $nhom->tenNhom = $request->etenNhom;
        $nhom->save();
        if ($nhom) {                
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý nhóm hàng";
            $nhatKy->noiDung = "Cập nhật nhóm hàng: Từ <strong>" . $name . "</strong> thành <strong>" . $request->etenNhom . "</strong>";
            $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã cập nhật"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể cập nhật nhóm hàng'
            ]);        
    }

     // Quản lý danh mục hàng hóa
     public function danhMucPanel(){
        $nhom = NhomSP::all();
        return view('vpp.quanlydanhmuc', ['dm' => $nhom]);
    }

    public function loadDanhMuc(){
        $dm = DanhMucSP::select("danhmuc_sp.*","nhom.tenNhom as tenNhom")
        ->join('nhom_sp as nhom','danhmuc_sp.id_nhom','=','nhom.id')
        ->orderBy('id', 'desc')
        ->get();
        if ($dm)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Load thành công",
                'data' => $dm
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không có dữ liệu'
            ]);
    }

    public function postDanhMuc(Request $request){
        $dm = new DanhMucSP();
        $dm->id_nhom = $request->nhomHang;
        $dm->tenSanPham = $request->tenSanPham;
        $dm->donViTinh = $request->donViTinh;
        $dm->moTa = $request->moTa;
        $dm->save();
        if ($dm) {                
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý danh mục";
            $nhatKy->noiDung = "Thêm danh mục hàng hóa: <br/>Tên: ".$request->tenSanPham."<br/> Đơn vị tính: ".$request->donViTinh."<br/> Mô tả: ".$request->moTa."<br/>";
            $nhatKy->save();
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã thêm"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể thêm danh mục'
            ]);        
    }

    public function deleteDanhMuc(Request $request) {
        $dm = DanhMucSP::find($request->id);
        $name = $dm->tenSanPham;       
        $dm->delete();
        if ($dm) {             
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý danh mục";
            $nhatKy->noiDung = "Xóa danh mục: " . $name ;
            $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã xóa"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xóa'
            ]);
    }

    public function loadEditDanhMuc($id){
        $dm = DanhMucSP::find($id);
        if ($dm) 
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => 'Đã load',
                'data' => $dm
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể load'
            ]);
    }

    public function updateDanhMuc(Request $request){
        $dm = DanhMucSP::find($request->eid);
        $name = $dm->tenSanPham;
        $nameNhomCu = NhomSP::find($dm->id_nhom)->tenNhom;
        $dm->id_nhom = $request->enhomHang;
        $nameNhom = NhomSP::find($request->enhomHang)->tenNhom;
        $dm->donViTinh = $request->edonViTinh;
        $dm->tenSanPham = $request->etenSanPham;
        $dm->moTa = $request->emoTa;
        $dm->save();
        if ($dm) {                
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho - Quản lý danh mục";
            $nhatKy->noiDung = "Cập nhật danh mục từ <strong>" . $name . "</strong> thành <strong>" . $request->etenSanPham . "</strong><br/>Nhóm hàng từ <strong>".$nameNhomCu."</strong> sang <strong>".$nameNhom."</strong>";
            $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã cập nhật"
                ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể cập nhật nhóm hàng'
            ]);        
    }

    // Quản lý nhập kho
    public function nhapKhoPanel(){
        return view('vpp.quanlynhapkho');
    }

    public function nhapKhoPost(Request $request) {  
        $id = $request->idPN;
        if ($id != ''){
            $count = 1;
            $i = 1;
            while(isset($request['hangHoa' . $count]))  {
                $count++;
            }      

            if ($count == 1) {
                return response()->json([
                    'code' => 500,        
                    'type' => 'info',
                    'message' => 'Vui lòng thêm hàng hóa vào phiếu nhập. Bạn chưa thêm bất kỳ mặt hàng nào!',
                ]);   
            } else {
                for($i; $i < $count; $i++) {
                    $hangHoa = $request['hangHoa' . $i];
                    $soLuong = $request['soLuong' . $i];
                    $donGia = $request['donGia' . $i];
                    // dd($hangHoa . " -- " . $soLuong . " - " . $donGia);
                    $nhap = new NhapSP();
                    $nhap->id_danhmuc = $hangHoa;
                    $nhap->id_nhap = $id;
                    $nhap->soLuong = $soLuong;
                    $nhap->donGia = $donGia;
                    $nhap->save();                    
                }
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => 'Đã thêm phiếu nhập và danh mục sản phẩm vào kho',
                ]);   
            }            
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'info',
                'message' => 'Vui lòng tạo phiếu nhập trước khi thực hiện thêm hàng hóa',
            ]);   
        }
    }

    public function nhapKhoLoadDanhMuc(){
        $dm = DanhMucSP::select("danhmuc_sp.*","nhom.tenNhom as tenNhom")
        ->join('nhom_sp as nhom','danhmuc_sp.id_nhom','=','nhom.id')
        ->orderBy('id', 'desc')
        ->get();
        if ($dm)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Tải thành công danh mục hàng hóa",
                'data' => $dm
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy danh mục hàng hóa'
            ]);
    }

    public function nhapKhoTaoPhieuNhap(Request $request){   
        if ($request->noiDungNhap != '') {
            $newPN = new PhieuNhap();
            $newPN->ngay = Date('d');
            $newPN->thang = Date('m');
            $newPN->nam = Date('Y');
            $newPN->id_user = Auth::user()->id;
            $newPN->noiDungNhap = $request->noiDungNhap;
            $newPN->save();
            $code = "PNK-0" . $newPN->id;
            if ($newPN) {                
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Quản lý nhập kho";
                $nhatKy->noiDung = "Tạo phiếu nhập <strong>".$code."</strong>";
                $nhatKy->save(); 
                    return response()->json([
                        'code' => 200,        
                        'type' => 'info',
                        'message' => "Đã tạo phiếu nhập",
                        'maPhieu' => $code,
                        'idPN' => $newPN->id
                    ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể tạo phiếu nhập'
                ]);     
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'info',
                'message' => 'Bạn chưa nhập nội dung nhập kho!'
            ]);    
        }        
    }

    public function nhapKhoLoadPhieuNhap(){
        $phieuNhap = PhieuNhap::select("*")->orderBy('id','desc')->get();
        if ($phieuNhap)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải phiếu nhập",
                'data' => $phieuNhap
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy danh mục hàng hóa'
            ]);
    }

    public function nhapKhoLoadPhieuNhapChiTiet(Request $request){
        $phieuNhap = PhieuNhap::find($request->idPN);
        $nhapsp = NhapSP::select('nhap_sp.soLuong','nhap_sp.donGia','d.tenSanPham','d.id')
        ->join('danhmuc_sp as d','d.id','=','nhap_sp.id_danhmuc')
        ->where('nhap_sp.id_nhap',$phieuNhap->id)
        ->get();
        if ($nhapsp)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải phiếu nhập",
                'data' => $nhapsp,
                'noiDung' => $phieuNhap->noiDungNhap,
                'ngayNhap' => $phieuNhap->ngay . "-" . $phieuNhap->thang . "-" . $phieuNhap->nam
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy danh mục hàng hóa'
            ]);
    }

    public function nhapKhoDelete(Request $request) {
        $xoa = PhieuNhap::find($request->idPN);
        $xoa->delete();
        if ($xoa)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã xóa"
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xóa'
            ]);
    }
}
