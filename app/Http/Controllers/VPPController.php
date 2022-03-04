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
use App\EventReal;
use App\RefreshSupport;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
  
    public function nhapKhoUpdate(Request $request) {    
        $arrHangHoa = [];
        $arrSoLuong = [];
        $arrDonGia = [];
        for($i = 1; $i <= 30; $i++) {
            if(array_key_exists('rhangHoa' . $i,$request->all())) {
                array_push($arrHangHoa, 'rhangHoa' . $i);
                array_push($arrSoLuong, 'rsoLuong' . $i);
                array_push($arrDonGia, 'rdonGia' . $i);
            }
        }
        $id = $request->idPNUpdate;
        if(empty($arrHangHoa)){
            $oldpn = NhapSP::where('id_nhap',$request->idPNUpdate)->get();
            $nhapsp = NhapSP::where('id_nhap', $request->idPNUpdate)->delete();
            if ($nhapsp) {
                $temp = "";
                foreach($oldpn as $row) {
                    $dmSP = DanhMucSP::find($row->id_danhmuc);
                    $temp .= "<br/>SP: ". $dmSP->tenSanPham ." SL: ". $row->soLuong ." ĐG ". $row->donGia;
                }
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Quản lý nhập kho";
                $nhatKy->noiDung = "Xóa danh mục hàng hóa có trong phiếu PNK-0" 
                . $request->idPNUpdate . "<br/>Nội dung " . $temp;
                $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => 'Đã xóa tất cả danh mục hàng hóa có trong phiếu nhập!',
                ]);   
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'info',
                    'message' => 'Không thể xóa tất cả danh mục sản phẩm trên phiếu!',
                ]); 
        } else {
            $oldpn = NhapSP::where('id_nhap',$request->idPNUpdate)->get();
            $tempold = "";
            foreach($oldpn as $row) {
                $dmSP = DanhMucSP::find($row->id_danhmuc);
                $tempold .= "<br/>SP: ". $dmSP->tenSanPham ." SL: ". $row->soLuong ." ĐG ". $row->donGia;
            }
            $nhapsp = NhapSP::where('id_nhap', $request->idPNUpdate)->delete();
            $temp = "";
            for($i = 0; $i < count($arrHangHoa); $i++) {
                $hangHoa = $request[$arrHangHoa[$i]];
                $soLuong = $request[$arrSoLuong[$i]];
                $donGia = $request[$arrDonGia[$i]];
                $tenDM = DanhMucSP::find($hangHoa)->tenSanPham;
                $temp .= "<br/>SP: ". $tenDM ." SL: ". $soLuong ." ĐG ". $donGia;
                $nhap = new NhapSP();
                $nhap->id_danhmuc = $hangHoa;
                $nhap->id_nhap = $request->idPNUpdate;
                $nhap->soLuong = $soLuong;
                $nhap->donGia = $donGia;
                $nhap->save();                    
            }

            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho";
            $nhatKy->noiDung = "Thực hiện xóa tất cả danh mục trong phiếu PNK-0" 
            . $request->idPNUpdate . ". Nội dung cũ:" . $tempold . "<br/>Thêm danh mục hàng hóa mới vào phiếu PNK-0" 
            . $request->idPNUpdate . ". Nội dung mới: " . $temp;
            $nhatKy->save(); 

            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => 'Đã cập nhật phiếu nhập và danh mục sản phẩm vào kho',
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
                        'message' => "Đã tạo phiếu nhập " . $code,
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
        $oldpn = NhapSP::where('id_nhap',$request->idPN)->get();
        $temp = "";
        foreach($oldpn as $row) {
            $dmSP = DanhMucSP::find($row->id_danhmuc);
            $temp .= "<br/>SP: ". $dmSP->tenSanPham ." SL: ". $row->soLuong ." ĐG ". $row->donGia;
        }
        $xoa->delete();
        if ($xoa) {            
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - Quản lý nhập kho";
            $nhatKy->noiDung = "Xóa phiếu nhập kho PNK-0".$request->idPN." và danh mục hàng hóa có trong phiếu PNK-0".$request->idPN."<br/>Nội dung " . $temp;
            $nhatKy->save(); 
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã xóa phiếu nhập và các hàng hóa trong phiếu"
            ]);
        }
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xóa'
            ]);
    }

    // Yêu cầu công cụ
    public function deNghiCongCuPanel() {
        return view('vpp.denghicongcu');
    }

    public function yeuCauTaoPhieu(Request $request){   
        if ($request->noiDung != '') {
            $newPN = new PhieuXuat();
            $newPN->ngay = Date('d');
            $newPN->thang = Date('m');
            $newPN->nam = Date('Y');
            $newPN->id_user_xuat = Auth::user()->id;
            $newPN->noiDungXuat = $request->noiDung;
            $newPN->save();
            $code = "PXK-0" . $newPN->id;
            if ($newPN) {                
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Tạo đề nghị (phiếu xuất) <strong>".$code."</strong>";
                $nhatKy->save(); 
                    return response()->json([
                        'code' => 200,        
                        'type' => 'info',
                        'message' => " Đã tạo đề nghị công cụ " . $code,
                        'maPhieu' => $code,
                        'idPX' => $newPN->id
                    ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể tạo đề nghị'
                ]);     
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'info',
                'message' => 'Bạn chưa nhập nội dung đề nghị!'
            ]);    
        }        
    }

    public function deNghiLoadPhieu(){
        if (Auth::user()->hasRole('system')) {
            $phieu = PhieuXuat::select("*")           
            ->orderBy('id','desc')
            ->get();
        } else {
            $phieu = PhieuXuat::select("*")
            ->where('id_user_xuat', Auth::user()->id)
            ->orderBy('id','desc')
            ->get();
        }       
        if ($phieu)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải đề nghị công cụ",
                'data' => $phieu
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy'
            ]);
    }

    public function deNghiLoadChiTiet(Request $request){
        $phieuXuat = PhieuXuat::find($request->idPX);
        $xuatsp = XuatSP::select('xuat_sp.soLuong','d.tenSanPham','d.id')
        ->join('danhmuc_sp as d','d.id','=','xuat_sp.id_danhmuc')
        ->where('xuat_sp.id_xuat',$phieuXuat->id)
        ->get();
        if ($xuatsp)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải yêu cầu công cụ",
                'data' => $xuatsp,
                'noiDung' => $phieuXuat->noiDungXuat,
                'ngayXuat' => $phieuXuat->ngay . "-" . $phieuXuat->thang . "-" . $phieuXuat->nam,
                'status' => $phieuXuat->duyet,
                'statusNhan' => $phieuXuat->nhan
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy'
            ]);
    }

    public function yeuCauDelete(Request $request) {
        $xoa = PhieuXuat::find($request->idPX);
        if ($xoa->duyet == false) {
            $xoa->delete();
            if ($xoa) {
                $eventReal = new EventReal;
                $eventReal->name = "Xoa yeu cau";
                $eventReal->save();
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Xóa phiếu yêu cầu công cụ mã phiếu PXK-0" . $request->idPX;
                $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã xóa phiếu yêu cầu công cụ và các hàng hóa trong phiếu"
                ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể xóa'
                ]);
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xóa phiếu đã được duyệt'
            ]);
        }        
    }

    public function deNghiUpdate(Request $request) {    
        $phieuXuat = PhieuXuat::find($request->idPXUpdate);
        if ($phieuXuat->duyet == false) {
            $arrHangHoa = [];
            $arrSoLuong = [];
            for($i = 1; $i <= 30; $i++) {
                if(array_key_exists('rhangHoa' . $i,$request->all())) {
                    array_push($arrHangHoa, 'rhangHoa' . $i);
                    array_push($arrSoLuong, 'rsoLuong' . $i);
                }
            }
            $id = $request->idPXUpdate;
            if(empty($arrHangHoa)){
                $xuatsp = XuatSP::where('id_xuat', $request->idPXUpdate)->delete();
                if ($xuatsp) {
                    $eventReal = new EventReal;
                    $eventReal->name = "Update CCDC";
                    $eventReal->save();
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                    $nhatKy->noiDung = "Xóa tất cả công cụ/dụng cụ trong phiếu yêu cầu PXK-0" . $request->idPXUpdate;
                    $nhatKy->save(); 
                    return response()->json([
                        'code' => 200,        
                        'type' => 'info',
                        'message' => 'Đã xóa tất cả danh mục hàng hóa có trong yêu cầu công cụ!',
                    ]);   
                }
                else
                    return response()->json([
                        'code' => 500,        
                        'type' => 'info',
                        'message' => 'Không thể xóa tất cả danh mục sản phẩm trên phiếu!',
                    ]); 
            } else {
                $xuatsp = XuatSP::where('id_xuat', $request->idPXUpdate)->delete();
                for($i = 0; $i < count($arrHangHoa); $i++) {
                    $hangHoa = $request[$arrHangHoa[$i]];
                    $soLuong = $request[$arrSoLuong[$i]];
                    $nhap = new XuatSP();
                    $nhap->id_danhmuc = $hangHoa;
                    $nhap->id_xuat = $request->idPXUpdate;
                    $nhap->soLuong = $soLuong;
                    $nhap->save();                    
                }
                $eventReal = new EventReal;
                $eventReal->name = "Update CCDC";
                $eventReal->save();

                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Cập nhật công cụ/dụng cụ trong phiếu yêu cầu PXK-0" . $request->idPXUpdate;
                $nhatKy->save(); 

                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => 'Đã cập nhật yêu cầu công cụ và danh mục hàng hóa yêu cầu vào phiếu',
                ]);      
            }             
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Phiếu đã duyệt không thể chỉnh sửa'
            ]);   
        }        
    }

    public function nhanHang(Request $request) {
        $px = PhieuXuat::find($request->idPX);
        if ($px->duyet == true) {
            $px->nhan = true;
            $px->save();
            if ($px) {
                $eventReal = new EventReal;
                $eventReal->name = "Xác nhận hàng";
                $eventReal->save();
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Xác nhận nhận đủ công cụ/dụng cụ mã phiếu PXK-0" . $request->idPX;
                $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã xác nhận nhận đầy đủ công cụ dụng cụ!"
                ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể xác nhận'
                ]);
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể xác nhận vì chưa được duyệt'
            ]);
        }        
    }

    // Quản lý xuất kho
    public function quanLyXuatKhoPanel(){
        return view('vpp.quanlyxuatkho');
    }

    public function xuatKhoLoadPhieu(){
        $phieu = PhieuXuat::select("*")           
        ->orderBy('id','desc')
        ->get();
        if ($phieu)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải đề nghị công cụ",
                'data' => $phieu
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy'
            ]);
    }

    public function xuatKhoLoadChiTiet(Request $request){
        $phieuXuat = PhieuXuat::find($request->idPX);
        $xuatsp = XuatSP::select('xuat_sp.soLuong','d.tenSanPham','d.id')
        ->join('danhmuc_sp as d','d.id','=','xuat_sp.id_danhmuc')
        ->where('xuat_sp.id_xuat',$phieuXuat->id)
        ->get();
        if ($xuatsp)
            return response()->json([
                'code' => 200,        
                'type' => 'info',
                'message' => "Đã tải yêu cầu công cụ",
                'data' => $xuatsp,
                'noiDung' => $phieuXuat->noiDungXuat,
                'ngayXuat' => $phieuXuat->ngay . "-" . $phieuXuat->thang . "-" . $phieuXuat->nam,
                'user' => $phieuXuat->userXuat->userDetail->surname,
                'status' => $phieuXuat->duyet,
                'statusNhan' => $phieuXuat->nhan
            ]);
        else
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không tìm thấy'
            ]);
    }

    public function refreshPage() {
        $_eve = EventReal::select('*')->get()->count();     
        $point = RefreshSupport::select("*")->orderBy('id','desc')->first();   
        if (!$point)
            $point = 0;
        else
            $point = $point->point;
        $data = [];                 
        if ((int)$_eve != (int)$point) {            
            $data = [                
                'flag' => true
            ];
            $newPoint = new RefreshSupport();
            $newPoint->point = $_eve;
            $newPoint->save();
        } else {
            $data = [                
                'flag' => false
            ];
        }
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->setCallback(
            function() use ($data) {
                    echo "data: ".json_encode($data)."\n\n";
                    flush();
            });
        $response->send();
    }

    public function duyetPhieu(Request $request) {
        $phieu = PhieuXuat::find($request->phieu);
        $flag = true;
        $sanPham = "";
        $xuatSP = XuatSP::where('id_xuat', $request->phieu)->get();
        foreach($xuatSP as $xuat) {
            $newPN = NhapSP::select('id_danhmuc', DB::raw('sum(soLuong) as soLuong'))           
            ->groupBy('id_danhmuc')
            ->having('id_danhmuc', $xuat->id_danhmuc)
            ->first();
            $soLuongNhap = (isset($newPN) ? $newPN->soLuong : 0);
            $newPX = XuatSP::where('id_danhmuc',$xuat->id_danhmuc)->get();
            $soLuongXuat = 0;
            foreach($newPX as $item) {
                $px = PhieuXuat::find($item->id_xuat);
                if ($px->duyet == true && $px->nhan == true) {
                    $soLuongXuat += $item->soLuong;
                }
            }
            if ($xuat->soLuong > ($soLuongNhap - $soLuongXuat)) {
                $sanPham .= " ;". DanhMucSP::find($xuat->id_danhmuc)->tenSanPham;
                $flag = false;
            }            
        }

        if ($flag) {
            $phieu->duyet = true;
            $phieu->save();
            if ($phieu) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Duyệt yêu cầu công cụ/dụng cụ mã phiếu PXK-0" . $request->phieu;
                $nhatKy->save(); 
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã duyệt yêu cầu. Đang đồng bộ và tải lại danh sách phiếu...."
                ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể duyệt yêu cầu'
                ]);
        } else {
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => "Không đủ số lượng [".$sanPham."] để duyệt"
            ]);
        }
    }

    public function huyDuyetPhieu(Request $request) {
        $phieu = PhieuXuat::find($request->phieu);
        if ($phieu->nhan == true) {
            return response()->json([
                'code' => 500,        
                'type' => 'error',
                'message' => 'Không thể hủy (hoàn trạng) yêu cầu vì nhân viên đã xác nhận CCDC'
            ]);
        } else {
            $phieu->duyet = false;
            $phieu->save();
            if ($phieu) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - Đề nghị công cụ";
                $nhatKy->noiDung = "Hủy duyệt(hoàn trạng) yêu cầu công cụ/dụng cụ mã phiếu PXK-0" . $request->phieu;
                $nhatKy->save();
                return response()->json([
                    'code' => 200,        
                    'type' => 'info',
                    'message' => "Đã HỦY duyệt phiếu. Đang đồng bộ và tải lại danh sách phiếu...."
                ]);
            }
            else
                return response()->json([
                    'code' => 500,        
                    'type' => 'error',
                    'message' => 'Không thể duyệt yêu cầu'
                ]);
        }        
    }

    // Báo cáo kho
    public function baoCaoKhoPanel() {
        return view('vpp.baocaokho');
    }

    public function tonKhoThucTe(Request $request){
        $listDM = DanhMucSP::all();
        $i = 1;
        foreach($listDM as $row) {
            $newPN = NhapSP::select('id_danhmuc', DB::raw('sum(soLuong) as soLuong'))           
            ->groupBy('id_danhmuc')
            ->having('id_danhmuc', $row->id)
            ->first(); 
            $newPX = XuatSP::where('id_danhmuc',$row->id)->get();
            $soLuongNhap = (isset($newPN) ? $newPN->soLuong : 0);
            $soLuongXuat = 0;
            foreach($newPX as $item) {
                $px = PhieuXuat::find($item->id_xuat);
                if ($px->duyet == true && $px->nhan == true) {
                    $soLuongXuat += $item->soLuong;
                }
            }

            echo "<tr class='text-center'>
                    <td>".$i++."</td>
                    <td>".$row->tenSanPham."</td>
                    <td>".$row->moTa."</td>
                    <td class='text-primary text-bold'>". ($soLuongNhap != 0 ? $soLuongNhap. " " .$row->donViTinh : "") ."</td>
                    <td class='text-danger text-bold'>". ($soLuongXuat != 0 ? $soLuongXuat. " " .$row->donViTinh : "") ."</td>
                    <td class='text-success text-bold'>".(($soLuongNhap - $soLuongXuat) != 0 ? ($soLuongNhap - $soLuongXuat). " " .$row->donViTinh : "")."</td>
                </tr>";
        }
    }

    public function bienDongKho(Request $request){       
        $tuNgay = \HelpFunction::revertDate($request->tuNgay);
        $denNgay = \HelpFunction::revertDate($request->denNgay);        
        $listDM = DanhMucSP::all();
      
        $i = 1;
        foreach($listDM as $row) {
            $maPhieu = "";
            $nhanVien = "";
            $soLuongNhap = 0;
            $soLuongXuat = 0;
            $newPN = NhapSP::where('id_danhmuc', $row->id)->get(); 
            $newPX = XuatSP::where('id_danhmuc',$row->id)->get();

            foreach($newPN as $nhap) {
                $pn = PhieuNhap::find($nhap->id_nhap);
                // dd($denNgay . " --- " . ($pn->ngay."/".$pn->thang."/".$pn->nam));
                if ((strtotime($pn->ngay."-".$pn->thang."-".$pn->nam) >= strtotime($tuNgay)) 
                    &&  (strtotime($pn->ngay."-".$pn->thang."-".$pn->nam) <= strtotime($denNgay))) {
                        $soLuongNhap += $nhap->soLuong;
                    }
            }           
           
            foreach($newPX as $item) {
                $px = PhieuXuat::find($item->id_xuat);
                if ((strtotime($px->ngay."-".$px->thang."-".$px->nam) >= strtotime($tuNgay)) 
                    &&  (strtotime($px->ngay."-".$px->thang."-".$px->nam) <= strtotime($denNgay))) {
                        if ($px->duyet == true && $px->nhan == true) {
                            $soLuongXuat += $item->soLuong;
                            $maPhieu .= "PXK-0" . $px->id . " <span class='badge badge-secondary'>".$item->soLuong."</span><br/>";
                            $nhanVien .= $px->userXuat->userDetail->surname . " <span class='badge badge-secondary'>".$item->soLuong."</span><br/>";
                        }
                    }               
            }
            echo "<tr class='text-center'>
                <td>".$i++."</td>
                <td>".$row->tenSanPham."</td>
                <td>".$row->moTa."</td>
                <td class='text-success text-bold'>".($soLuongNhap != 0 ? $soLuongNhap. " " .$row->donViTinh : "")."</td>
                <td class='text-danger text-bold'>".($soLuongXuat != 0 ? $soLuongXuat. " " .$row->donViTinh : "")."</td>
                <td>".$maPhieu."</td>
                <td>".$nhanVien."</td>
            </tr>";
        }     
        
    }

    public function yeuCauDaDuyet(Request $request){       
        $tuNgay = \HelpFunction::revertDate($request->tuNgay);
        $denNgay = \HelpFunction::revertDate($request->denNgay);        
        $px = PhieuXuat::select("*")->where([
            ['duyet','=',true]
        ])->get();
        $i = 1;
        foreach($px as $row) {
            $_day = $row->ngay."-".$row->thang."-".$row->nam;
            $_nv = $row->userXuat->userDetail->surname;
            $_dayDuyet = \HelpFunction::getDateRevertCreatedAt($row->updated_at);
            $_noiDung = $row->noiDungXuat;
            $_maPhieu = "PXK-0".$row->id;
            $_status = ($row->nhan) ? "<strong class='text-success'>Đã nhận</strong>" : "<strong class='text-danger'>Chưa nhận</strong>";
            $_dm = "";
            if ((strtotime($_day) >= strtotime($tuNgay)) 
                &&  (strtotime($_day) <= strtotime($denNgay))) {
                    $xuat = XuatSP::where('id_xuat', $row->id)->get();
                    foreach($xuat as $item) {
                        $dm = DanhMucSP::find($item->id_danhmuc);
                        $_dm .= "<span>".$dm->tenSanPham.": <span class='badge badge-info'>".$item->soLuong." ".$dm->donViTinh."</span></span><br/>";
                    }
                }
            echo "<tr class='text-center'>
                <td>".$i++."</td>
                <td>".$_day." <span class='text-success'>(".$_dayDuyet.")</span></td>
                <td>".$_nv."</td>
                <td>".$_noiDung."</td>
                <td>".$_maPhieu."</td>
                <td>
                    ".$_dm."
                </td> 
                <td>".$_status."</td>                                           
            </tr>";         
        }
    }

    public function yeuCauDoiDuyet(Request $request){       
        $tuNgay = \HelpFunction::revertDate($request->tuNgay);
        $denNgay = \HelpFunction::revertDate($request->denNgay);        
        $px = PhieuXuat::select("*")->where([
            ['duyet','=',false]
        ])->get();
        $i = 1;
        foreach($px as $row) {
            $_day = $row->ngay."-".$row->thang."-".$row->nam;
            $_nv = $row->userXuat->userDetail->surname;
            $_dayDuyet = \HelpFunction::getDateRevertCreatedAt($row->updated_at);
            $_noiDung = $row->noiDungXuat;
            $_maPhieu = "PXK-0".$row->id;
            $_dm = "";
            $_sl = "";
            if ((strtotime($_day) >= strtotime($tuNgay)) 
                &&  (strtotime($_day) <= strtotime($denNgay))) {
                    $xuat = XuatSP::where('id_xuat', $row->id)->get();
                    foreach($xuat as $item) {
                        $dm = DanhMucSP::find($item->id_danhmuc);
                        $_dm .= "<span>".$dm->tenSanPham.": <span class='badge badge-info'>".$item->soLuong." ".$dm->donViTinh."</span></span><br/>";
                        
                        // Kiểm tra tồn kho
                        $newPN = NhapSP::select('id_danhmuc', DB::raw('sum(soLuong) as soLuong'))           
                        ->groupBy('id_danhmuc')
                        ->having('id_danhmuc', $item->id_danhmuc)
                        ->first();
                        $soLuongNhap = (isset($newPN) ? $newPN->soLuong : 0);
                        $newPX = XuatSP::where('id_danhmuc',$item->id_danhmuc)->get();
                        $soLuongXuat = 0;
                        foreach($newPX as $itemx) {
                            $px = PhieuXuat::find($itemx->id_xuat);
                            if ($px->duyet == true && $px->nhan == true) {
                                $soLuongXuat += $itemx->soLuong;
                            }
                        }
                        // --------
                        $_sl .= "<span><span class='badge badge-warning'>".($soLuongNhap - $soLuongXuat)." ".$dm->donViTinh."</span></span><br/>";
                    }
                }

            echo "<tr class='text-center'>
                <td>".$i++."</td>
                <td>".$_day."</td>
                <td>".$_nv."</td>
                <td>".$_noiDung."</td>
                <td>".$_maPhieu."</td>
                <td class='text-left'>
                    ".$_dm."
                </td>      
                <td>
                    ".$_sl."
                </td>                                             
            </tr>";         
        }
    }

    public function nhapKhoChiTiet(Request $request){       
        $tuNgay = \HelpFunction::revertDate($request->tuNgay);
        $denNgay = \HelpFunction::revertDate($request->denNgay);        
        $pn = PhieuNhap::all();
        $i = 1;
        foreach($pn as $row) {
            $_day = $row->ngay."-".$row->thang."-".$row->nam;
            $_nv = $row->user->userDetail->surname;
            $_noiDung = $row->noiDungNhap;
            $_maPhieu = "PNK-0".$row->id;
            $_dm = "";
            $_sl = "";
            $_dg = "";
            if ((strtotime($_day) >= strtotime($tuNgay)) 
                &&  (strtotime($_day) <= strtotime($denNgay))) {
                    $nhap = NhapSP::where('id_nhap', $row->id)->get();
                    foreach($nhap as $item) {
                        $dm = DanhMucSP::find($item->id_danhmuc);
                        $_dm .= "<span>".$dm->tenSanPham." </span><br/>";
                        $_sl .= $item->soLuong." ".$dm->donViTinh."<br/>";
                        $_dg .= number_format($item->donGia)."<br/>";
                    }

                    echo "<tr class='text-center'>
                            <td>".$i++."</td>
                            <td>".$_day."</td>
                            <td>".$_nv."</td>
                            <td>".$_maPhieu."</td>
                            <td>".$_noiDung."</td>
                            <td class='text-left'>
                                ".$_dm."                                           
                            </td>      
                            <td class='text-info text-bold'>
                                ".$_sl."                                                       
                            </td>  
                            <td>
                                ".$_dg."                                              
                            </td>                                             
                        </tr>";    
                }              
        }
    }
}
