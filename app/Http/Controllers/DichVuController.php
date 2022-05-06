<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GuestDv;
use App\BHPK;
use App\User;
use App\HopDong;
use App\KhoV2;
use App\ChiTietBHPK;
use App\BaoGiaBHPK;
use Illuminate\Support\Facades\Auth;
use App\NhatKy;
use PhpOffice\PhpWord\TemplateProcessor;

class DichVuController extends Controller
{
    //
    public function phuKienPanel() {
        $user = User::all();
        return view('dichvu.quanlyphukien',['user' => $user]);
    }

    public function baoHiemPanel() {
        $user = User::all();
        return view('dichvu.quanlybaohiem',['user' => $user]);
    }

    public function khachHangPanel() {
        return view('dichvu.khachhang');
    }

    public function getKhachHang() {
        if (Auth::user()->hasRole('system'))
            $guest = GuestDv::select("*")->orderBy('id','desc')->get();
        else
            $guest = GuestDv::select("*")->where('id_user_create',Auth::user()->id)->orderBy('id','desc')->get();   
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'Đã tải dữ liệu',
            'data' => $guest
        ]);
    }

    public function addKhachHang(Request $request) {
        $kh = new GuestDv();
        $kh->id_user_create = Auth::user()->id;
        $kh->hoTen = $request->hoTen;
        $kh->dienThoai = $request->dienThoai;
        $kh->mst = $request->mst;
        $kh->diaChi = $request->diaChi;
        $kh->bienSo = $request->bienSo;
        $kh->soKhung = $request->soKhung;
        $kh->soMay = $request->soMay;
        $kh->thongTinXe = $request->thongTinXe;
        $kh->taiXe = $request->taiXe;
        $kh->dienThoaiTaiXe = $request->dienThoaiTaiXe;
        $kh->save();
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý khách hàng";
            $nhatKy->noiDung = "Thêm khách hàng: "
            .$request->hoTen."; điện thoại: "
            .$request->dienThoai."; MST: "
            .$request->mst."; Địa chỉ: "
            .$request->diaChi."; Biển số: "
            .$request->bienSo."; Số khung: "
            .$request->soKhung."; Số máy: "
            .$request->soMay."; Thông tin xe: "
            .$request->thongTinXe."; Liên hệ: "
            .$request->taiXe."; Điện thoại: "
            .$request->dienThoaiTaiXe.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã thêm khách hàng'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function delKhachHang(Request $request) {
        $kh = GuestDv::find($request->id);
        $temp = $kh;
        if (Auth::user()->hasRole('system'))
            $kh->delete();
        elseif(Auth::user()->id == $kh->id_user_create) {
            $kh->delete();
        } else return response()->json([
            'type' => 'error',
            'code' => 500,
            'message' => 'Bạn không có quyền xoá nội dung này'
        ]);

        if ($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý khách hàng: ";
            $nhatKy->noiDung = "Xoá khách hàng: "
            .$temp->hoTen."; điện thoại: "
            .$temp->dienThoai."; MST: "
            .$temp->mst."; Địa chỉ: "
            .$temp->diaChi."; Biển số: "
            .$temp->bienSo."; Số khung: "
            .$temp->soKhung."; Số máy: "
            .$temp->soMay."; Thông tin xe: "
            .$temp->thongTinXe."; Liên hệ: "
            .$temp->taiXe."; Điện thoại: "
            .$temp->dienThoaiTaiXe.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã xoá'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi xoá'
            ]);
    }

    public function getKhachHangEdit(Request $request) {
        $kh = GuestDv::find($request->id);
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'Đã tải',
            'data' => $kh
        ]);
    }

    public function updateKhachHang(Request $request) {
        $kh = GuestDv::find($request->eid);
        $temp = $kh;
        $kh->hoTen = $request->ehoTen;
        $kh->dienThoai = $request->edienThoai;
        $kh->mst = $request->emst;
        $kh->diaChi = $request->ediaChi;
        $kh->bienSo = $request->ebienSo;
        $kh->soKhung = $request->esoKhung;
        $kh->soMay = $request->esoMay;
        $kh->thongTinXe = $request->ethongTinXe;
        $kh->taiXe = $request->etaiXe;
        $kh->dienThoaiTaiXe = $request->edienThoaiTaiXe;
        $kh->save();
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý khách hàng";
            $nhatKy->noiDung = "Cập nhật khách hàng thông tin cũ: <br/>"
            .$temp->hoTen."; điện thoại: "
            .$temp->dienThoai."; MST: "
            .$temp->mst."; Địa chỉ: "
            .$temp->diaChi."; Biển số: "
            .$temp->bienSo."; Số khung: "
            .$temp->soKhung."; Số máy: "
            .$temp->soMay."; Thông tin xe: "
            .$temp->thongTinXe."; Liên hệ: "
            .$temp->taiXe."; Điện thoại: "
            .$temp->dienThoaiTaiXe.";<br/>Cập nhật khách hàng thông tin mới: <br/>"
            .$request->ehoTen."; điện thoại: "
            .$request->edienThoai."; MST: "
            .$request->emst."; Địa chỉ: "
            .$request->ediaChi."; Biển số: "
            .$request->ebienSo."; Số khung: "
            .$request->esoKhung."; Số máy: "
            .$request->esoMay."; Thông tin xe: "
            .$request->ethongTinXe."; Liên hệ: "
            .$request->etaiXe."; Điện thoại: "
            .$request->edienThoaiTaiXe.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã cập nhật khách hàng'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function hangMucPanel(){
        return view('dichvu.hangmuc');
    }

    public function getHangMuc() {
        if (Auth::user()->hasRole('system'))
            $bhpk = BHPK::select("*")->orderBy('id','desc')->get();
        else
            $bhpk = BHPK::select("*")->where('id_user_create',Auth::user()->id)->orderBy('id','desc')->get();   
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'Đã tải dữ liệu',
            'data' => $bhpk
        ]);
    }

    public function addHangMuc(Request $request) {
        $kh = new BHPK();
        $kh->id_user_create = Auth::user()->id;
        $kh->isPK = $request->isPK;
        $kh->ma = strtoupper($request->ma);
        $kh->noiDung = $request->noiDung;
        $kh->dvt = $request->dvt;
        $kh->donGia = $request->donGia;
        $kh->type = $request->loai;        
        $kh->save();
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý hạng mục";
            $nhatKy->noiDung = "Thêm hạng mục: "
            .$request->noiDung."; Phần (1: Phụ kiện, 2: Bảo hiểm): "
            .$request->isPK."; Mã: "
            .$request->ma."; Đơn vị tính: "
            .$request->dvt."; Đơn giá: "
            .$request->donGia."; Loại: "
            .$request->type.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã thêm hạng mục'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function delHangMuc(Request $request) {
        $kh = BHPK::find($request->id);
        $temp = $kh;
        if (Auth::user()->hasRole('system'))
            $kh->delete();
        elseif(Auth::user()->id == $kh->id_user_create) {
            $kh->delete();
        } else return response()->json([
            'type' => 'error',
            'code' => 500,
            'message' => 'Bạn không có quyền xoá nội dung này'
        ]);

        if ($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý hạng mục";
            $nhatKy->noiDung = "Xoá hạng mục: "
            .$temp->noiDung."; Phần (1: Phụ kiện, 2: Bảo hiểm)"
            .$temp->isPK."; Mã: "
            .$temp->ma."; Đơn vị tính: "
            .$temp->dvt."; Đơn giá: "
            .$temp->donGia."; Loại: "
            .$temp->type.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã xoá'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi xoá'
            ]);
    }
    public function getHangMucEdit(Request $request) {
        $kh = BHPK::find($request->id);
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'Đã tải',
            'data' => $kh
        ]);
    }

    public function updateHangMuc(Request $request) {
        $kh = BHPK::find($request->eid);
        $temp = $kh;
        $kh->isPK = $request->eisPK;
        $kh->ma = strtoupper($request->ema);
        $kh->noiDung = $request->enoiDung;
        $kh->dvt = $request->edvt;
        $kh->donGia = $request->edonGia;
        $kh->type = $request->eloai;        
        $kh->save();
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý hạng mục";
            $nhatKy->noiDung = "Cập nhật hạng mục: <br/>Nội dung cũ<br/>"
            .$temp->noiDung."; Phần (1: Phụ kiện, 2: Bảo hiểm)"
            .$temp->isPK."; Mã: "
            .$temp->ma."; Đơn vị tính: "
            .$temp->dvt."; Đơn giá: "
            .$temp->donGia."; Loại: "
            .$temp->type.";<br/>Nội dung mới<br/>"
            .$request->enoiDung."; Phần (1: Phụ kiện, 2: Bảo hiểm)"
            .$request->eisPK."; Mã: "
            .$request->ema."; Đơn vị tính: "
            .$request->edvt."; Đơn giá: "
            .$request->edonGia."; Loại: "
            .$request->etype.";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã cập nhật hạng mục'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }
    // quan ly phu kien
    public function timHopDong(Request $request) {
        $hd = HopDong::where('code',$request->findVal)->first();
        $hopDong = $hd->code.".".$hd->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($hd->created_at)."/HĐMB-PA";
        $nhanVien = $hd->user->userDetail->surname;
        $hoTen = $hd->guest->name;
        $diaChi= $hd->guest->address;
        $dienThoai = $hd->guest->phone;
        $mst = $hd->guest->mst;
        $kho = KhoV2::find($hd->id_car_kho);
        $soKhung = $kho->vin;
        $soMay = $kho->frame;

        $car = $hd->carSale;
        $carname = $car->name;
        $seat = $car->seat;
        $fuel = $car->fuel;
        $color = $hd->mau;
        $thongTinXe =  $carname . "; Màu: ". $color."; ".$seat." chỗ; Nhiên liệu: ".$fuel;

        if ($hd)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tìm được dữ liệu khách hàng',
                'hopDong' => $hopDong,
                'nhanVien' => $nhanVien,
                'hoTen' => $hoTen,
                'dienThoai' => $dienThoai,
                'mst' => $mst,
                'diaChi' => $diaChi,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'thongTinXe' => $thongTinXe,
            ]);
        else
            return response()->json([
                'type' => 'warning',
                'code' => 500,
                'message' => 'Không tìm thấy dữ liệu khách hàng'
            ]);
    }

    public function timKhachHang(Request $request) {
        $guest = GuestDv::where('dienThoai',$request->findVal)->first();
        $hoTen = $guest->hoTen;
        $diaChi= $guest->diaChi;
        $dienThoai = $guest->dienThoai;
        $mst =  $guest->mst;
        $bienSo = $guest->bienSo;
        $soKhung = $guest->soKhung;
        $soMay = $guest->soMay;
        $thongTinXe = $guest->thongTinXe;
        $taiXe = $guest->taiXe;
        $dienThoaiTaiXe = $guest->dienThoaiTaiXe;

        if ($guest)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tìm được dữ liệu khách hàng',
                'hoTen' => $hoTen,
                'dienThoai' => $dienThoai,
                'mst' => $mst,
                'diaChi' => $diaChi,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'thongTinXe' => $thongTinXe,
                'bienSo' => $bienSo,
                'taiXe' => $taiXe,
                'dienThoaiTaiXe' => $dienThoaiTaiXe,
            ]);
        else
            return response()->json([
                'type' => 'warning',
                'code' => 500,
                'message' => 'Không tìm thấy dữ liệu khách hàng'
            ]);
    }

    public function postBaoGia(Request $request) {
        $bg = new BaoGiaBHPK();
        $bg->id_user_create = Auth::user()->id;
        $bg->isPKD = $request->isPKD;
        if (Auth::user()->hasRole('nv_baohiem')) {
            $bg->isBaoHiem = true;
        } elseif (Auth::user()->hasRole('nv_phukien')) {
            $bg->isBaoHiem = false;
        } else {
            $bg->isBaoHiem = $request->isBaoHiem;
        }
        $bg->hopDongKD = $request->hopDong;
        $bg->nvKD = $request->nhanVien;
        $bg->thoiGianVao = $request->gioVao;
        $bg->ngayVao = $request->ngayVao;
        $bg->thoiGianHoanThanh = $request->gioRa;
        $bg->ngayHoanThanh = $request->ngayRa;
        $bg->hoTen = $request->hoTen;
        $bg->dienThoai = $request->dienThoai;
        $bg->mst = $request->mst;
        $bg->diaChi = $request->diaChi;
        $bg->bienSo = $request->bienSo;
        $bg->soKhung = $request->soKhung;
        $bg->soMay = $request->soMay;
        $bg->thongTinXe = $request->thongTinXe;
        $bg->taiXe = $request->taiXe;
        $bg->dienThoaiTaiXe = $request->dienThoaiTaiXe;
        $bg->yeuCau = $request->yeuCau;
        $bg->inProcess = false;
        $bg->isDone = false;
        $bg->isCancel = false;
        $bg->save();
        //--------Auto add guest
        //--- check guest exist
        $check = GuestDv::where('dienThoai',$request->dienThoai)->exists();
        if (!$check) {
            $kh = new GuestDv();
            $kh->id_user_create = Auth::user()->id;
            $kh->hoTen = $request->hoTen;
            $kh->dienThoai = $request->dienThoai;
            $kh->mst = $request->mst;
            $kh->diaChi = $request->diaChi;
            $kh->bienSo = $request->bienSo;
            $kh->soKhung = $request->soKhung;
            $kh->soMay = $request->soMay;
            $kh->thongTinXe = $request->thongTinXe;
            $kh->taiXe = $request->taiXe;
            $kh->dienThoaiTaiXe = $request->dienThoaiTaiXe;
            $kh->save();


            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Thêm khách hàng: ".$request->hoTen."; điện thoại: ".$request->dienThoai." tự động từ báo giá bảo hiểm, phụ kiện";
            $nhatKy->save();
        }
        //
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Tạo báo giá: BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tạo báo giá',
                'data' => $bg,
                'soBG' => "BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at),
                'isBaoHiem' => $bg->isBaoHiem,
                'idBG' => $bg->id
            ]);
        }
        else
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function editBaoGia(Request $request) {
        $bg = BaoGiaBHPK::find($request->eid);
        $bg->isPKD = $request->isPKD;
        $bg->hopDongKD = $request->hopDong;
        $bg->nvKD = $request->nhanVien;
        $bg->thoiGianVao = $request->gioVao;
        $bg->ngayVao = $request->ngayVao;
        $bg->thoiGianHoanThanh = $request->gioRa;
        $bg->ngayHoanThanh = $request->ngayRa;
        $bg->hoTen = $request->hoTen;
        $bg->dienThoai = $request->dienThoai;
        $bg->mst = $request->mst;
        $bg->diaChi = $request->diaChi;
        $bg->bienSo = $request->bienSo;
        $bg->soKhung = $request->soKhung;
        $bg->soMay = $request->soMay;
        $bg->thongTinXe = $request->thongTinXe;
        $bg->taiXe = $request->taiXe;
        $bg->dienThoaiTaiXe = $request->dienThoaiTaiXe;
        $bg->yeuCau = $request->yeuCau;
        $bg->save();
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Chỉnh sửa báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã chỉnh sửa báo giá',
                'data' => $bg,
                'soBG' => "BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at),
                'isBaoHiem' => $bg->isBaoHiem,
                'idBG' => $bg->id
            ]);
        }
        else
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function timKiem(Request $request) {
        $_from = $request->tu;
        $_to = $request->den;
        switch($request->baoCao) {
            case 1: {
                if (Auth::user()->hasRole('system'))
                    $bg = BaoGiaBHPK::select("*")
                    ->orderBy('id','desc')
                    ->get();
                else
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
            } break;
            case 2: {
                if (Auth::user()->hasRole('system'))
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['inProcess','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                else
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['id_user_create','=',Auth::user()->id],
                        ['inProcess','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
            } break;
            case 3: {
                if (Auth::user()->hasRole('system'))
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['inProcess','=',true],
                        ['isDone','=',false],
                        ['isCancel','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                else
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['id_user_create','=',Auth::user()->id],
                        ['inProcess','=',true],
                        ['isDone','=',false],
                        ['isCancel','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
            } break;
            case 4: {
                if (Auth::user()->hasRole('system'))
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isDone','=',true],
                        ['isCancel','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                else
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['id_user_create','=',Auth::user()->id],
                        ['isDone','=',true],
                        ['isCancel','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
            } break;
            case 5: {
                if (Auth::user()->hasRole('system'))
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isCancel','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                else
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['id_user_create','=',Auth::user()->id],
                        ['isCancel','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
            } break;
            default: abort(403);
        }
        foreach($bg as $row) {
            $stt = "";
            if (!$row->inProcess)
                $stt = "class='bg-secondary'";
            if ($row->inProcess && !$row->isDone && !$row->isCancel)
                $stt = "class='bg-success'";
            if ($row->inProcess && $row->isDone && !$row->isCancel)
                $stt = "class='bg-info'";
            if ($row->isCancel)
                $stt = "class='bg-danger'";
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                echo "
                <tr id='tes' data-id='".$row->id."' ".$stt.">
                    <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                    <td>".$row->bienSo."</td>
                    <td>".$row->soKhung."</td>
                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                </tr>";
            }
        }      
    }

    public function loadBaoGia(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tải báo giá',
                'data' => $bg,
                'soBG' => "BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at),
            ]);
        else
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function deleteBaoGia(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        if (!$bg->isDone || !$bg->inProcess || !$bg->isCancel) {
            $bg->delete();
            if ($bg) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Xoá báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã xoá báo giá'
                ]);
            }
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi'
                ]);
        } else {
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Báo giá đang thực hiện, hoàn tất, huỷ không thể xoá!'
            ]);
        }
    }

    public function thucHienBaoGia(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        $bg->inProcess = true;
        $bg->save();
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Thực hiện báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tiến hành thực hiện báo giá',
                'data' => $bg
            ]);
        }
        else
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function huyBaoGia(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->inProcess) {
            $bg->isCancel = true;
            $bg->lyDoHuy = $request->lyDo;
            $bg->save();
            if ($bg) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Huỷ báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã huỷ báo giá',
                    'data' => $bg
                ]);
            }
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi'
                ]);
        } else {
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Báo giá chưa tiến hành chưa thể huỷ'
            ]);
        }
    }

    public function doneBaoGia(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->inProcess) {
            $bg->isDone = true;
            $bg->save();
            if ($bg) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Hoàn tất báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã hoàn tất báo giá',
                    'data' => $bg
                ]);
            }
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi'
                ]);
        } else {
            return response()->json([
                'type' => 'info',
                'code' => 500,
                'message' => 'Báo giá chưa tiến hành chưa thể hoàn tất'
            ]);
        }
    }

    public function taiHangMuc(Request $request) {
        $item = BHPK::where([
            ['isPK','=',$request->boPhan],
            ['type','=',$request->hangMuc]
        ])->orderBy('noiDung', 'asc')
        ->get();
        if ($item)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã tải danh sách hạng mục',
                    'data' => $item
                ]);
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi'
                ]);
    }

    public function taiBHPK(Request $request) {
        $item = BHPK::find($request->eid);
        if ($item)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Load!',
                    'data' => $item
                ]);
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi'
                ]);
    }

    public function luuBHPK(Request $request) {
        $ct = new ChiTietBHPK();
        $ct->id_baogia = $request->bgid;
        $ct->id_baohiem_phukien = $request->hangMucChiTiet;
        $ct->soLuong = $request->soLuong;
        $ct->donGia = $request->donGia;
        $ct->chietKhau = $request->chietKhau;
        $ct->isTang = $request->tang;
        $ct->id_user_work = ($request->kyThuatVien == 0) ? null : $request->kyThuatVien;
        $ct->thanhTien = ($request->soLuong * $request->donGia) - $request->chietKhau;
        $ct->id_user_work_two = ($request->kyThuatVienTwo == 0) ? null : $request->kyThuatVienTwo;
        $ct->tiLe = ($request->tiLe == 0) ? 10 : $request->tiLe;
        $ct->save();
        $pk = BHPK::find($request->hangMucChiTiet);
        if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Lưu hạng mục cho báo giá: BG0".$request->bgid.";"
                .$pk->noiDung."; Số lượng: "
                .$request->soLuong."; Đơn giá: "
                .$request->donGia."; Chiết khấu: "
                .$request->chietKhau."; Tặng (1: Có; 0: Không): "
                .$request->tang."; Tỉ lệ công "
                .$request->tiLe."/".(10 - $request->tiLe)."; Chiết khấu: ";
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã lưu!'
                ]);
            }
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi '
                ]);
    }

    public function delHM(Request $request) {
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->isDone || $bg->isCancel) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Báo giá đã huỷ, đã hoàn tất không thể xoá hạng mục!'
            ]);
        } else {
            $temp = ChiTietBHPK::where([
                ['id_baogia','=', $request->eid],
                ['id_baohiem_phukien','=', $request->ehm],
            ])->first();
            $bhpk = BHPK::find($request->ehm);
            $ct = ChiTietBHPK::where([
                ['id_baogia','=', $request->eid],
                ['id_baohiem_phukien','=', $request->ehm],
            ])->delete();        
            if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Xoá hạng mục cho báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";"
                .$bhpk->noiDung."; Số lượng: "
                .$temp->soLuong."; Đơn giá: "
                .$temp->donGia."; Chiết khấu: "
                .$temp->chietKhau."; Tặng (1: Có; 0: Không): "
                .$temp->isTang.";";
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã xoá hạng mục!'
                ]);
            }
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi '
                ]);
        }       
    }

    public function refreshHM(Request $request){
        $id_bg = BaoGiaBHPK::find($request->eid)->id;
        $ct = ChiTietBHPK::where('id_baogia',$id_bg)->get();
        foreach($ct as $row) {
            $bhpk = BHPK::find($row->id_baohiem_phukien);
            $namektv = ($row->userWork) ? explode(" ", $row->userWork->userDetail->surname) : "";  
            $namektv2 = ($row->userWorkTwo) ? explode(" ", $row->userWorkTwo->userDetail->surname) : "";            
            echo "<tr>
                <td>".($bhpk->isPK ? "Phụ kiện" : "Bảo hiểm")."</td>  
                <td>".($bhpk->type == "CONG" ? "CÔNG" : "PHỤ TÙNG")."</td>                                    
                <td>".$bhpk->ma."</td>
                <td>".$bhpk->noiDung."</td>
                <td>".$bhpk->dvt."</td>
                <td>".$row->soLuong."</td>
                <td>".number_format($row->donGia)."</td>
                <td>".number_format($row->chietKhau)."</td>
                <td>".number_format($row->thanhTien)."</td>
                <td>".(($row->isTang == true) ? "Có" : "Không")."</td>
                <td>"
                .(($row->userWork) ? $namektv[count($namektv)-1] : "")
                .", "
                .(($row->userWorkTwo) ? $namektv2[count($namektv2)-1] : "")."</td>
                <td>
                    <button id='delHangMuc' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-danger btn-xs'>Xoá</button>&nbsp;
                    <button id='editHangMuc' data-toggle='modal' data-target='#editModal' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-success btn-xs'>Sửa</button>
                </td>
            </tr>";
        }        
    }

    public function getTong(Request $request){
        $bg = BaoGiaBHPK::find($request->eid);
        $ct = ChiTietBHPK::where('id_baogia', $bg->id)->get();
        $tongBaoGia = 0;
        $chietKhau = 0;
        $thanhToan = 0;
        foreach($ct as $row){
            $tongBaoGia += $row->thanhTien;
            $chietKhau += $row->chietKhau;
        }
        $thanhToan = $tongBaoGia - $chietKhau;
        return response()->json([
            'tongBaoGia' => $tongBaoGia,
            'chietKhau' => $chietKhau,
            'thanhToan' => $thanhToan
        ]);
    }
    
    public function printBaoGia($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        $ngay = Date('d');
        $thang = Date('m');
        $nam = Date('Y');
        $soBG = "BG0" . $bg->id . "-" . \HelpFunction::getDateCreatedAtRevert($bg->created_at);
        $khachHang = $bg->hoTen;
        $dienThoai = $bg->dienThoai;
        $diaChi = $bg->diaChi;
        $bienSo = $bg->bienSo;
        $thongTinXe = $bg->thongTinXe;
        $soKhung = $bg->soKhung;
        $soMay = $bg->soMay;
        $loaiBG = ($bg->isPKD) ? "BÁO GIÁ KINH DOANH" : "BÁO GIÁ KHAI THÁC";
        $nvtv = $bg->user->userDetail->surname . " - " . $bg->user->userDetail->phone;
        $thoiGianVao = $bg->thoiGianVao;
        $ngayVao = \HelpFunction::revertDate($bg->ngayVao);
        $thoiGianRa = $bg->thoiGianHoanThanh;
        $ngayRa = \HelpFunction::revertDate($bg->ngayHoanThanh);
        $taiXe = $bg->taiXe;
        $dienThoaiTaiXe = $bg->dienThoaiTaiXe;

        $tt = "";
        $noiDung = "";
        $dvt = "";
        $sl = "";
        $donGia = "";
        $chietKhau = "";
        $thanhTien = "";
        $tongCong = 0;

        $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
        $i = 1;
        foreach($ct as $row){
            $bh = BHPK::find($row->id_baohiem_phukien);
            $tt .= $i++ . "<w:br/>"; 
            $dvt .= $bh->dvt . "<w:br/>";
            $sl .= $row->soLuong . "<w:br/>";
            $donGia .= number_format($row->donGia) . "<w:br/>";
            $chietKhau .= number_format($row->chietKhau) . "<w:br/>";
            if (!$row->isTang) {
                $noiDung .= $bh->noiDung . "<w:br/>";               
            } else {
                $noiDung .= $bh->noiDung . " (tặng)<w:br/>";               
            }            
            $thanhTien .= number_format((($row->donGia*$row->soLuong) - $row->chietKhau)) . "<w:br/>";
            $tongCong += ((($row->donGia*$row->soLuong) - $row->chietKhau));
        }
        $tienBangChu = \HelpFunction::convert($tongCong);
        $yeuCau = $bg->yeuCau;
        $ttnhanvien = $bg->user->userDetail->surname . "<w:br/>" . $bg->user->userDetail->phone;
        $templateProcessor = new TemplateProcessor('template/BHPK/BAOGIA.docx');               
            $outhd = 'BÁO GIÁ SỬA CHỮA ' . $soBG;
            // Cá nhân            
            $templateProcessor->setValues([
                'ngay' => $ngay,
                'thang' => $thang,
                'nam' => $nam,
                'soBG' => $soBG,
                'khachHang' => $khachHang,
                'dienThoai' => $dienThoai,
                'diaChi' => $diaChi,
                'bienSo' => $bienSo,
                'thongTinXe' => $thongTinXe,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'loaiBG' => $loaiBG,
                'nvtv' => $nvtv,
                'thoiGianVao' => $thoiGianVao,
                'ngayVao' => $ngayVao,
                'thoiGianRa' => $thoiGianRa,
                'ngayRa' => $ngayRa,
                'taiXe' => $taiXe,
                'dienThoaiTaiXe' => $dienThoaiTaiXe,
                'tt' => $tt,
                'noiDung' => $noiDung,
                'dvt' => $dvt,
                'sl' => $sl,
                'donGia' => $donGia,
                'chietKhau' => $chietKhau,
                'thanhTien' => $thanhTien,
                'tongCong' => number_format($tongCong),
                'tienBangChu' => $tienBangChu,
                'yeuCau' => $yeuCau,
                'ttnhanvien' => $ttnhanvien,               
            ]);

        $pathToSave = 'template/BHPK/BAOGIADOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
        $nhatKy->noiDung = "In báo giá sửa chữa " . $soBG;
        $nhatKy->save();
        if ($bg->inProcess)
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        else
            return redirect()->back();
    }

    public function printYeuCauCapVatTu($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        $ngay = Date('d');
        $thang = Date('m');
        $nam = Date('Y');
        $soBG = "BG0" . $bg->id . "-" . \HelpFunction::getDateCreatedAtRevert($bg->created_at);;
        $bienSo = $bg->bienSo;
        $thongTinXe = $bg->thongTinXe;
        $soKhung = $bg->soKhung;
        $soMay = $bg->soMay;
        $nvtv = $bg->user->userDetail->surname;
        $thoiGianVao = $bg->thoiGianVao;
        $ngayVao = \HelpFunction::revertDate($bg->ngayVao);
        $thoiGianRa = $bg->thoiGianHoanThanh;
        $ngayRa = \HelpFunction::revertDate($bg->ngayHoanThanh);

        $tt = "";
        $ma = "";
        $noiDung = "";
        $dvt = "";
        $sl = "";

        $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
        $i = 1;
        foreach($ct as $row){
            $bh = BHPK::find($row->id_baohiem_phukien);
            if ($bh->type == "PHUTUNG") {
                $tt .= $i++ . "<w:br/>"; 
                $ma .= $bh->ma . "<w:br/>"; 
                $noiDung .= $bh->noiDung . "<w:br/>";
                $dvt .= $bh->dvt . "<w:br/>";
                $sl .= $row->soLuong . "<w:br/>";
            }            
        }

        $templateProcessor = new TemplateProcessor('template/BHPK/CAPVATTU.docx');                    
            $outhd = 'YÊU CẦU CẤP VẬT TƯ ' . $soBG;
            // Cá nhân            
            $templateProcessor->setValues([
                'ngay' => $ngay,
                'thang' => $thang,
                'nam' => $nam,
                'soBG' => $soBG,
                'bienSo' => $bienSo,
                'thongTinXe' => $thongTinXe,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'nhanvien' => $nvtv,
                'thoiGianVao' => $thoiGianVao,
                'ngayVao' => $ngayVao,
                'thoiGianRa' => $thoiGianRa,
                'ngayRa' => $ngayRa,
                'tt' => $tt,
                'maSo' => $ma,
                'noiDung' => $noiDung,
                'dvt' => $dvt,
                'sl' => $sl,                   
            ]);

        $pathToSave = 'template/BHPK/CAPVATTUDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
        $nhatKy->noiDung = "In yêu cầu cấp vật tư " . $soBG;
        $nhatKy->save();
        if ($bg->inProcess)
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        else
            return redirect()->back();
    }

    public function printLenhSuaChua($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        $ngay = Date('d');
        $thang = Date('m');
        $nam = Date('Y');
        $soBG = "BG0" . $bg->id . "-" . \HelpFunction::getDateCreatedAtRevert($bg->created_at);
        $bienSo = $bg->bienSo;
        $thongTinXe = $bg->thongTinXe;
        $soKhung = $bg->soKhung;
        $soMay = $bg->soMay;
        $nvtv = $bg->user->userDetail->surname;
        $thoiGianVao = $bg->thoiGianVao;
        $ngayVao = \HelpFunction::revertDate($bg->ngayVao);
        $thoiGianRa = $bg->thoiGianHoanThanh;
        $ngayRa = \HelpFunction::revertDate($bg->ngayHoanThanh);

        $tt = "";
        $ma = "";
        $ktv = "";
        $noiDung = "";
        $dvt = "";
        $sl = "";

        $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
        $i = 1;
        foreach($ct as $row){
            $bh = BHPK::find($row->id_baohiem_phukien);
            if ($bh->type == "CONG") {
                $tt .= $i++ . "<w:br/>"; 
                $ma .= $bh->ma . "<w:br/>"; 
                $noiDung .= $bh->noiDung . "<w:br/>";
                $dvt .= $bh->dvt . "<w:br/>";
                $sl .= $row->soLuong . "<w:br/>";
                $namektv = ($row->userWork) ? explode(" ", $row->userWork->userDetail->surname) : "";
                $namektv2 = ($row->userWorkTwo) ? explode(" ", $row->userWorkTwo->userDetail->surname) : "";
                $ktv .= (($row->userWork) ? $namektv[count($namektv)-1] : "").  ", ".(($row->userWorkTwo) ? $namektv2[count($namektv2)-1] : "")." <w:br/>";
            }            
        }

        $templateProcessor = new TemplateProcessor('template/BHPK/LENHSUACHUA.docx');                    
            $outhd = 'LỆNH SỬA CHỮA ' . $soBG;
            // Cá nhân            
            $templateProcessor->setValues([
                'ngay' => $ngay,
                'thang' => $thang,
                'nam' => $nam,
                'soBG' => $soBG,
                'bienSo' => $bienSo,
                'thongTinXe' => $thongTinXe,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'nhanvien' => $nvtv,
                'thoiGianVao' => $thoiGianVao,
                'ngayVao' => $ngayVao,
                'thoiGianRa' => $thoiGianRa,
                'ngayRa' => $ngayRa,
                'tt' => $tt,
                'maSo' => $ma,
                'noiDung' => $noiDung,
                'dvt' => $dvt,
                'sl' => $sl,        
                'ktv' => $ktv           
            ]);

        $pathToSave = 'template/BHPK/LENHSUACHUADOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
        $nhatKy->noiDung = "In lệnh sửa chữa " . $soBG;
        $nhatKy->save();
        if ($bg->inProcess)
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        else
            return redirect()->back();
    }

    public function printQuyetToan($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        $ngay = Date('d');
        $thang = Date('m');
        $nam = Date('Y');
        $soBG = "BG0" . $bg->id . "-" . \HelpFunction::getDateCreatedAtRevert($bg->created_at);;
        $khachHang = $bg->hoTen;
        $dienThoai = $bg->dienThoai;
        $diaChi = $bg->diaChi;
        $bienSo = $bg->bienSo;
        $thongTinXe = $bg->thongTinXe;
        $soKhung = $bg->soKhung;
        $soMay = $bg->soMay;
        $loaiBG = ($bg->isPKD) ? "BÁO GIÁ KINH DOANH" : "BÁO GIÁ KHAI THÁC";
        $nvtv = $bg->user->userDetail->surname . " - " . $bg->user->userDetail->phone;
        $thoiGianVao = $bg->thoiGianVao;
        $ngayVao = \HelpFunction::revertDate($bg->ngayVao);
        $thoiGianRa = $bg->thoiGianHoanThanh;
        $ngayRa = \HelpFunction::revertDate($bg->ngayHoanThanh);
        $taiXe = $bg->taiXe;
        $dienThoaiTaiXe = $bg->dienThoaiTaiXe;

        $tt = "";
        $noiDung = "";
        $dvt = "";
        $sl = "";
        $donGia = "";
        $chietKhau = "";
        $thanhTien = "";
        $tongCong = 0;

        $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
        $i = 1;
        foreach($ct as $row){
            $bh = BHPK::find($row->id_baohiem_phukien);
            $tt .= $i++ . "<w:br/>";             
            $dvt .= $bh->dvt . "<w:br/>";
            $sl .= $row->soLuong . "<w:br/>";
            $donGia .= number_format($row->donGia) . "<w:br/>";
            $chietKhau .= number_format($row->chietKhau) . "<w:br/>";
            if (!$row->isTang) {
                $noiDung .= $bh->noiDung . "<w:br/>";                
            } else {
                // $thanhTien .= "0<w:br/>";
                // $tongCong += 0;
                $noiDung .= $bh->noiDung . " (tặng)<w:br/>";
            }
            $thanhTien .= number_format((($row->donGia*$row->soLuong) - $row->chietKhau)) . "<w:br/>";
            $tongCong += ((($row->donGia*$row->soLuong) - $row->chietKhau));
            
        }
        $tienBangChu = \HelpFunction::convert($tongCong);
        $yeuCau = $bg->yeuCau;
        $ttnhanvien = $bg->user->userDetail->surname . "<w:br/>" . $bg->user->userDetail->phone;
        $templateProcessor = new TemplateProcessor('template/BHPK/QUYETTOAN.docx');               
            $outhd = 'QUYẾT TOÁN SỬA CHỮA ' . $soBG;
            // Cá nhân            
            $templateProcessor->setValues([
                'ngay' => $ngay,
                'thang' => $thang,
                'nam' => $nam,
                'soBG' => $soBG,
                'khachHang' => $khachHang,
                'dienThoai' => $dienThoai,
                'diaChi' => $diaChi,
                'bienSo' => $bienSo,
                'thongTinXe' => $thongTinXe,
                'soKhung' => $soKhung,
                'soMay' => $soMay,
                'loaiBG' => $loaiBG,
                'nvtv' => $nvtv,
                'thoiGianVao' => $thoiGianVao,
                'ngayVao' => $ngayVao,
                'thoiGianRa' => $thoiGianRa,
                'ngayRa' => $ngayRa,
                'taiXe' => $taiXe,
                'dienThoaiTaiXe' => $dienThoaiTaiXe,
                'tt' => $tt,
                'noiDung' => $noiDung,
                'dvt' => $dvt,
                'sl' => $sl,
                'donGia' => $donGia,
                'chietKhau' => $chietKhau,
                'thanhTien' => $thanhTien,
                'tongCong' => number_format($tongCong),
                'tienBangChu' => $tienBangChu,
                'yeuCau' => $yeuCau,
                'ttnhanvien' => $ttnhanvien,               
            ]);

        $pathToSave = 'template/BHPK/QUYETTOANDOWN.docx';
        $templateProcessor->saveAs($pathToSave);
        $headers = array(
            'Content-Type: application/docx',
        );
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
        $nhatKy->noiDung = "In quyết toán sửa chữa " . $soBG;
        $nhatKy->save();
        if ($bg->inProcess)
            return response()->download($pathToSave,$outhd . '.docx',$headers);
        else
            return redirect()->back();
    }

    public function baoCaoDoanhThuPanel() {
        $user = User::all();
        $iduser = Auth::user()->id;
        $nameuser = Auth::user()->userDetail->surname;
        return view('dichvu.baocaodoanhthu',['user' => $user, 'iduser' => $iduser, 'nameuser' => $nameuser]);
    }

    public function loadBaoCaoDoanhThu(Request $request) {
        $loai = $request->baoCao;
        $nv = $request->nhanVien;
        $tu = $request->tu;
        $den = $request->den;
        $c_kd = 0;
        $pt_kd = 0;
        $ck_kd = 0;
        $tong_ck_kd = 0;
        $tong_ck_ptkd = 0;
        $tong_kd = 0;
        $c_kt = 0;
        $pt_kt = 0;
        $ck_kt = 0;
        $tong_ck_kt = 0;
        $tong_ck_ptkt = 0;
        $tong_kt = 0;
        $tb = "";
        $i = 1;
        //--------------
        $toTong = 0;
        $toTongKD = 0;
        $toTongCKKD = 0;
        $toTongKT = 0;
        $toTongCKKT = 0;
        //--------------
        switch($loai) {
            case 1: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Người tạo</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>
                    <th>Công</th>
                    <th>Phụ tùng</th>
                    <th>Chiết khấu</th>
                    <th>Tổng</th>
                </tr>
                <tbody>";   
                if ($nv == 0) {
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isDone','=',true],
                        ['isCancel','=',false],
                        ['isBaoHiem','=', true]
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $temp_cong = 0;
                            $temp_phutung = 0;
                            $temp_chietkhau = 0;
                            foreach($ct as $item) {
                                $bhpk = BHPK::where([
                                    ['id','=',$item->id_baohiem_phukien],
                                    ['isPK','=',false]
                                ])->exists();
                                if ($bhpk) {
                                    $temp_cong += $item->thanhTien;
                                    $temp_chietkhau += $item->chietKhau;
                                    if ($row->isPKD) {
                                        $c_kd += $item->thanhTien;
                                        $tong_kd += $item->thanhTien;
                                        $tong_ck_kd += $item->chietKhau;
                                    } else {
                                        $c_kt += $item->thanhTien;
                                        $tong_kt += $item->thanhTien;
                                        $tong_ck_kt += $item->chietKhau;
                                    }         
                                }                                      
                            }
                            echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                        <td>".$row->user->userDetail->surname."</td>
                                        <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                        <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                        <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_chietkhau).")</span></td>
                                        <td class='text-bold text-success'></td>
                                        <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                        <td class='text-bold text-primary'>".number_format($temp_cong)."</td>
                                    </tr>";
                        }
                    }
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kd)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($c_kt)."</span>
                                </p>
                            </div>
                    </div>";
                } else {    
                    $bg = BaoGiaBHPK::select("*")
                        ->where([
                            ['isDone','=',true],
                            ['isCancel','=',false],
                            ['isBaoHiem','=', true],
                            ['id_user_create','=',$nv]
                        ])
                        ->orderBy('isPKD','desc')->get();
                        foreach($bg as $row) {
                            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $temp_cong = 0;
                                $temp_phutung = 0;
                                $temp_chietkhau = 0;
                                foreach($ct as $item) {
                                    $bhpk = BHPK::where([
                                        ['id','=',$item->id_baohiem_phukien],
                                        ['isPK','=',false]
                                    ])->exists();
                                    if ($bhpk) {
                                        $temp_cong += $item->thanhTien;
                                        $temp_chietkhau += $item->chietKhau;
                                        if ($row->isPKD) {
                                            $c_kd += $item->thanhTien;
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_kd += $item->chietKhau;
                                        } else {
                                            $c_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_kt += $item->chietKhau;
                                        }         
                                    }                
                                }
                                echo "<tr>
                                            <td>".($i++)."</td>
                                            <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                            <td>".$row->user->userDetail->surname."</td>
                                            <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                            <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                            <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_chietkhau).")</span></td>
                                            <td class='text-bold text-success'></td>
                                            <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                            <td class='text-bold text-primary'>".number_format($temp_cong)."</td>
                                        </tr>";
                            }
                        }
                        echo "</tbody>
                            </table>        
                            <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                        <div class='row'>
                                <div class='col-md-3'>
                                    <h6>Báo giá kinh doanh:</h6>
                                    <p>
                                        - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                        - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                        - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd)."</span><br/>
                                        => Tổng: <span class='text-bold text-primary'>".number_format($c_kd)."</span>
                                    </p>
                                </div>
                                <div class='col-md-3'>
                                    <h6>Báo giá khai thác:</h6>
                                    <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kt)."</span>
                                    </p>
                                </div>
                        </div>";
                }
            } break;     
            case 2: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Người tạo</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>
                    <th>Công</th>
                    <th>Phụ tùng</th>
                    <th>Chiết khấu</th>                    
                    <th>Tổng</th>
                </tr>
                <tbody>";   
                if ($nv == 0) {
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isDone','=',true],
                        ['isCancel','=',false],
                        ['isBaoHiem','=', false]
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $temp_cong = 0;
                            $temp_ck_cong = 0;
                            $temp_phutung = 0;
                            $temp_ck_phutung = 0;
                            $temp_chietkhau = 0;
                            foreach($ct as $item) {
                                $bhpk = BHPK::where([
                                    ['id','=',$item->id_baohiem_phukien],
                                    ['isPK','=',true]
                                ])->exists();
                                if ($bhpk) {
                                    $subbhpk = BHPK::where([
                                        ['id','=',$item->id_baohiem_phukien],
                                        ['isPK','=',true]
                                    ])->first();
                                    if ($subbhpk->type == "CONG") {
                                        $temp_cong += $item->thanhTien;   
                                        $temp_ck_cong += $item->chietKhau;   
                                        if ($row->isPKD) {
                                            $c_kd += $item->thanhTien;                                        
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_kd += $item->chietKhau;
                                            $ck_kd = 0;
                                        } else {
                                            $c_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_kt += $item->chietKhau;
                                            $ck_kt = 0;
                                        }                                     
                                    }
                                    if ($subbhpk->type == "PHUTUNG") {
                                        $temp_phutung += $item->thanhTien;
                                        $temp_ck_phutung += $item->chietKhau; 
                                        if ($row->isPKD) {
                                            $pt_kd += $item->thanhTien;                                        
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_ptkd += $item->chietKhau;
                                            $ck_kd = 0;
                                        } else {
                                            $pt_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_ptkt += $item->chietKhau;
                                            $ck_kt = 0;
                                        }                  
                                    }
                                    $temp_chietkhau += $item->chietKhau;                                    
                                }                                      
                            }
                            echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                        <td>".$row->user->userDetail->surname."</td>
                                        <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                        <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                        <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_ck_cong ).")</span></td>
                                        <td class='text-bold text-success'>".number_format($temp_phutung)."<span class='text-secondary'> (".number_format($temp_ck_phutung).")</span></td>
                                        <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                        <td class='text-bold text-primary'>".number_format($temp_cong + $temp_phutung)."</td>
                                    </tr>";
                        }
                    }
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>".number_format($pt_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_ptkd).")</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd + $tong_ck_ptkd)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kd + $pt_kd)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>".number_format($pt_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_ptkt).")</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt + $tong_ck_ptkt)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kt + $pt_kt)."</span>
                                </p>
                            </div>
                    </div>";
                } else {    
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isDone','=',true],
                        ['isCancel','=',false],
                        ['isBaoHiem','=', false],
                        ['id_user_create','=', $nv]
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $temp_cong = 0;
                            $temp_ck_cong = 0;
                            $temp_phutung = 0;
                            $temp_ck_phutung = 0;
                            $temp_chietkhau = 0;                            
                            foreach($ct as $item) {
                                $bhpk = BHPK::where([
                                    ['id','=',$item->id_baohiem_phukien],
                                    ['isPK','=',true]
                                ])->exists();
                                if ($bhpk) {
                                    $subbhpk = BHPK::where([
                                        ['id','=',$item->id_baohiem_phukien],
                                        ['isPK','=',true]
                                    ])->first();
                                    if ($subbhpk->type == "CONG") {
                                        $temp_cong += $item->thanhTien;   
                                        $temp_ck_cong += $item->chietKhau;   
                                        if ($row->isPKD) {
                                            $c_kd += $item->thanhTien;                                        
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_kd += $item->chietKhau;
                                            $ck_kd = 0;
                                        } else {
                                            $c_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_kt += $item->chietKhau;
                                            $ck_kt = 0;
                                        }                                     
                                    }
                                    if ($subbhpk->type == "PHUTUNG") {
                                        $temp_phutung += $item->thanhTien;
                                        $temp_ck_phutung += $item->chietKhau; 
                                        if ($row->isPKD) {
                                            $pt_kd += $item->thanhTien;                                        
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_ptkd += $item->chietKhau;
                                            $ck_kd = 0;
                                        } else {
                                            $pt_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_ptkt += $item->chietKhau;
                                            $ck_kt = 0;
                                        }                  
                                    }
                                    $temp_chietkhau += $item->chietKhau;                                    
                                }                                      
                            }
                            echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                        <td>".$row->user->userDetail->surname."</td>
                                        <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                        <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                        <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_ck_cong ).")</span></td>
                                        <td class='text-bold text-success'>".number_format($temp_phutung)."<span class='text-secondary'> (".number_format($temp_ck_phutung).")</span></td>
                                        <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                        <td class='text-bold text-primary'>".number_format($temp_cong + $temp_phutung)."</td>
                                    </tr>";
                        }
                    }
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>".number_format($pt_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_ptkd).")</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd + $tong_ck_ptkd)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kd + $pt_kd)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>".number_format($pt_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_ptkt).")</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt + $tong_ck_ptkt)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kt + $pt_kt)."</span>
                                </p>
                            </div>
                    </div>";
                }
            } break;  
            case 3: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>KTV 1</th>
                    <th>KTV 2</th>
                    <th>Tỉ lệ</th>                    
                    <th>Người tạo BG</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>
                    <th>Công</th>
                    <th>Chiết khấu</th>
                </tr>
                <tbody>";   
                if ($nv == 0) {
                    $user = User::all();
                    $i = 1;
                    foreach($user as $row) {
                        if ($row->hasRole("to_phu_kien")) {
                            $ct = ChiTietBHPK::where('id_user_work','=',$row->id)
                            ->orWhere('id_user_work_two','=',$row->id)
                            ->get();
                            foreach($ct as $item) {
                                $tiLe = 1;
                                $cong = 0;
                                $chietKhau = 0;
                                $ktv1 = "";
                                $ktv2 = "";
                                $nguoiTao = "";
                                $loaiBaoGia = false;
                                $soBaoGia = "";
                                if ($item->baoGia->isDone && !$item->baoGia->isCancel 
                                    && ((strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) >= strtotime($tu)) 
                                    &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) <= strtotime($den)))) {
                                    if ($item->id_user_work != $row->id) {
                                        $tiLe = 10 - $item->tiLe;
                                        $ktv2 = $item->userWorkTwo->userDetail->surname;
                                    } elseif ($item->id_user_work_two != $row->id) {
                                        $tiLe = $item->tiLe;
                                        $ktv1 = $item->userWork->userDetail->surname;
                                    } else {
                                        
                                    }
                                    $cong = $item->thanhTien * $tiLe/10;
                                    $chietKhau = $item->chietKhau * $tiLe/10;
                                    $nguoiTao = $item->baoGia->user->userDetail->surname;
                                    $loaiBaoGia = $item->baoGia->isPKD;
                                    if ($loaiBaoGia) {
                                        $toTongKD += ($item->thanhTien * $tiLe/10);
                                        $toTongCKKD +=  $item->chietKhau * $tiLe/10;
                                    } else {
                                        $toTongKT += ($item->thanhTien * $tiLe/10);
                                        $toTongCKKT +=  $item->chietKhau * $tiLe/10;
                                    }
                                    $toTong += ($item->thanhTien * $tiLe/10);
                                    $soBaoGia = "BG0".$item->baoGia->id."-".\HelpFunction::getDateCreatedAtRevert($item->baoGia->created_at);
                                    echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)."</td>
                                        <td>".$ktv1."</td>
                                        <td>".$ktv2."</td>
                                        <td class='text-bold text-pink'>".$tiLe."/".(10-$tiLe)."</td>                                        
                                        <td>".$nguoiTao."</td>
                                        <td>".($loaiBaoGia == true ? "Báo giá KD" : "Báo giá khai thác")."</td>
                                        <td>".$soBaoGia."</td>
                                        <td>".number_format($cong)."</td>
                                        <td>".number_format($chietKhau)."</td>
                                        </tr>";
                                }
                            }
                        }
                    }                    
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($toTong)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($toTongKD)."</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKD)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($toTongKD)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($toTongKT)."</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKT)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($toTongKT)."</span>
                                </p>
                            </div>
                    </div>";
                } else {    
                    $i = 1;
                    $ct = ChiTietBHPK::where('id_user_work','=',$nv)
                    ->orWhere('id_user_work_two','=',$nv)
                    ->get();
                    foreach($ct as $item) {
                        $tiLe = 1;
                        $cong = 0;
                        $chietKhau = 0;
                        $ktv1 = "";
                        $ktv2 = "";
                        $nguoiTao = "";
                        $loaiBaoGia = false;
                        $soBaoGia = "";
                        if ($item->baoGia->isDone && !$item->baoGia->isCancel 
                            && ((strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) <= strtotime($den)))) {
                            if ($item->id_user_work != $nv) {
                                $tiLe = 10 - $item->tiLe;
                                if ($item->id_user_work_two == $nv)
                                    $ktv2 = $item->userWorkTwo->userDetail->surname;
                            } elseif ($item->id_user_work_two != $nv)  {
                                $tiLe = $item->tiLe;
                                if ($item->id_user_work == $nv)
                                    $ktv1 = $item->userWork->userDetail->surname;
                            } else {

                            }

                            $cong = $item->thanhTien * $tiLe/10;
                            $chietKhau = $item->chietKhau * $tiLe/10;
                            $nguoiTao = $item->baoGia->user->userDetail->surname;
                            $loaiBaoGia = $item->baoGia->isPKD;
                            if ($loaiBaoGia) {
                                $toTongKD += ($item->thanhTien * $tiLe/10);
                                $toTongCKKD +=  $item->chietKhau * $tiLe/10;
                            } else {
                                $toTongKT += ($item->thanhTien * $tiLe/10);
                                $toTongCKKT +=  $item->chietKhau * $tiLe/10;
                            }
                            $toTong += ($item->thanhTien * $tiLe/10);
                            $soBaoGia = "BG0".$item->baoGia->id."-".\HelpFunction::getDateCreatedAtRevert($item->baoGia->created_at);
                            echo "<tr>
                                <td>".($i++)."</td>
                                <td>".\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)."</td>
                                <td>".$ktv1."</td>
                                <td>".$ktv2."</td>
                                <td class='text-bold text-pink'>".$tiLe."/".(10-$tiLe)."</td>                                        
                                <td>".$nguoiTao."</td>
                                <td>".($loaiBaoGia == true ? "Báo giá KD" : "Báo giá khai thác")."</td>
                                <td>".$soBaoGia."</td>
                                <td>".number_format($cong)."</td>
                                <td>".number_format($chietKhau)."</td>
                                </tr>";
                        }
                    }              
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($toTong)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($toTongKD)."</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKD)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($toTongKD)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($toTongKT)."</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKT)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($toTongKT)."</span>
                                </p>
                            </div>
                    </div>";
                }
            } break;     
        }
    }

    public function baoCaoTienDoPanel() {
        $user = User::all();
        $iduser = Auth::user()->id;
        $nameuser = Auth::user()->userDetail->surname;
        return view('dichvu.baocaotiendo',['user' => $user, 'iduser' => $iduser, 'nameuser' => $nameuser]);
    }

    public function loadTienDo(Request $request) {
        $nv = $request->nhanVien;
        $tu = $request->tu;
        $den = $request->den;
        echo "<div style='overflow:auto;'><table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Mã lệnh</th>
                    <th>Biển số</th>
                    <th>Số khung</th>
                    <th>Công việc</th>                         
                    <th>Bắt đầu</th>
                    <th>Hoàn tất</th>
                    <th>Trạng thái</th>               
                </tr>
                <tbody>";
                
        $ct = ChiTietBHPK::where('id_user_work','=',$nv)
        ->orWhere('id_user_work_two','=',$nv)
        ->get();
        $i = 1;
        foreach($ct as $row) {
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->baoGia->created_at)) >= strtotime($tu)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->baoGia->created_at)) <= strtotime($den))) {
                $bhpk = BHPK::find($row->id_baohiem_phukien);                
                $stt = "";
                if ($row->baoGia->isCancel)
                    $stt = "<span class='text-bold text-danger'>Đã huỷ</span>";
                elseif (!$row->baoGia->isCancel && $row->baoGia->isDone)
                    $stt = "<span class='text-bold text-success'>Hoàn tất</span>";
                elseif (!$row->baoGia->isCancel && !$row->baoGia->isDone && $row->baoGia->inProcess)
                    $stt = "<span class='text-bold text-info'>Đang thực hiện</span>";
                elseif (!$row->baoGia->isCancel && !$row->baoGia->isDone && !$row->baoGia->inProcess)
                    $stt = "<span class='text-bold text-secondary'>Mới tạo</span>";

                echo "<tr>
                <td>".($i++)."</td>
                <td>".\HelpFunction::getDateRevertCreatedAt($row->baoGia->created_at)."</td>
                <td class='text-bold text-secondary'>BG0".$row->baoGia->id."-".\HelpFunction::getDateCreatedAtRevert($row->baoGia->created_at)."</td>
                <td class='text-bold text-primary'>".$row->baoGia->bienSo."</td>
                <td class='text-bold text-primary'>".$row->baoGia->soKhung."</td>
                <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                <td>".$row->baoGia->thoiGianVao." ".\HelpFunction::revertDate($row->baoGia->ngayVao)."</td>
                <td>".$row->baoGia->thoiGianHoanThanh." ".\HelpFunction::revertDate($row->baoGia->ngayHoanThanh)."</td>
                <td>".$stt."</td>
                </tr>";            
            }
        }
        echo "</tbody>
                </table></div>";
    }

    public function getEditHangMuc(Request $request) {
        $ct = ChiTietBHPK::where([
            ['id_baogia','=',$request->eid],
            ['id_baohiem_phukien','=',$request->ehm]
        ])->first();
        if ($ct) {
            $bhpk = BHPK::find($ct->id_baohiem_phukien);
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã load hạng mục chỉnh sửa",
                "data" => $ct,
                "congViec" => $bhpk->noiDung
            ]);
        }
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải hạng mục"
            ]);
    }

    public function editHangMuc(Request $request) {
        $bg = BaoGiaBHPK::find($request->editID);
        if ($bg->isDone || $bg->isCancel) {
            return response()->json([
                "code" => 500,
                "type" => "error",
                "message" => "Báo giá đã hoàn tất hoặc đã huỷ không thể cập nhật hạng mục"
            ]);
        } else {
            $ct = ChiTietBHPK::where([
                ['id_baogia','=',$request->editID],
                ['id_baohiem_phukien','=',$request->editIDHM]
            ])->update([
                "id_user_work" => ($request->ekyThuatVien) ? $request->ekyThuatVien : null,
                "id_user_work_two" => ($request->ekyThuatVienTwo) ? $request->ekyThuatVienTwo : null,
                "tiLe" => ($request->etiLe) ? $request->etiLe : 10
            ]);
            if ($ct) {
                $userOne = User::find($request->ekyThuatVien);
                $userTwo = User::find($request->ekyThuatVienTwo);
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý phụ kiện";
                $nhatKy->noiDung = "Cập nhật kỹ thuật viên cho BG0" 
                . $bg->id . "-" .\HelpFunction::getDateCreatedAtRevert($bg->created_at)
                .";<br/>KTV1: ".(($userOne) ? $userOne->userDetail->surname : "Không")."<br/>Tỉ lệ: ".(($request->etiLe) ? $request->etiLe : 10)."<br/>KTV2: " 
                . (($userTwo) ? $userTwo->userDetail->surname : "Không");
                $nhatKy->save();
                return response()->json([
                    "code" => 200,
                    "type" => "info",
                    "message" => "Đã cập nhật hạng mục chỉnh sửa"
                ]);
            }
            else
                return response()->json([
                    "code" => 500,
                    "type" => "info",
                    "message" => "Lỗi cập nhật hạng mục"
                ]);
        }        
    }
}
