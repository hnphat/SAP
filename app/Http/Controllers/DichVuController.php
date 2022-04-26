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
            $nhatKy->noiDung = "Tạo báo giá: BG0".$bg->id."-".Date('Y')."".Date('m').";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tạo báo giá',
                'data' => $bg,
                'soBG' => "BG0".$bg->id."-".Date('Y')."".Date('m'),
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
            $nhatKy->noiDung = "Chỉnh sửa báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";";
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã chỉnh sửa báo giá',
                'data' => $bg,
                'soBG' => "BG0".$bg->id."-".Date('Y')."".Date('m'),
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
                    <td>BG0".$row->id."-".Date('Y')."".Date('m')."</td>
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
                'soBG' => "BG0".$bg->id."-".Date('Y')."".Date('m'),
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
                $nhatKy->noiDung = "Xoá báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";";
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
            $nhatKy->noiDung = "Thực hiện báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";";
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
                $nhatKy->noiDung = "Huỷ báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";";
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
                $nhatKy->noiDung = "Hoàn tất báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";";
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
        $ct->save();
        $pk = BHPK::find($request->hangMucChiTiet);
        if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Lưu hạng mục cho báo giá: BG0".$request->bgid."-".Date('Y')."".Date('m').";"
                .$pk->noiDung."; Số lượng: "
                .$request->soLuong."; Đơn giá: "
                .$request->donGia."; Chiết khấu: "
                .$request->chietKhau."; Tặng (1: Có; 0: Không): "
                .$request->tang.";";
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
                $nhatKy->noiDung = "Xoá hạng mục cho báo giá: BG0".$request->eid."-".Date('Y')."".Date('m').";"
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
                <td>".(($row->userWork) ? $row->userWork->userDetail->surname : "Không có")."</td>
                <td>
                    <button id='delHangMuc' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-danger btn-xs'>Xoá</button>
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
}
