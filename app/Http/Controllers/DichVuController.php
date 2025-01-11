<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GuestDv;
use App\BHPK;
use App\User;
use App\HopDong;
use App\KhoV2;
use App\HistoryHopDong;
use App\TypeCar;
use App\PackageV2;
use App\ChiTietBHPK;
use App\BaoGiaBHPK;
use Illuminate\Support\Facades\Auth;
use App\NhatKy;
use App\KTVBHPK;
use App\SaleOffV2;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Excel;

class DichVuController extends Controller
{
    //
    public function phuKienPanel() {
        $user = User::all();
        $bhpk = BHPK::all();
        $typecar = TypeCar::all();
        return view('dichvu.quanlyphukien',['user' => $user, 'bhpk' => $bhpk, 'typecar' => $typecar]);
    }

    public function baoHiemPanel() {
        $user = User::all();
        $bhpk = BHPK::all();
        return view('dichvu.quanlybaohiem',['user' => $user, 'bhpk' => $bhpk]);
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
            $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
        $typecar = TypeCar::all();
        return view('dichvu.hangmuc', ['typecar' => $typecar]);
    }

    public function getHangMuc() {
        $arr = [];
        if (Auth::user()->hasRole('system'))
            $bhpk = BHPK::select("*")->where('loaiXe','!=',null)->orderBy('id','desc')->get();
        else
            $bhpk = BHPK::select("*")->where([
                ['id_user_create','=',Auth::user()->id],
                ['loaiXe','!=',null],
            ])->orderBy('id','desc')->get();  
        foreach($bhpk as $row) {
            $typecar = TypeCar::find($row->loaiXe);
            $row->idcar = $typecar ? $typecar->id : null;
            $row->namecar = $typecar ? $typecar->name : null;
            array_push($arr, $row);
        }            
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'Đã tải dữ liệu',
            'data' => $arr
        ]);
    }

    public function addHangMuc(Request $request) {
        $kh = new BHPK();
        $kh->id_user_create = Auth::user()->id;
        $kh->isPK = 1;
        $kh->ma = strtoupper($request->ma);
        $kh->noiDung = $request->noiDung;
        $kh->dvt = $request->dvt;
        $kh->donGia = $request->donGia ? $request->donGia : 0;
        $kh->giaVon = $request->giaVon ? $request->giaVon : 0;
        $kh->congKTV = $request->congKTV ? $request->congKTV : 0;
        $kh->loai = $request->loai;
        $kh->loaiXe = $request->typeCar;        
        $kh->save();
        $typecar = TypeCar::find($request->typeCar);
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý hạng mục";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Thêm hạng mục: "
            .$request->noiDung."; Phần (1: Phụ kiện, 2: Bảo hiểm): "
            .$request->isPK."; Mã: "
            .$request->ma."; Đơn vị tính: "
            .$request->dvt."; Đơn giá: "
            .$request->donGia."; Loại: "
            .$request->loai."; Loại xe: "
            .$typecar->name.";";
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
            $nhatKy->ghiChu = Carbon::now();
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
        // $kh->isPK = $request->eisPK;
        $kh->ma = strtoupper($request->ema);
        $kh->noiDung = $request->enoiDung;
        $kh->dvt = $request->edvt;
        $kh->donGia = $request->edonGia ? $request->edonGia : 0;
        $kh->loai = $request->eloai; 
        $kh->giaVon = $request->egiaVon ? $request->egiaVon : 0; 
        $kh->congKTV = $request->econgKTV ? $request->econgKTV : 0;     
        $kh->loaiXe = $request->etypeCar;        
        $kh->save();
        $typecar = TypeCar::find($request->etypeCar);
        if($kh) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý hạng mục";
            $nhatKy->ghiChu = Carbon::now();
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
            .$request->eloai."; Loại xe: "
            .($typecar ? $typecar->name : "").";";
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
        $hd = HopDong::where('code',$request->findVal)->orderBy('id', 'desc')->first();
        // $hopDong = $hd->code.".".$hd->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($hd->created_at)."/HĐMB-PA";
        $hopDong = $hd->code;
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

    public function lienKetPhuKien(Request $request) {
        $flag = false;
        $bg = BaoGiaBHPK::find($request->idbg);
        if (!$bg->saler)
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Báo giá khai thác không thể liên kết phụ kiện!'
            ]);
        $hd = HopDong::where('code',$bg->hopDongKD)->orderBy('id','desc')->first();
        $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
        $check = ChiTietBHPK::where('id_baogia',$request->idbg)->exists();
        if ($check) {
            return response()->json([
                'type' => 'error',
                'code' => 200,
                'message' => 'Phụ kiện đã tồn tại, không thể liên kết. Vui lòng xoá tất cả phụ kiện đang có! '
            ]);
        } else {
            foreach($listpk as $row) {
                $p = PackageV2::find($row->id_bh_pk_package);
                if ($p->type != "cost" && $p->mapk) {
                    $ct = new ChiTietBHPK();
                    $ct->id_baogia = $request->idbg;
                    $ct->id_baohiem_phukien = $p->mapk;
                    $ct->soLuong = 1;
                    $ct->donGia = $p->cost;
                    $ct->isTang = $p->type == "pay" ? 0 : 1;
                    $ct->thanhTien = $p->cost - ($p->cost*$row->giamGia/100);
                    $ct->chietKhau = $row->giamGia;
                    $ct->save();
                    $pk = BHPK::find($p->mapk);
                    if ($ct) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                        $nhatKy->noiDung = "Liên kết phụ kiện. Lưu hạng mục cho báo giá: BG0".$request->idbg.";"
                        .$pk->noiDung."; Số lượng: 1; Đơn giá: "
                        .$p->cost."; Tặng (1: Có; 0: Không): "
                        .($p->type == "pay" ? 0 : 1)."; Chiết khấu: " . $row->giamGia;
                        $nhatKy->save();
                        $flag = true;
                    }
                } else continue;            
            } 
        }
        if ($flag) {
            $his = new HistoryHopDong();
            $his->idDeNghi = $hd->id;
            $his->id_user = Auth::user()->id;
            $his->ngay = Date("H:m:s d-m-Y");
            $his->noiDung = "Liên kết phụ kiện";
            $his->ghiChu = "";
            $his->save();

            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã liên kết phụ kiện!'
            ]);
        } else 
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Không thể liên kết phụ kiện '
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
        $bg->saler = $request->saler;
        if (Auth::user()->hasRole('nv_baohiem')) {
            $bg->isBaoHiem = true;
        } elseif (Auth::user()->hasRole('nv_phukien')) {
            $bg->isBaoHiem = false;
        } else {
            $bg->isBaoHiem = $request->isBaoHiem;
        }
        // Xử lý số hợp đồng
        $hd = HopDong::where('code',$request->hopDong)->orderBy('id', 'desc')->first();
        $logSoHd = $hd ? $hd->code.".".$hd->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($hd->created_at)."/HĐMB-PA" : null;
        $bg->soHopDongKD = $logSoHd;
        //------------------------------------
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
        $bg->tienCoc = $request->tienCoc;
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
        }
        //
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Tạo báo giá: BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->ghiChu = Carbon::now();
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
        // Xử lý số hợp đồng
        $hd = HopDong::where('code',$request->hopDong)->orderBy('id', 'desc')->first();
        $logSoHd = $hd ? $hd->code.".".$hd->carSale->typeCar->code."/".\HelpFunction::getDateCreatedAt($hd->created_at)."/HĐMB-PA" :  null;
        $bg->soHopDongKD = $logSoHd;
        $tongPKBan = 0;
        $tongPKBanPhuKien = 0;
        if ($hd) {
            // $magiamgia = $hd->magiamgia;
            $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
            foreach($listpk as $row) {
                $p = PackageV2::find($row->id_bh_pk_package);
                if ($p->type != "cost" && $p->mapk) {
                    if ($p->type == "pay")
                        $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                } 
            }

            // $tongPKBan = $tongPKBan - ($tongPKBan*$magiamgia/100);

            // Kiểm tra số tiền phụ kiện bán hiện có 
            $ct = ChiTietBHPK::where('id_baogia',$request->eid)->get();
            foreach($ct as $row) {
                if (!$row->isTang)
                    $tongPKBanPhuKien += $row->thanhTien;
            }
            // --------------------------
        }
        //------------------------------------
        if ($tongPKBan != $tongPKBanPhuKien)
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Tổng phụ kiện bán: '.number_format($tongPKBanPhuKien).' không khớp với hợp đồng: '.number_format($tongPKBan).'!'
            ]);
            
        $bg->hopDongKD = $request->hopDong;
        $bg->nvKD = $request->nhanVien;
        $bg->saler = $request->saler;
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
        $bg->tienCoc = $request->tienCoc;
        $bg->save();
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Cập nhật báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã cập nhật báo giá',
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
            $doanhthubaogia = 0;
            $stt = "";
            $flag = true;
            $ct = ChiTietBHPK::select("*")
            ->where('id_baogia', $row->id)
            ->get();
            if (!$row->inProcess) {
                $stt = "class='bg-secondary'";
                foreach($ct as $c){
                    if (!$c->isTang)
                        $doanhthubaogia += $c->thanhTien;       
                }
            }
            if ($row->inProcess && !$row->isDone && !$row->isCancel) {
                $stt = "class='bg-success'";
                foreach($ct as $c){
                    if (!$c->isTang)
                        $doanhthubaogia += $c->thanhTien;       
                }
            }
            if ($row->inProcess && $row->isDone && !$row->isCancel) {
                // xử lý chưa thêm KTV start
                foreach($ct as $c){
                    if (!$c->isTang)
                        $doanhthubaogia += $c->thanhTien;
                    $bhpk = BHPK::find($c->id_baohiem_phukien);
                    if ($bhpk->loai != "KTV lắp đặt") continue;
                    $check = KTVBHPK::select("*")
                    ->where([
                        ['id_baogia','=',$row->id],
                        ['id_bhpk','=',$c->id_baohiem_phukien]
                    ])->exists();
                    if (!$check) {
                        $flag = false;
                        // break;
                    }        
                }
                // xử lý chưa thêm KTV end
                if ($flag)
                    $stt = "class='bg-info'";
                else 
                    $stt = "class='bg-orange'";
            }
            if ($row->isCancel) {
                $stt = "class='bg-danger'";
                foreach($ct as $c){
                    if (!$c->isTang)
                        $doanhthubaogia += $c->thanhTien;       
                }
            }
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                echo "
                <tr id='tes' data-id='".$row->id."' ".$stt.">
                    <td>".($row->saler ? "Kinh doanh" : "Khai thác")."</td>
                    <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                    <td>".$row->bienSo."</td>
                    <td>".$row->hoTen."</td>
                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                    <td>".number_format($doanhthubaogia)."</td>
                </tr>";
            }
        }      
    }

    public function counterBadge(Request $request) {
        $_from = $request->tu;
        $_to = $request->den;
        // $_from = \HelpFunction::revertDate($request->tu);
        // $_to = \HelpFunction::revertDate($request->den);   
        $badge1 = 0;
        $badge2 = 0;
        $badge3 = 0;
        $badge4 = 0;
        $badge5 = 0;
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
            $flag = true;
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                if (!$row->inProcess)
                    $badge1++;
                if ($row->inProcess && !$row->isDone && !$row->isCancel)
                    $badge2++;
                if ($row->inProcess && $row->isDone && !$row->isCancel) {
                    // xử lý chưa thêm KTV start
                    $ct = ChiTietBHPK::select("*")
                    ->where('id_baogia', $row->id)
                    ->get();
                    foreach($ct as $c){
                        $bhpk = BHPK::find($c->id_baohiem_phukien);
                        if ($bhpk->loai != "KTV lắp đặt") continue;
                        $check = KTVBHPK::select("*")
                        ->where([
                            ['id_baogia','=',$row->id],
                            ['id_bhpk','=',$c->id_baohiem_phukien]
                        ])->exists();
                        if (!$check) {
                            $flag = false;
                            break;
                        }        
                    }
                    // xử lý chưa thêm KTV end
                    if ($flag)
                        $badge3++;
                    else 
                        $badge4++;
                }
                if ($row->isCancel)
                    $badge5++;
            }
        } 
        return response()->json([
            'type' => 'info',
            'code' => 200,
            'badge1' => $badge1,
            'badge2' => $badge2,
            'badge3' => $badge3,
            'badge4' => $badge4,
            'badge5' => $badge5,
        ]);    
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
                $nhatKy->ghiChu = Carbon::now();
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
        $soHD = $bg->soHopDongKD;
        $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
        $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
        $tongPKBan = 0;
        $tongPKBanPhuKien = 0;
        $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
        if ($soHD && $hd) {
            // $magiamgia = $hd->magiamgia;
            $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
            foreach($listpk as $row) {
                $p = PackageV2::find($row->id_bh_pk_package);
                if ($p->type != "cost" && $p->mapk) {
                    if ($p->type == "pay")
                        $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                } 
            }

            // $tongPKBan = $tongPKBan - ($tongPKBan*$magiamgia/100);

            // Kiểm tra số tiền phụ kiện bán hiện có 
            $ct = ChiTietBHPK::where('id_baogia',$request->eid)->get();
            foreach($ct as $row) {
                if (!$row->isTang)
                    $tongPKBanPhuKien += $row->thanhTien;
            }
            // --------------------------
        }

        if ($tongPKBan != $tongPKBanPhuKien)
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Tổng phụ kiện bán: '.number_format($tongPKBanPhuKien).' không khớp với hợp đồng: '.number_format($tongPKBan).' không thể thực hiện báo giá này!'
            ]);

        $bg->inProcess = true;
        $bg->save();
        if ($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
            $nhatKy->noiDung = "Thực hiện báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
            $nhatKy->ghiChu = Carbon::now();
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
        if ($bg->trangThaiThu) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Báo giá đã thu tiền không thể huỷ!',
                'data' => $bg
            ]);
        }

        if ($bg->inProcess) {
            $bg->isCancel = true;
            $bg->lyDoHuy = $request->lyDo;
            $bg->save();
            if ($bg) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->noiDung = "Huỷ báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."; Lý do hủy: "
                . $request->lyDo . "";
                $nhatKy->ghiChu = Carbon::now();
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
        $tongCong = 0;
        $tongTang = 0;
        $tongChietKhau = 0;
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->saler != 0) {
            // --- Kiểm tra giá tiền giữa hợp đồng với phụ kiện đang khai báo
            $soHD = $bg->soHopDongKD;
            $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
            $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
            $tongPKBan = 0;
            $tongPKBanPhuKien = 0;
            $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
            if ($soHD && $hd) {
                $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
                foreach($listpk as $row) {
                    $p = PackageV2::find($row->id_bh_pk_package);
                    if ($p->type != "cost" && $p->mapk) {
                        if ($p->type == "pay")
                            $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                    } 
                }

                $ct = ChiTietBHPK::where('id_baogia',$request->eid)->get();
                foreach($ct as $row) {
                    if (!$row->isTang)
                        $tongPKBanPhuKien += $row->thanhTien;
                }
                // --------------------------
            }

            if ($tongPKBan != $tongPKBanPhuKien)
                return response()->json([
                    'type' => 'error',
                    'code' => 500,
                    'message' => "Tổng phụ kiện bán: ".number_format($tongPKBanPhuKien)." không khớp với hợp đồng: ".number_format($tongPKBan)." không thể hoàn tất báo giá này!"
                ]);
            // ----------------------------------------------------------------
            if ($bg->inProcess) {
                $ct = ChiTietBHPK::where('id_baogia', $request->eid)->get();
                foreach($ct as $row){
                    $bh = BHPK::find($row->id_baohiem_phukien);
                    $tongCong += ($row->donGia*$row->soLuong);
                    if ($row->isTang)
                        $tongTang += ($row->donGia*$row->soLuong);
                    else
                        $tongChietKhau += ($row->donGia * $row->soLuong * ($row->chietKhau ? $row->chietKhau : 0)/100);
                } 
                $bg->doanhThu = $tongCong;
                $bg->tang = $tongTang;
                $bg->chietKhau = $tongChietKhau;
                $bg->isDone = true;
                $bg->save();
                if ($bg) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                    $nhatKy->noiDung = "Hoàn tất báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
                    $nhatKy->ghiChu = Carbon::now();
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
        } else {
            if ($bg->inProcess) {
                $ct = ChiTietBHPK::where('id_baogia', $request->eid)->get();
                foreach($ct as $row){
                    $bh = BHPK::find($row->id_baohiem_phukien);
                    $tongCong += ($row->donGia*$row->soLuong);
                    if ($row->isTang)
                        $tongTang += ($row->donGia*$row->soLuong);
                    else
                        $tongChietKhau += ($row->donGia * $row->soLuong * ($row->chietKhau ? $row->chietKhau : 0)/100);
                } 
                $bg->doanhThu = $tongCong;
                $bg->tang = $tongTang;
                $bg->chietKhau = $tongChietKhau;
                $bg->isDone = true;
                $bg->save();
                if ($bg) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                    $nhatKy->noiDung = "Hoàn tất báo giá: BG0".$request->eid."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at).";";
                    $nhatKy->ghiChu = Carbon::now();
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

    public function taiHangHoa(Request $request) {
        $item = BHPK::where([
            ['ma','=',$request->ma]
        ])->first();
        if ($item)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã tìm được hàng hóa',
                    'data' => $item
                ]);
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => ' Không tìm thấy'
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
        // Xử lý nếu là phụ kiện đang liên kết với hợp đồng thì chỉ liên kết phụ kiện
        $bg = BaoGiaBHPK::find($request->bgid);
        if ($bg->soHopDongKD != null) {
            return response()->json([
                'type' => 'warning',
                'code' => 500,
                'message' => 'Báo giá này chỉ có thể liên kết phụ kiện không thể thêm mới!'
            ]);
        }
        // ----------------------------------------------
        $bhpk = BHPK::where('ma',$request->hangHoa)->first();
        $ct = new ChiTietBHPK();
        $ct->id_baogia = $request->bgid;
        $ct->id_baohiem_phukien = $bhpk->id;
        $ct->soLuong = $request->soLuong;
        $ct->donGia = $bhpk->donGia;
        $ct->chietKhau = $request->addChietKhau;
        $ct->isTang = $request->tang;
        $tt = $request->soLuong * $bhpk->donGia;
        $ct->thanhTien = ($tt) - ($tt * ($request->addChietKhau/100));
        $ct->save();
        $pk = BHPK::find($bhpk->id);
        if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->noiDung = "Lưu hạng mục cho báo giá: BG0".$request->bgid.";"
                .$pk->noiDung."; Số lượng: "
                .$request->soLuong."; Đơn giá: "
                .$request->donGia."; Tặng (1: Có; 0: Không): "
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

    public function updateBHPK(Request $request) {
        $pk = BHPK::find($request->ehangHoa)->first();
        // $temp = ChiTietBHPK::where([
        //     ["id_baogia","=",$request->ebgid],
        //     ["id_baohiem_phukien","=",$request->ehangHoa],
        // ])->first();
        $temp = ChiTietBHPK::find($request->mainIdEdit);
        // $ct = ChiTietBHPK::where([
        //     ["id_baogia","=",$request->ebgid],
        //     ["id_baohiem_phukien","=",$request->ehangHoa],
        // ])->update([
        //     "soLuong" => $request->esoLuong,
        //     "chietKhau" => $request->eaddChietKhau,
        //     "isTang" => $request->etang,
        //     "thanhTien" => $temp->donGia * $request->esoLuong
        // ]);
        $ct = ChiTietBHPK::where([
            ["id","=",$request->mainIdEdit]
        ])->update([
            "soLuong" => $request->esoLuong,
            "chietKhau" => $request->eaddChietKhau,
            "isTang" => $request->etang,
            "thanhTien" => ($temp->donGia * $request->esoLuong) - ($temp->donGia * $request->esoLuong * $request->eaddChietKhau/100)
        ]);
        if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->noiDung = "Lưu hạng mục cho báo giá: BG0".$request->ebgid.";"
                .$pk->noiDung."; Số lượng: "
                .$request->esoLuong."; Tặng (1: Có; 0: Không): "
                .$request->etang.";";
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
            // $temp = ChiTietBHPK::where([
            //     ['id_baogia','=', $request->eid],
            //     ['id_baohiem_phukien','=', $request->ehm],
            // ])->first();
            $temp = ChiTietBHPK::find($request->mainId);
            $bhpk = BHPK::find($request->ehm);
            // $ct = ChiTietBHPK::where([
            //     ['id_baogia','=', $request->eid],
            //     ['id_baohiem_phukien','=', $request->ehm],
            // ])->delete();      
            $ct = ChiTietBHPK::find($request->mainId);
            $ct->delete();
            $ktv = KTVBHPK::where([
                ['id_baogia','=',$request->eid],
                ['id_bhpk','=',$request->ehm]
            ])->delete();
            if ($ct) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý bảo hiểm, phụ kiện";
                $nhatKy->ghiChu = Carbon::now();
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
            
            //Xử lý ktv
            $n_ktv = "";
            $ktv = KTVBHPK::where([
                ['id_baogia','=',$row->id_baogia],
                ['id_bhpk','=',$row->id_baohiem_phukien],
            ])->get(); 
            foreach($ktv as $k) {
                $u = User::find($k->id_work);
                $namektv = explode(" ", $u->userDetail->surname);
                $n_ktv .= $namektv[count($namektv) - 1] . "; ";
            }
            //-----           

            echo "<tr>                                                   
                <td>".$bhpk->ma."</td>
                <td>".$bhpk->noiDung."</td>
                <td>".$bhpk->dvt."</td>
                <td>".$row->soLuong."</td>
                <td>".number_format($row->donGia)."</td>
                <td>".number_format($row->chietKhau)."%</td>
                <td>".number_format($row->thanhTien)."</td>
                <td>".(($row->isTang == true) ? "Có" : "Không")."</td>    
                <td>".$n_ktv."</td>                
                <td>
                    <button id='delHangMuc' data-mainid='".$row->id."' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-danger btn-xs'>Xoá</button>&nbsp;
                    <button id='editHangMuc' data-mainid='".$row->id."' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-info btn-xs'>KTV</button>&nbsp;
                    <button id='eeditHangMuc' data-mainid='".$row->id."' data-bgid='".$row->id_baogia."' data-hm='".$row->id_baohiem_phukien."' class='btn btn-success btn-xs'>Edit</button>
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
            if (!$row->isTang)
                $tongBaoGia += $row->thanhTien;
        }
        $thanhToan = $tongBaoGia;
        return response()->json([
            'tongBaoGia' => $tongBaoGia,
            // 'chietKhau' => $chietKhau,
            // 'thanhToan' => $thanhToan
        ]);
    }
    
    public function printBaoGia($idbg) {
        $bg = BaoGiaBHPK::find($idbg);        
        if ($bg->saler != 0) {
            // --- Kiểm tra giá tiền giữa hợp đồng với phụ kiện đang khai báo
            $soHD = $bg->soHopDongKD;
            $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
            $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
            $tongPKBan = 0;
            $tongPKBanPhuKien = 0;
            $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
            if ($soHD && $hd) {
                $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
                foreach($listpk as $row) {
                    $p = PackageV2::find($row->id_bh_pk_package);
                    if ($p->type != "cost" && $p->mapk) {
                        if ($p->type == "pay")
                            $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                    } 
                }

                $ct = ChiTietBHPK::where('id_baogia',$idbg)->get();
                foreach($ct as $row) {
                    if (!$row->isTang)
                        $tongPKBanPhuKien += $row->thanhTien;
                }
                // --------------------------
            }

            if ($tongPKBan != $tongPKBanPhuKien)
                return "Tổng phụ kiện bán: ".number_format($tongPKBanPhuKien)." không khớp với hợp đồng: ".number_format($tongPKBan)." không thể in báo giá này!";
            // ----------------------------------------------------------------
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
                $chietKhau .= number_format($row->chietKhau) . "%<w:br/>";
                if (!$row->isTang) {
                    $noiDung .= $bh->noiDung . "<w:br/>";     
                    $thanhTien .= number_format((($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100))) . "<w:br/>";
                    $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));           
                } else {
                    $noiDung .= $bh->noiDung . " (tặng)<w:br/>";      
                    $thanhTien .=  "0 <w:br/>";
                    // $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));         
                }
            }
            $tienBangChu = \HelpFunction::convert($tongCong);
            $yeuCau = $bg->yeuCau;
            $tienCoc = $bg->tienCoc;
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
                    'tienCoc' => number_format($tienCoc),
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();            
        } else {
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
                $chietKhau .= number_format($row->chietKhau) . "%<w:br/>";
                if (!$row->isTang) {
                    $noiDung .= $bh->noiDung . "<w:br/>";     
                    $thanhTien .= number_format((($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100))) . "<w:br/>";
                    $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));           
                } else {
                    $noiDung .= $bh->noiDung . " (tặng)<w:br/>";      
                    $thanhTien .=  "0 <w:br/>";
                    // $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));         
                }
            }
            $tienBangChu = \HelpFunction::convert($tongCong);
            $yeuCau = $bg->yeuCau;
            $tienCoc = $bg->tienCoc;
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
                    'tienCoc' => number_format($tienCoc),
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        }
    }

    public function printYeuCauCapVatTu($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        if ($bg->saler != 0) {
            // --- Kiểm tra giá tiền giữa hợp đồng với phụ kiện đang khai báo
            $soHD = $bg->soHopDongKD;
            $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
            $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
            $tongPKBan = 0;
            $tongPKBanPhuKien = 0;
            $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
            if ($soHD && $hd) {
                $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
                foreach($listpk as $row) {
                    $p = PackageV2::find($row->id_bh_pk_package);
                    if ($p->type != "cost" && $p->mapk) {
                        if ($p->type == "pay")
                            $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                    } 
                }

                $ct = ChiTietBHPK::where('id_baogia',$idbg)->get();
                foreach($ct as $row) {
                    if (!$row->isTang)
                        $tongPKBanPhuKien += $row->thanhTien;
                }
                // --------------------------
            }

            if ($tongPKBan != $tongPKBanPhuKien)
                return "Tổng phụ kiện bán: ".number_format($tongPKBanPhuKien)." không khớp với hợp đồng: ".number_format($tongPKBan)." không thể in báo giá này!";
            // ----------------------------------------------------------------
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

                if ($bh->loai != "Gia công ngoài") {
                    $tt .= $i++ . "<w:br/>";
                    $ma .= $bh->ma . "<w:br/>"; 
                    if (!$row->isTang) {
                        $noiDung .= $bh->noiDung . "<w:br/>";               
                    } else {
                        $noiDung .= $bh->noiDung . " (tặng)<w:br/>";               
                    }    
                    // $noiDung .= $bh->noiDung . "<w:br/>";
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        } else {
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

                if ($bh->loai != "Gia công ngoài") {
                    $tt .= $i++ . "<w:br/>";
                    $ma .= $bh->ma . "<w:br/>"; 
                    if (!$row->isTang) {
                        $noiDung .= $bh->noiDung . "<w:br/>";               
                    } else {
                        $noiDung .= $bh->noiDung . " (tặng)<w:br/>";               
                    }    
                    // $noiDung .= $bh->noiDung . "<w:br/>";
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        }
    }

    public function printLenhSuaChua($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        if ($bg->saler != 0) {
            // --- Kiểm tra giá tiền giữa hợp đồng với phụ kiện đang khai báo
            $soHD = $bg->soHopDongKD;
            $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
            $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
            $tongPKBan = 0;
            $tongPKBanPhuKien = 0;
            $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
            if ($soHD && $hd) {
                $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
                foreach($listpk as $row) {
                    $p = PackageV2::find($row->id_bh_pk_package);
                    if ($p->type != "cost" && $p->mapk) {
                        if ($p->type == "pay")
                            $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                    } 
                }

                $ct = ChiTietBHPK::where('id_baogia',$idbg)->get();
                foreach($ct as $row) {
                    if (!$row->isTang)
                        $tongPKBanPhuKien += $row->thanhTien;
                }
                // --------------------------
            }

            if ($tongPKBan != $tongPKBanPhuKien)
                return "Tổng phụ kiện bán: ".number_format($tongPKBanPhuKien)." không khớp với hợp đồng: ".number_format($tongPKBan)." không thể in báo giá này!";
            // ----------------------------------------------------------------
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
            $loai = "";
            $ma = "";
            $ktvn = ""; 
            $noiDung = "";
            $dvt = "";
            $sl = "";

            $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
            $i = 1;
            foreach($ct as $row){
                $bh = BHPK::find($row->id_baohiem_phukien);
                
                $checkktv = KTVBHPK::where([
                    ['id_baogia','=',$idbg],
                    ['id_bhpk','=',$row->id_baohiem_phukien],
                ])->exists(); 

                if ($bh->loai != "Bán thêm" && $bh->loai != "Tặng kèm") {
                    if ($checkktv) {
                        $tt .= $i++ . "<w:br/>";
                        $loai .= $bh->loai . "<w:br/>"; 
                        $ma .= $bh->ma . "<w:br/>"; 
                        // $noiDung .= $bh->noiDung . "<w:br/>";
                
                        if (!$row->isTang) {
                            $noiDung .= $bh->noiDung . "<w:br/>";               
                        } else {
                            $noiDung .= $bh->noiDung . " (tặng)<w:br/>";               
                        }
                        
                        $dvt .= $bh->dvt . "<w:br/>";
                        $sl .= $row->soLuong . "<w:br/>";

                        //Xử lý ktv
                        $n_ktv = "";
                        $ktv = KTVBHPK::where([
                            ['id_baogia','=',$idbg],
                            ['id_bhpk','=',$row->id_baohiem_phukien],
                        ])->get(); 
                        foreach($ktv as $k) {
                            $u = User::find($k->id_work);
                            $namektv = explode(" ", $u->userDetail->surname);
                            $n_ktv .= $namektv[count($namektv) - 1] . "; ";
                        }
                        if ($n_ktv == "")
                            $ktvn .= "<w:br/>";
                        else
                            $ktvn .= $n_ktv . "<w:br/>";;
                        //-----         
                    } else if ($bh->loai == "Gia công ngoài") {
                        $tt .= $i++ . "<w:br/>";
                        $loai .= $bh->loai . "<w:br/>"; 
                        $ma .= $bh->ma . "<w:br/>"; 
                        $noiDung .= $bh->noiDung . "<w:br/>";
                        $dvt .= $bh->dvt . "<w:br/>";
                        $sl .= $row->soLuong . "<w:br/>";
                        $ktvn .= "<w:br/>";
                    }                
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
                    'ktv' => $ktvn,
                    'loai' => $loai,           
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        } else {
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
            $loai = "";
            $ma = "";
            $ktvn = ""; 
            $noiDung = "";
            $dvt = "";
            $sl = "";

            $ct = ChiTietBHPK::where('id_baogia', $idbg)->get();
            $i = 1;
            foreach($ct as $row){
                $bh = BHPK::find($row->id_baohiem_phukien);
                
                $checkktv = KTVBHPK::where([
                    ['id_baogia','=',$idbg],
                    ['id_bhpk','=',$row->id_baohiem_phukien],
                ])->exists(); 

                if ($bh->loai != "Bán thêm" && $bh->loai != "Tặng kèm") {
                    if ($checkktv) {
                        $tt .= $i++ . "<w:br/>";
                        $loai .= $bh->loai . "<w:br/>"; 
                        $ma .= $bh->ma . "<w:br/>"; 
                        // $noiDung .= $bh->noiDung . "<w:br/>";
                
                        if (!$row->isTang) {
                            $noiDung .= $bh->noiDung . "<w:br/>";               
                        } else {
                            $noiDung .= $bh->noiDung . " (tặng)<w:br/>";               
                        }
                        
                        $dvt .= $bh->dvt . "<w:br/>";
                        $sl .= $row->soLuong . "<w:br/>";

                        //Xử lý ktv
                        $n_ktv = "";
                        $ktv = KTVBHPK::where([
                            ['id_baogia','=',$idbg],
                            ['id_bhpk','=',$row->id_baohiem_phukien],
                        ])->get(); 
                        foreach($ktv as $k) {
                            $u = User::find($k->id_work);
                            $namektv = explode(" ", $u->userDetail->surname);
                            $n_ktv .= $namektv[count($namektv) - 1] . "; ";
                        }
                        if ($n_ktv == "")
                            $ktvn .= "<w:br/>";
                        else
                            $ktvn .= $n_ktv . "<w:br/>";;
                        //-----         
                    } else if ($bh->loai == "Gia công ngoài") {
                        $tt .= $i++ . "<w:br/>";
                        $loai .= $bh->loai . "<w:br/>"; 
                        $ma .= $bh->ma . "<w:br/>"; 
                        $noiDung .= $bh->noiDung . "<w:br/>";
                        $dvt .= $bh->dvt . "<w:br/>";
                        $sl .= $row->soLuong . "<w:br/>";
                        $ktvn .= "<w:br/>";
                    }                
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
                    'ktv' => $ktvn,
                    'loai' => $loai,           
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        }
    }

    public function printQuyetToan($idbg) {
        $bg = BaoGiaBHPK::find($idbg);
        if ($bg->saler != 0) {
            // --- Kiểm tra giá tiền giữa hợp đồng với phụ kiện đang khai báo
            $soHD = $bg->soHopDongKD;
            $arrSoHD = ($soHD) ? explode(".",$soHD) : "";
            $num = ($arrSoHD != "") ? $arrSoHD[0] : "";
            $tongPKBan = 0;
            $tongPKBanPhuKien = 0;
            $hd = HopDong::where('code',$num)->orderBy('id', 'desc')->first();
            if ($soHD && $hd) {
                $listpk = SaleOffV2::where('id_hd',$hd->id)->get();
                foreach($listpk as $row) {
                    $p = PackageV2::find($row->id_bh_pk_package);
                    if ($p->type != "cost" && $p->mapk) {
                        if ($p->type == "pay")
                            $tongPKBan += ($p->cost - ($p->cost*$row->giamGia/100));
                    } 
                }

                $ct = ChiTietBHPK::where('id_baogia',$idbg)->get();
                foreach($ct as $row) {
                    if (!$row->isTang)
                        $tongPKBanPhuKien += $row->thanhTien;
                }
                // --------------------------
            }

            if ($tongPKBan != $tongPKBanPhuKien)
                return "Tổng phụ kiện bán: ".number_format($tongPKBanPhuKien)." không khớp với hợp đồng: ".number_format($tongPKBan)." không thể thực hiện báo giá này!";
            // ----------------------------------------------------------------
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
                $chietKhau .= number_format($row->chietKhau) . "%<w:br/>";
                if (!$row->isTang) {
                    $noiDung .= $bh->noiDung . "<w:br/>";  
                    $thanhTien .= number_format((($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100))) . "<w:br/>";
                    $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));              
                } else {
                    // $thanhTien .= "0<w:br/>";
                    // $tongCong += 0;
                    $noiDung .= $bh->noiDung . " (tặng)<w:br/>";
                    $thanhTien .= "0 <w:br/>";
                    // $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));
                }            
                // $thanhTien .= number_format((($row->donGia*$row->soLuong) - $row->chietKhau)) . "<w:br/>";
                // $tongCong += ((($row->donGia*$row->soLuong) - $row->chietKhau));
                
            }
            $tienCoc = $bg->tienCoc;
            $tienBangChu = \HelpFunction::convert($tongCong-$tienCoc);
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
                    'tienBangChu' => $tienBangChu,
                    'yeuCau' => $yeuCau,
                    'tienCoc' => number_format($tienCoc),
                    'tongCong' => number_format($tongCong-$tienCoc),
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        } else {
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
                $chietKhau .= number_format($row->chietKhau) . "%<w:br/>";
                if (!$row->isTang) {
                    $noiDung .= $bh->noiDung . "<w:br/>";  
                    $thanhTien .= number_format((($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100))) . "<w:br/>";
                    $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));              
                } else {
                    // $thanhTien .= "0<w:br/>";
                    // $tongCong += 0;
                    $noiDung .= $bh->noiDung . " (tặng)<w:br/>";
                    $thanhTien .= "0 <w:br/>";
                    // $tongCong += (($row->donGia*$row->soLuong) - (($row->donGia*$row->soLuong) * $row->chietKhau/100));
                }            
                // $thanhTien .= number_format((($row->donGia*$row->soLuong) - $row->chietKhau)) . "<w:br/>";
                // $tongCong += ((($row->donGia*$row->soLuong) - $row->chietKhau));
                
            }
            $tienCoc = $bg->tienCoc;
            $tienBangChu = \HelpFunction::convert($tongCong-$tienCoc);
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
                    'tienBangChu' => $tienBangChu,
                    'yeuCau' => $yeuCau,
                    'tienCoc' => number_format($tienCoc),
                    'tongCong' => number_format($tongCong-$tienCoc),
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            if ($bg->inProcess)
                return response()->download($pathToSave,$outhd . '.docx',$headers);
            else
                return redirect()->back();
        }
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
        $baogiakd = 0;
        $i = 1;
        $checkFromTuOnlyMonth = false; // Kiểm tra chọn thời gian báo cáo có cùng 1 tháng hay không
        $monthSelect = "";
        $date1 = "";
        $date2 = "";
        if (\HelpFunction::getOnlyMonth($tu) != \HelpFunction::getOnlyMonth($den)) {
            $checkFromTuOnlyMonth = false;
        } else {
            $checkFromTuOnlyMonth = true;
            $monthSelect = \HelpFunction::getOnlyMonth($tu);
            // Xử lý tìm lương chưa tính tháng trước
            $yearSelect = \HelpFunction::getOnlyYear($tu);
            $tempMonth = 0;
            $tempYear = 0;
            switch($monthSelect) {
                case 1: {
                    $tempMonth = 12; 
                    $tempYear = $yearSelect - 1;
                } break;
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12: {
                    $tempMonth = $monthSelect - 1; 
                    $tempYear = $yearSelect;
                } break;
                default: $tempMonth = 0;
            }
            $date1 = $tempYear."-".$tempMonth."-01";
            $date2 = $tempYear."-".$tempMonth."-".\HelpFunction::countDayInMonth($tempMonth,$tempYear);
            // ------------------------------------
        }
        switch($loai) {
            case 1: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Người tạo</th>
                    <th>Sale</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>                    
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
                                        <td>".$row->nvKD."</td>
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
                                            <td>".$row->nvKD."</td>
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
                        <th>Ngày tạo</th>
                        <th>Người tạo</th>
                        <th>Sale</th>
                        <th>KH</th>
                        <th>Số hợp đồng kinh doanh</th>
                        <th>Loại BG</th>
                        <th>Số BG</th>
                        <th>Doanh thu</th>                    
                        <th>Tặng</th>
                        <th>Chiết khấu</th>
                        <th>Thực tế thu</th>
                        <th>KT xác nhận</th>
                    </tr>
                <tbody>";   
                if ($nv == 0) {
                    $_tongdoanhthu = 0;
                    $_doanhthuthucte = 0;
                    $_doanhthuthuctekinhdoanh = 0;
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['trangThaiThu','=',true],
                        ['isBaoHiem','=', false],
                        ['isDone','=',true],
                        ['isCancel','=',false],
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime($row->ngayThu) >= strtotime($tu)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($den))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $_doanhthu = 0;
                            $_chiphitang = 0;
                            $_chietKhau = 0;
                            $_sale = "";
                            $_thucThu = 0;
                            $_chietKhauCost = 0;
                            foreach($ct as $item) {
                                $_doanhthu += ($item->soLuong * $item->donGia);
                                $_tongdoanhthu += ($item->soLuong * $item->donGia);
                                $_chietKhau = $item->chietKhau ? $item->chietKhau : 0;
                                if ($item->isTang) {
                                    $_chiphitang += ($item->soLuong * $item->donGia);
                                    if ($row->saler) {
                                        $_sale = User::find($row->saler)->userDetail->surname;
                                        $baogiakd += ($item->soLuong * $item->donGia);                                       
                                    }     
                                } else {
                                    $_chietKhauCost += (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    $_thucThu += ($item->soLuong * $item->donGia) - (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    if ($row->saler) {
                                        $_sale = User::find($row->saler)->userDetail->surname;
                                        $baogiakd += ($item->soLuong * $item->donGia);
                                        $_doanhthuthuctekinhdoanh += ($item->soLuong * $item->donGia) - (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    }   
                                    $_doanhthuthucte += ($item->soLuong * $item->donGia) - (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                }
                            }
                            echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                        <td>".$row->user->userDetail->surname."</td>
                                        <td>".$_sale."</td>
                                        <td>".$row->hoTen."</td>
                                        <td>".$row->soHopDongKD."</td>
                                        <td>".($row->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                        <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                        <td><span class='text-bold text-info'>".number_format($_doanhthu)."</span></td>
                                        <td><span class='text-bold text-warning'>".number_format($_chiphitang)."</span></td>
                                        <td><span class='text-bold text-secondary'>".number_format($_chietKhauCost)."</span></td>
                                        <td class='text-bold text-success'>".number_format($_thucThu)."</td>
                                        <td class='text-bold text-info'><strong>".\HelpFunction::revertDate($row->ngayThu)."</strong></span></td>
                                    </tr>";
                        }
                    }
                    echo "</tbody>
                        </table>";
                    echo "
                    <h3>Tổng doanh thu: <span class='text-bold text-success'>".number_format($_tongdoanhthu)."</span></h3>
                    <h4><i>Doanh thu báo giá kinh doanh: <span class='text-bold text-info'>".number_format($baogiakd)."</span></i></h4>
                    <h4><i>Doanh thu báo giá khai thác: <span class='text-bold text-info'>".number_format($_tongdoanhthu - $baogiakd)."</span></i></h4>
                    <hr/>
                    <h3>Thực tế thu: <span class='text-bold text-success'>".number_format($_doanhthuthucte)."</span></h3>
                    <h4><i>Doanh thu kinh doanh: <span class='text-bold text-info'>".number_format($_doanhthuthuctekinhdoanh)."</span></i></h4>
                    <h4><i>Doanh thu khai thác: <span class='text-bold text-info'>".number_format($_doanhthuthucte - $_doanhthuthuctekinhdoanh)."</span></i></h4>
                    ";
                } else {    
                    $_tongdoanhthu = 0;
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['trangThaiThu','=',true],
                        ['isBaoHiem','=', false],
                        ['isDone','=',true],
                        ['isCancel','=',false],
                    ])
                    // ->where([
                    //     ['isDone','=',true],
                    //     ['isCancel','=',false],
                    //     ['isBaoHiem','=', false]
                    // ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        // if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                        // &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                        if ((strtotime($row->ngayThu) >= strtotime($tu)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($den))) {
                            if ($row->saler && $row->saler == $nv) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $_doanhthu = 0;
                                $_chiphitang = 0;
                                $_chietKhau = 0;
                                $_chietKhauCost = 0;
                                $_sale = "";
                                foreach($ct as $item) {
                                    $_doanhthu += $item->thanhTien;
                                    $_tongdoanhthu += $item->thanhTien;
                                    $_chietKhau = $item->chietKhau ? $item->chietKhau : 0;
                                    if ($item->isTang) {
                                        $_chiphitang += $item->thanhTien;
                                        $_tongdoanhthu -= $item->thanhTien;
                                    }       
                                    if ($row->saler) {
                                        $_sale = User::find($row->saler)->userDetail->surname;
                                        $_chietKhauCost += (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    }                             
                                }
                                echo "<tr>
                                    <td>".($i++)."</td>
                                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                    <td>".$row->user->userDetail->surname."</td>
                                    <td>".$_sale."</td>
                                    <td>".$row->hoTen."</td>
                                    <td>".$row->soHopDongKD."</td>
                                    <td><span class='text-bold text-secondary'>Báo giá kinh doanh</span></td>
                                    <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                    <td class='text-bold text-info'>".number_format($_doanhthu)."</span></td>
                                    <td class='text-bold text-warning'>".number_format($_chiphitang)."</span></td>
                                    <td class='text-bold text-warning'>".number_format($_chietKhauCost)."</span></td>
                                    <td class='text-bold text-success'>".number_format($_doanhthu-$_chiphitang)."</td>
                                    <td class='text-bold text-info'>".\HelpFunction::revertDate($row->ngayThu)."</span></td>
                                </tr>";
                            } 
                            
                            if (!$row->saler && $row->id_user_create == $nv) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $_doanhthu = 0;
                                $_chiphitang = 0;
                                $_sale = "";
                                $_chietKhau = 0;
                                $_chietKhauCost = 0;
                                foreach($ct as $item) {
                                    $_doanhthu += $item->thanhTien;
                                    $_tongdoanhthu += $item->thanhTien;
                                    $_chietKhau = $item->chietKhau ? $item->chietKhau : 0;
                                    if ($item->isTang) {
                                        $_chiphitang += $item->thanhTien;
                                        $_tongdoanhthu -= $item->thanhTien;
                                    }                            
                                    $_chietKhauCost += (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                }
                                echo "<tr>
                                    <td>".($i++)."</td>
                                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                    <td>".$row->user->userDetail->surname."</td>
                                    <td>".$_sale."</td>
                                    <td>".$row->hoTen."</td>
                                    <td><span class='text-bold'>Báo giá khai thác</span></td>
                                    <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                    <td class='text-bold text-info'>".number_format($_doanhthu)."</span></td>
                                    <td class='text-bold text-warning'>".number_format($_chiphitang)."</span></td>
                                    <td class='text-bold text-warning'>".number_format($_chietKhauCost)."</span></td>
                                    <td class='text-bold text-success'>".number_format($_doanhthu-$_chiphitang)."</td>
                                    <td class='text-bold text-info'>".\HelpFunction::revertDate($row->ngayThu)."</span></td>
                                </tr>";
                            }                                                         
                            
                        }
                    }
                    echo "</tbody>
                        </table>";
                    echo "<h3>Tổng: <span class='text-bold text-success'>".number_format($_tongdoanhthu)."</span></h3>";
                }
            } break;  
            case 3: {
                $tong_cong = 0;
                $tong_doanhthu_cong = 0;
                echo "
                <table class='table table-striped table-bordered'>
                    <tr>
                        <th>STT</th>
                        <th>Nhân viên</th>
                        <th>Số BG</th>
                        <th>Ngày tạo</th>
                        <th>Người tạo</th>
                        <th>TVBH</th>
                        <th>Khách hàng</th>
                        <th>Loại báo giá</th>
                        <th>Công việc</th>
                        <th>Doanh thu</th>                    
                        <th>Công</th>
                        <th>Công tính lương</th>
                        <th>Ngày hoàn tất</th>
                        <th>Ngày thu tiền</th>
                    </tr>
                <tbody>";   
                if ($nv == 0) {
                    $u = User::all();
                    $i = 1;
                    foreach($u as $r){
                        if ($r->hasRole('to_phu_kien')) {
                            $ten = $r->userDetail->surname;
                            $ktv = KTVBHPK::where([
                                ["id_work","=",$r->id],
                                ["isDone","=",true]
                            ])->orderBy('id_baogia','desc')->get();
                            foreach($ktv as $k) {
                                $bg = BaoGiaBHPK::select("*")
                                ->where([
                                    ['trangThaiThu','=',true],
                                    ['isDone','=',true],
                                    ['isCancel','=',false],
                                    ['isBaoHiem','=', false],
                                    ['id','=',$k->id_baogia]
                                ])->get();
                                foreach($bg as $row) {
                                    // if ((strtotime($row->ngayThu) >= strtotime($tu)) 
                                    // &&  (strtotime($row->ngayThu) <= strtotime($den))) {
                                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) >= strtotime($tu)) 
                                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) <= strtotime($den))) {
                                        $ct = ChiTietBHPK::where([
                                            ['id_baogia','=',$k->id_baogia],
                                            ['id_baohiem_phukien','=',$k->id_bhpk]
                                        ])->get();
                                        $_doanhthu = 0;
                                        $_sale = "";
                                        $_cong = 0;
                                        $_congviec = "";
                                        $_tile = 0;
                                        $_ngayThu = $row->ngayThu;
                                        foreach($ct as $item) {
                                            $_doanhthu = $item->thanhTien;
                                            if ($row->saler) {
                                                $_sale = User::find($row->saler)->userDetail->surname;
                                            }     
                                            $ktv = KTVBHPK::where([
                                                ["id_baogia","=",$row->id],
                                                ["id_bhpk","=",$item->id_baohiem_phukien],
                                            ])->get();
                                            
                                            $ktv2 = KTVBHPK::where([
                                                ["id_baogia","=",$row->id],
                                                ["id_bhpk","=",$item->id_baohiem_phukien],
                                                ["id_work","=",$r->id],
                                            ])->first();

                                            if ($ktv)
                                                $_tile = $ktv->count();
                                            else
                                                $_tile = 0;

                                            if ($ktv2) {
                                                $bhpk = BHPK::find($ktv2->id_bhpk);
                                                $_congviec = $bhpk->noiDung;
                                                if ($_tile != 0) {
                                                    $_cong = $bhpk->congKTV / $_tile;
                                                }
                                            }    
                                            
                                        }
                                        
                                        $tong_cong += $_cong;
                                        $tong_doanhthu_cong += $_doanhthu;
                                        echo "<tr>
                                                    <td>".($i++)."</td>
                                                    <td>".$ten."</td>
                                                    <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                                    <td>".$row->user->userDetail->surname."</td>
                                                    <td>".$_sale."</td>
                                                    <td>".$row->hoTen."</td>
                                                    <td>".($row->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                                    <td class='text-bold text-info'>".$_congviec."</span></td>
                                                    <td class='text-bold text-info'>".number_format($_doanhthu)."</span></td>
                                                    <td class='text-bold text-success'>".number_format($_cong)."</td>
                                                    <td></td>
                                                    <td>".\HelpFunction::getDateRevertCreatedAt($k->updated_at)."</td>
                                                    <td>".($_ngayThu ? \HelpFunction::revertDate($_ngayThu) : "")."</td>
                                                </tr>";
                                    }
                                }                                
                            }   
                        }
                    }
                    echo "</tbody>
                    </table>";
                    echo "<h3>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_doanhthu_cong)."</span><br/>Tổng tiền công: <span class='text-bold text-success'>".number_format($tong_cong)."</span></h3>";
                } else {    
                    $r = User::find($nv);
                    // Xử lý phần lương chưa tính của tháng trước
                    $congChuaTinh = 0;
                    $tongCongThangTruoc = 0;
                    $tongCongDaTinh = 0;
                    $tongCongChuaTinh = 0;
                    if ($r->hasRole('to_phu_kien') && $date1 != "" && $date2 != "") {
                        $_ktv = KTVBHPK::where([
                            ["id_work","=",$r->id],
                            ["isDone","=",true]
                        ])->orderBy('id_baogia','desc')->get();
                        foreach($_ktv as $_k) {
                            $_bg = BaoGiaBHPK::select("*")
                            ->where([
                                ['trangThaiThu','=',true],
                                ['isDone','=',true],
                                ['isCancel','=',false],
                                ['isBaoHiem','=', false],
                                ['id','=',$_k->id_baogia]
                            ])->get();
                            foreach($_bg as $_row) {
                                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($_k->updated_at)) >= strtotime($date1)) 
                                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($_k->updated_at)) <= strtotime($date2))) {                        
                                    $__tile = 0;
                                    $__cong = 0;
                                    $__ngayThu = $_row->ngayThu;
                                    $_ct = ChiTietBHPK::where([
                                        ['id_baogia','=',$_k->id_baogia],
                                        ['id_baohiem_phukien','=',$_k->id_bhpk]
                                    ])->get();
                                    foreach($_ct as $_item) {  
                                        $_ktv = KTVBHPK::where([
                                            ["id_baogia","=",$_row->id],
                                            ["id_bhpk","=",$_item->id_baohiem_phukien],
                                        ])->get();

                                        if ($_ktv)
                                            $__tile = $_ktv->count();
                                        else
                                            $__tile = 0;
                                        $_ktv2 = KTVBHPK::where([
                                            ["id_baogia","=",$_row->id],
                                            ["id_bhpk","=",$_item->id_baohiem_phukien],
                                            ["id_work","=",$r->id],
                                        ])->first();

                                        if ($_ktv2) {
                                            $_bhpk = BHPK::find($_ktv2->id_bhpk);
                                            if ($__tile != 0) {
                                                $__cong = $_bhpk->congKTV / $__tile;
                                                // Kiểm tra công tính lương
                                                if ($__ngayThu != null && $checkFromTuOnlyMonth == true) {
                                                    if ($tempYear == \HelpFunction::getOnlyYear($__ngayThu) && $tempYear == \HelpFunction::getOnlyYear($date1)) {
                                                        $_dateKeep = \HelpFunction::getOnlyDateFromCreatedAtKeepFormat($_ktv2->updated_at);
                                                        if (\HelpFunction::getOnlyMonth($_dateKeep) == $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) == $tempMonth) {
                                                            $tongCongDaTinh += $__cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($_dateKeep) < $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) == $tempMonth) {
                                                            $tongCongDaTinh += $__cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($_dateKeep) == $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) < $tempMonth) {
                                                            $tongCongDaTinh += $__cong;
                                                        }          
                                                    } else {

                                                    }                                                                                     
                                                }
                                                // ---------------------------
                                            }
                                        }        
                                    }                                    
                                    $tongCongThangTruoc += $__cong;
                                }
                            }                                
                        }   
                    }
                    $tongCongChuaTinh = $tongCongThangTruoc - $tongCongDaTinh;
                    // -------------------------------------------------------------------
                    $i = 1;
                    $tongCongTinhLuong = 0;
                    if ($r->hasRole('to_phu_kien')) {
                        $ten = $r->userDetail->surname;
                        $ktv = KTVBHPK::where([
                            ["id_work","=",$r->id],
                            ["isDone","=",true]
                        ])->orderBy('id_baogia','desc')->get();
                        foreach($ktv as $k) {
                            $bg = BaoGiaBHPK::select("*")
                            ->where([
                                ['trangThaiThu','=',true],
                                ['isDone','=',true],
                                ['isCancel','=',false],
                                ['isBaoHiem','=', false],
                                ['id','=',$k->id_baogia]
                            ])->get();
                            foreach($bg as $row) {
                                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) >= strtotime($tu)) 
                                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) <= strtotime($den))) {
                                    $ct = ChiTietBHPK::where([
                                        ['id_baogia','=',$k->id_baogia],
                                        ['id_baohiem_phukien','=',$k->id_bhpk]
                                    ])->get();
                                    $_doanhthu = 0;
                                    $_sale = "";
                                    $_cong = 0;
                                    $_congviec = "";
                                    $_tile = 0;
                                    $_ngayThu = $row->ngayThu;
                                    $_congTinhLuong = 0;
                                    foreach($ct as $item) {
                                        $_doanhthu = $item->thanhTien;
                                        if ($row->saler) {
                                            $_sale = User::find($row->saler)->userDetail->surname;
                                        }    

                                        $ktv = KTVBHPK::where([
                                            ["id_baogia","=",$row->id],
                                            ["id_bhpk","=",$item->id_baohiem_phukien],
                                        ])->get();
                                        
                                        $ktv2 = KTVBHPK::where([
                                            ["id_baogia","=",$row->id],
                                            ["id_bhpk","=",$item->id_baohiem_phukien],
                                            ["id_work","=",$r->id],
                                        ])->first();

                                        if ($ktv)
                                            $_tile = $ktv->count();
                                        else
                                            $_tile = 0;

                                        if ($ktv2) {
                                            $bhpk = BHPK::find($ktv2->id_bhpk);
                                            $_congviec = $bhpk->noiDung;
                                            if ($_tile != 0) {
                                                $_cong = $bhpk->congKTV / $_tile;
                                                // Kiểm tra công tính lương
                                                if ($_ngayThu != null && $checkFromTuOnlyMonth == true) {
                                                    if ($yearSelect == \HelpFunction::getOnlyYear($_ngayThu) && $yearSelect == \HelpFunction::getOnlyYear($tu)) {
                                                        $dateKeep = \HelpFunction::getOnlyDateFromCreatedAtKeepFormat($ktv2->updated_at);
                                                        if (\HelpFunction::getOnlyMonth($dateKeep) == $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) == $monthSelect) {
                                                            $tongCongTinhLuong += $_cong;
                                                            $_congTinhLuong = $_cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($dateKeep) < $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) == $monthSelect) {
                                                            $tongCongTinhLuong += $_cong;
                                                            $_congTinhLuong = $_cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($dateKeep) == $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) < $monthSelect) {
                                                            $tongCongTinhLuong += $_cong; 
                                                            $_congTinhLuong = $_cong;
                                                        } 
                                                    }                                                    
                                                }
                                                // ---------------------------
                                            }
                                        }    
                                        
                                    }                                    
                                    $tong_cong += $_cong;
                                    $tong_doanhthu_cong += $_doanhthu;
                                    echo "<tr>
                                                <td>".($i++)."</td>
                                                <td>".$ten."</td>
                                                <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                                <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                                <td>".$row->user->userDetail->surname."</td>
                                                <td>".$_sale."</td>
                                                <td>".$row->hoTen."</td>
                                                <td>".($row->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                                <td class='text-bold text-info'>".$_congviec."</span></td>
                                                <td class='text-bold text-info'>".number_format($_doanhthu)."</span></td>
                                                <td class='text-bold text-primary'>".number_format($_cong)."</td>
                                                <td class='text-bold text-success'>".number_format($_congTinhLuong)." (^".number_format($tongCongTinhLuong).")</td>
                                                <td>".\HelpFunction::getDateRevertCreatedAt($k->updated_at)."</td>
                                                <td>".($_ngayThu ? \HelpFunction::revertDate($_ngayThu) : "")."</td>
                                            </tr>";
                                }
                            }                                
                        }   
                    }
                    echo "</tbody>
                    </table>";
                    if ($checkFromTuOnlyMonth) {
                        echo "<h3>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_doanhthu_cong)
                        ."</span><br/>Tổng tiền công: <span class='text-bold text-secondary'>".number_format($tong_cong)."</span>"
                        ."<br/>Tiền công tháng ".$monthSelect." năm  ".$yearSelect." được xác nhận: <span class='text-bold text-primary'>".number_format($tongCongTinhLuong)."</span></h3>"
                        ."<h3>Tiền công tháng ".($tempMonth > 9 ? $tempMonth : "0".$tempMonth)." năm ".$tempYear." chưa tính: <span class='text-bold text-warning'>".number_format($tongCongChuaTinh)."</span><br/>"
                        ."Tổng tiền công tháng ".$monthSelect." năm  ".$yearSelect." để tính lương: <span class='text-bold text-success'>".number_format($tongCongChuaTinh+$tongCongTinhLuong)."</span><br/>"
                        ."Tiền công tháng ".$monthSelect." năm  ".$yearSelect." chưa tính: <span class='text-bold text-pink'>".number_format($tong_cong-$tongCongTinhLuong)."</span></h3>";
                    } else {
                        echo "<h3>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_doanhthu_cong)
                        ."</span><br/>Tổng tiền công: <span class='text-bold text-primary'>".number_format($tong_cong)."</span>";
                    }                    
                }
            } break;     
        }
    }

    public function counterBaoCaoDoanhThu(Request $request) {
        $tu = $request->tu;
        $den = $request->den;  
        // $tu = \HelpFunction::revertDate($request->tu);
        // $den = \HelpFunction::revertDate($request->den);      
        $tongdoanhthu = 0;
        $kinhdoanh = 0;
        $khaithac = 0;
        $kinhdoanhs = 0;
        $khaithacs = 0;
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
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                if ((($row->isDone && !$row->isCancel) || (($row->inProcess && !$row->isDone && !$row->isCancel))) && $request->baoCao == 1) {
                    $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                    $_doanhthu = 0;
                    $_chiphitang = 0;
                    $_sale = "";
                    foreach($ct as $item) {
                        if (!$item->isTang) {
                            $tongdoanhthu += $item->thanhTien;
                            ($row->saler) ? $kinhdoanh += $item->thanhTien : $khaithac += $item->thanhTien;
                            ($row->trangThaiThu && $row->saler) ? $kinhdoanhs += $item->thanhTien : "";                  
                            ($row->trangThaiThu && !$row->saler) ? $khaithacs += $item->thanhTien : ""; 
                            ($row->trangThaiThu && $row->saler && $item->isTang) ? $kinhdoanhs -= $item->thanhTien : ""; 
                            ($row->trangThaiThu && !$row->saler && $item->isTang) ? $khaithacs -= $item->thanhTien : ""; 
                        }
                    }
                }

                if ($request->baoCao != 1) {
                    $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                    $_doanhthu = 0;
                    $_chiphitang = 0;
                    $_sale = "";
                    foreach($ct as $item) {
                        if (!$item->isTang) {
                            $tongdoanhthu += $item->thanhTien;
                            ($row->saler) ? $kinhdoanh += $item->thanhTien : $khaithac += $item->thanhTien;
                            ($row->trangThaiThu && $row->saler) ? $kinhdoanhs += $item->thanhTien : "";                  
                            ($row->trangThaiThu && !$row->saler) ? $khaithacs += $item->thanhTien : ""; 
                            ($row->trangThaiThu && $row->saler && $item->isTang) ? $kinhdoanhs -= $item->thanhTien : ""; 
                            ($row->trangThaiThu && !$row->saler && $item->isTang) ? $khaithacs -= $item->thanhTien : ""; 
                        }
                    }
                }
            }
        }
        return response()->json([
            "code" => 200,
            "type" => "info",
            "tongdoanhthu" => $tongdoanhthu,
            "kinhdoanh" => $kinhdoanh,
            "kinhdoanhs" => $kinhdoanhs,
            "khaithac" => $khaithac,
            "khaithacs" => $khaithacs,
        ]);
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
        $loai = $request->loai;
        if ($nv != 0) {
            $ct = KTVBHPK::where('id_work','=',$nv)
            ->orderBy('id', 'desc')
            ->get();
            $i = 1;
            foreach($ct as $row) {           
                $bg = BaoGiaBHPK::find($row->id_baogia);            
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) <= strtotime($den))) {
                    $bhpk = BHPK::find($row->id_bhpk);                
                    $stt = "";
                    $sttThu = "";
                    $tacVu = "";
                    $tang = "";
                    $giaTri = 0;
                    $_sale = "";
                    switch($loai) {
                        case 0: {
                            if ($bg->saler) {
                                $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                            } 
                            $ct = ChiTietBHPK::where([
                                ['id_baogia','=',$bg->id],
                                ['id_baohiem_phukien','=',$row->id_bhpk],
                            ])->first();
                            $giaTri = $ct->thanhTien;
                            $tang = $ct->isTang;
                            $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                            if ($row->isDone) {
                                $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                // if (Auth::user()->hasRole('system'))
                                //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                            }
                            else {
                                $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                            }     
            
                            if ($bg->trangThaiThu) {
                                $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                            }
                            else {
                                $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                            }     

                            echo "<tr>
                                    <td>".($i++)."</td>
                                    <td>".$stt."</td>
                                    <td>".$sttThu."</td>
                                    <td>
                                        ".$tacVu."
                                    </td>
                                    <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                    <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                    <td>".$_sale."</td>
                                    <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                    <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                    <td>".$bg->soKhung."</td>
                                    <td>".$bg->hoTen."</td>
                                    <td>".$bg->thongTinXe."</td>
                                    <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                    <td>".$bhpk->loai."</td>
                                    <td>".$tang."</td>
                                    <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                    <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                    <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                </tr>"; 
                        };
                            break;
                        case 7: {
                            if (!$row->isDone) {
                                if ($bg->saler) {
                                    $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                } 
                                $ct = ChiTietBHPK::where([
                                    ['id_baogia','=',$bg->id],
                                    ['id_baohiem_phukien','=',$row->id_bhpk],
                                ])->first();
                                $giaTri = $ct->thanhTien;
                                $tang = $ct->isTang;
                                $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                                if ($row->isDone) {
                                    $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                    // if (Auth::user()->hasRole('system'))
                                    //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                                }
                                else {
                                    $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                    $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                                }     
                
                                if ($bg->trangThaiThu) {
                                    $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                                }
                                else {
                                    $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                                }

                                echo "<tr>
                                    <td>".($i++)."</td>
                                    <td>".$stt."</td>
                                    <td>".$sttThu."</td>
                                    <td>
                                        ".$tacVu."
                                    </td>
                                    <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                    <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                    <td>".$_sale."</td>
                                    <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                    <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                    <td>".$bg->soKhung."</td>
                                    <td>".$bg->hoTen."</td>
                                    <td>".$bg->thongTinXe."</td>
                                    <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                    <td>".$bhpk->loai."</td>
                                    <td>".$tang."</td>
                                    <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                    <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                    <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                </tr>"; 
                            }
                        };
                            break;
                        case 8: {
                            if ($row->isDone) {
                                if ($bg->saler) {
                                    $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                } 
                                $ct = ChiTietBHPK::where([
                                    ['id_baogia','=',$bg->id],
                                    ['id_baohiem_phukien','=',$row->id_bhpk],
                                ])->first();
                                $giaTri = $ct->thanhTien;
                                $tang = $ct->isTang;
                                $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                                if ($row->isDone) {
                                    $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                    // if (Auth::user()->hasRole('system'))
                                    //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                                }
                                else {
                                    $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                    $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                                }     
                
                                if ($bg->trangThaiThu) {
                                    $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                                }
                                else {
                                    $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                                }

                                echo "<tr>
                                    <td>".($i++)."</td>
                                    <td>".$stt."</td>
                                    <td>".$sttThu."</td>
                                    <td>
                                        ".$tacVu."
                                    </td>
                                    <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                    <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                    <td>".$_sale."</td>
                                    <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                    <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                    <td>".$bg->soKhung."</td>
                                    <td>".$bg->hoTen."</td>
                                    <td>".$bg->thongTinXe."</td>
                                    <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                    <td>".$bhpk->loai."</td>
                                    <td>".$tang."</td>
                                    <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                    <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                    <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                </tr>"; 
                            }    
                        };
                            break;
                    }       
                }
            }
        } else {
            $ktv = User::all();
            foreach($ktv as $rowktv){
                if ($rowktv->hasRole("to_phu_kien")) {
                    $ct = KTVBHPK::where('id_work','=',$rowktv->id)
                    ->orderBy('id', 'desc')
                    ->get();
                    echo "<tr class='bg-pink'>
                             <td colspan='5'><strong>".$rowktv->userDetail->surname."</strong></td>                             
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>
                             <td></td>                              
                        </tr>";
                    $i = 1;
                    foreach($ct as $row) {           
                        $bg = BaoGiaBHPK::find($row->id_baogia);            
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) <= strtotime($den))) {
                            $bhpk = BHPK::find($row->id_bhpk);                
                            $stt = "";
                            $sttThu = "";
                            $tacVu = "";
                            $tang = "";
                            $giaTri = 0;
                            $_sale = "";
                            switch($loai) {
                                case 0: {
                                    if ($bg->saler) {
                                        $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                    } 
                                    $ct = ChiTietBHPK::where([
                                        ['id_baogia','=',$bg->id],
                                        ['id_baohiem_phukien','=',$row->id_bhpk],
                                    ])->first();
                                    $giaTri = $ct->thanhTien;
                                    $tang = $ct->isTang;
                                    $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                                    if ($row->isDone) {
                                        $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                        // if (Auth::user()->hasRole('system'))
                                        //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                                    }
                                    else {
                                        $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                        $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                                    }     
                    
                                    if ($bg->trangThaiThu) {
                                        $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                                    }
                                    else {
                                        $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                                    }     

                                    echo "<tr>
                                            <td>".($i++)."</td>
                                            <td>".$stt."</td>
                                            <td>".$sttThu."</td>
                                            <td>
                                                ".$tacVu."
                                            </td>
                                            <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                            <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                            <td>".$_sale."</td>
                                            <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                            <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                            <td>".$bg->soKhung."</td>
                                            <td>".$bg->hoTen."</td>
                                            <td>".$bg->thongTinXe."</td>
                                            <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                            <td>".$bhpk->loai."</td>
                                            <td>".$tang."</td>
                                            <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                            <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                            <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                        </tr>"; 
                                };
                                    break;
                                case 7: {
                                    if (!$row->isDone) {
                                        if ($bg->saler) {
                                            $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                        } 
                                        $ct = ChiTietBHPK::where([
                                            ['id_baogia','=',$bg->id],
                                            ['id_baohiem_phukien','=',$row->id_bhpk],
                                        ])->first();
                                        $giaTri = $ct->thanhTien;
                                        $tang = $ct->isTang;
                                        $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                                        if ($row->isDone) {
                                            $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                            // if (Auth::user()->hasRole('system'))
                                            //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                                        }
                                        else {
                                            $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                            $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                                        }     
                        
                                        if ($bg->trangThaiThu) {
                                            $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                                        }
                                        else {
                                            $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                                        }

                                        echo "<tr>
                                            <td>".($i++)."</td>
                                            <td>".$stt."</td>
                                            <td>".$sttThu."</td>
                                            <td>
                                                ".$tacVu."
                                            </td>
                                            <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                            <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                            <td>".$_sale."</td>
                                            <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                            <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                            <td>".$bg->soKhung."</td>
                                            <td>".$bg->hoTen."</td>
                                            <td>".$bg->thongTinXe."</td>
                                            <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                            <td>".$bhpk->loai."</td>
                                            <td>".$tang."</td>
                                            <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                            <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                            <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                        </tr>"; 
                                    }
                                };
                                    break;
                                case 8: {
                                    if ($row->isDone) {
                                        if ($bg->saler) {
                                            $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                        } 
                                        $ct = ChiTietBHPK::where([
                                            ['id_baogia','=',$bg->id],
                                            ['id_baohiem_phukien','=',$row->id_bhpk],
                                        ])->first();
                                        $giaTri = $ct->thanhTien;
                                        $tang = $ct->isTang;
                                        $tang = ($tang == 1 ? "<span class='text-success text-bold'>Có</span>" : "<span class='text-secondary'>Không</span>");
                                        if ($row->isDone) {
                                            $stt = "<span class='text-bold text-success'>Đã hoàn tất</span> (".\HelpFunction::getOnlyDateFromCreatedAt($row->updated_at).")";
                                            // if (Auth::user()->hasRole('system'))
                                            //     $tacVu = "<button id='revert' data-id='".$row->id."' class='btn btn-warning'>Hoàn trạng</button>";
                                        }
                                        else {
                                            $tacVu = "<button id='hoanTat' data-id='".$row->id."' class='btn btn-info'>Hoàn tất</button>";
                                            $stt = "<span class='text-bold text-danger'>Chưa làm</span>";
                                        }     
                        
                                        if ($bg->trangThaiThu) {
                                            $sttThu = "<span class='text-bold text-success'>Đã thu</span> (".\HelpFunction::revertDate($bg->ngayThu).")";
                                        }
                                        else {
                                            $sttThu = "<span class='text-bold text-danger'>Chưa</span>";
                                        }

                                        echo "<tr>
                                            <td>".($i++)."</td>
                                            <td>".$stt."</td>
                                            <td>".$sttThu."</td>
                                            <td>
                                                ".$tacVu."
                                            </td>
                                            <td>".($bg->saler ? "<span class='text-bold text-secondary'>Báo giá kinh doanh</span>" : "<span class='text-bold'>Báo giá khai thác</span>")."</td>
                                            <td>".\HelpFunction::getDateRevertCreatedAt($bg->created_at)."</td>
                                            <td>".$_sale."</td>
                                            <td class='text-bold text-secondary'>BG0".$bg->id."-".\HelpFunction::getDateCreatedAtRevert($bg->created_at)."</td>
                                            <td class='text-bold text-primary'>".$bg->bienSo."</td>
                                            <td>".$bg->soKhung."</td>
                                            <td>".$bg->hoTen."</td>
                                            <td>".$bg->thongTinXe."</td>
                                            <td class='text-bold text-pink'>".$bhpk->noiDung."</td>
                                            <td>".$bhpk->loai."</td>
                                            <td>".$tang."</td>
                                            <td><strong class='text-success'>".number_format($giaTri)."</strong></td>
                                            <td>".$bg->thoiGianVao." ".\HelpFunction::revertDate($bg->ngayVao)."</td>
                                            <td>".$bg->thoiGianHoanThanh." ".\HelpFunction::revertDate($bg->ngayHoanThanh)."</td>                                
                                        </tr>"; 
                                    }    
                                };
                                    break;
                            }       
                        }
                    }
                } else continue;
            }            
        }
    }

    public function loadTienDoDoanhThu(Request $request) {
        $doanhthutang = 0;
        $doanhthuban = 0;
        $thucthu = 0;
        $nv = $request->nhanVien;
        $tu = $request->tu;
        $den = $request->den;
        $loai = $request->loai;
        if ($nv != 0) {
            $ct = KTVBHPK::where('id_work','=',$nv)
            ->orderBy('id', 'desc')
            ->get();
            $i = 1;
            foreach($ct as $row) {           
                $bg = BaoGiaBHPK::find($row->id_baogia);            
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) <= strtotime($den))) {
                    $bhpk = BHPK::find($row->id_bhpk);                
                    $stt = "";
                    $sttThu = "";
                    $tacVu = "";
                    $tang = "";
                    $giaTri = 0;
                    $_sale = "";
                    switch($loai) {
                        case 0: {
                            if ($bg->saler) {
                                $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                            } 
                            $ct = ChiTietBHPK::where([
                                ['id_baogia','=',$bg->id],
                                ['id_baohiem_phukien','=',$row->id_bhpk],
                            ])->first();
                            $giaTri = $ct->thanhTien;
                            $tang = $ct->isTang;
                            // Xử lý doanh thu -----
                            if ($ct->isTang)
                                $doanhthutang += $giaTri;
                            else 
                                $doanhthuban += $giaTri;
                            
                            if ($bg->trangThaiThu) {
                                $thucthu += $giaTri;
                            }                           
                        };
                            break;
                        case 7: {
                            if (!$row->isDone) {
                                if ($bg->saler) {
                                    $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                } 
                                $ct = ChiTietBHPK::where([
                                    ['id_baogia','=',$bg->id],
                                    ['id_baohiem_phukien','=',$row->id_bhpk],
                                ])->first();
                                $giaTri = $ct->thanhTien;
                                $tang = $ct->isTang;
                                // Xử lý doanh thu -----
                                if ($ct->isTang)
                                    $doanhthutang += $giaTri;
                                else 
                                    $doanhthuban += $giaTri;
                                // ---------------------                               
                                if ($bg->trangThaiThu) {
                                    $thucthu += $giaTri;
                                }                                
                            }
                        };
                            break;
                        case 8: {
                            if ($row->isDone) {
                                if ($bg->saler) {
                                    $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                } 
                                $ct = ChiTietBHPK::where([
                                    ['id_baogia','=',$bg->id],
                                    ['id_baohiem_phukien','=',$row->id_bhpk],
                                ])->first();
                                $giaTri = $ct->thanhTien;
                                $tang = $ct->isTang;
                                // Xử lý doanh thu -----
                                if ($ct->isTang)
                                    $doanhthutang += $giaTri;
                                else 
                                    $doanhthuban += $giaTri;
                                // ---------------------                                              
                                if ($bg->trangThaiThu) {
                                    $thucthu += $giaTri;
                                }         
                            }    
                        };
                            break;
                    }       
                }
            }
        } else {
            $ktv = User::all();
            foreach($ktv as $rowktv){
                if ($rowktv->hasRole("to_phu_kien")) {
                    $ct = KTVBHPK::where('id_work','=',$rowktv->id)
                    ->orderBy('id', 'desc')
                    ->get();                    
                    $i = 1;
                    foreach($ct as $row) {           
                        $bg = BaoGiaBHPK::find($row->id_baogia);            
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($bg->created_at)) <= strtotime($den))) {
                            $bhpk = BHPK::find($row->id_bhpk);                
                            $stt = "";
                            $sttThu = "";
                            $tacVu = "";
                            $tang = "";
                            $giaTri = 0;
                            $_sale = "";
                            switch($loai) {
                                case 0: {
                                    if ($bg->saler) {
                                        $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                    } 
                                    $ct = ChiTietBHPK::where([
                                        ['id_baogia','=',$bg->id],
                                        ['id_baohiem_phukien','=',$row->id_bhpk],
                                    ])->first();
                                    $giaTri = $ct->thanhTien;
                                    $tang = $ct->isTang;
                                    // Xử lý doanh thu -----
                                    if ($ct->isTang)
                                        $doanhthutang += $giaTri;
                                    else 
                                        $doanhthuban += $giaTri;
                                    // ---------------------                                  
                                    if ($bg->trangThaiThu) {
                                        $thucthu += $giaTri;
                                    }  
                                };
                                    break;
                                case 7: {
                                    if (!$row->isDone) {
                                        if ($bg->saler) {
                                            $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                        } 
                                        $ct = ChiTietBHPK::where([
                                            ['id_baogia','=',$bg->id],
                                            ['id_baohiem_phukien','=',$row->id_bhpk],
                                        ])->first();
                                        $giaTri = $ct->thanhTien;
                                        $tang = $ct->isTang;
                                        // Xử lý doanh thu -----
                                        if ($ct->isTang)
                                            $doanhthutang += $giaTri;
                                        else 
                                            $doanhthuban += $giaTri;
                                        // ---------------------                                        
                                        if ($bg->trangThaiThu) {
                                            $thucthu += $giaTri;
                                        }
                                    }
                                };
                                    break;
                                case 8: {
                                    if ($row->isDone) {
                                        if ($bg->saler) {
                                            $_sale = User::find($bg->saler)->userDetail->surname;                                                                     
                                        } 
                                        $ct = ChiTietBHPK::where([
                                            ['id_baogia','=',$bg->id],
                                            ['id_baohiem_phukien','=',$row->id_bhpk],
                                        ])->first();
                                        $giaTri = $ct->thanhTien;
                                        $tang = $ct->isTang;
                                        // Xử lý doanh thu -----
                                        if ($ct->isTang)
                                            $doanhthutang += $giaTri;
                                        else 
                                            $doanhthuban += $giaTri;
                                        // ---------------------                                       
                                        if ($bg->trangThaiThu) {
                                            $thucthu += $giaTri;
                                        }
                                    }    
                                };
                                    break;
                            }       
                        }
                    }
                } else continue;
            }            
        }
        return response()->json([
            "doanhthutang" => $doanhthutang,
            "doanhthuban" => $doanhthuban,
            "thucthu" => $thucthu
        ]);
    }

    public function getEditHangMuc(Request $request) {
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->isDone || $bg->isCancel) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Báo giá đã huỷ, đã hoàn tất không thể ĐIỀU CHỈNH!'
            ]);
        }
        // $ct = ChiTietBHPK::where([
        //     ['id_baogia','=',$request->eid],
        //     ['id_baohiem_phukien','=',$request->ehm]
        // ])->first();
        $ct = ChiTietBHPK::find($request->mainid);
        $ktv = KTVBHPK::select("ktv_bhpk.id","ktv_bhpk.id_baogia","ktv_bhpk.id_bhpk","d.surname","d.id_user")
        ->join("users as u","u.id","=","ktv_bhpk.id_work")
        ->join("users_detail as d","u.id","=","d.id_user")
        ->where([
            ['ktv_bhpk.id_baogia','=',$request->eid],
            ['ktv_bhpk.id_bhpk','=',$request->ehm]
        ])->get();  
        if ($ct) {
            $bhpk = BHPK::find($ct->id_baohiem_phukien);
            if ($bhpk->loai == "KTV lắp đặt") {
                return response()->json([
                    "code" => 200,
                    "type" => "info",
                    "message" => "Đã load hạng mục chỉnh sửa",
                    "data" => $ct,
                    "congViec" => $bhpk->noiDung,
                    "ktv" => $ktv
                ]);
            } else {                
                return response()->json([
                    "code" => 500,
                    "type" => "warning",
                    "message" => "Hạng mục này không thể thêm KTV",
                ]);
            }      
        }
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải hạng mục"
            ]);
    }

    public function getEditHangMucHangHoa(Request $request) {
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->isDone || $bg->isCancel) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Báo giá đã huỷ, đã hoàn tất không thể ĐIỀU CHỈNH!'
            ]);
        }
        // $ct = ChiTietBHPK::where([
        //     ['id_baogia','=',$request->eid],
        //     ['id_baohiem_phukien','=',$request->ehm]
        // ])->first();
        $ct = ChiTietBHPK::find($request->mainid);
        $hbpk = BHPK::find($ct->id_baohiem_phukien);
        if ($ct) {
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã load hạng mục chỉnh sửa",
                "data" => $ct,
                "ten" => $hbpk->noiDung
            ]); 
        }
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải hạng mục"
            ]);
    }

    public function xoaKTV(Request $request) {    
        $bg = BaoGiaBHPK::find($request->eid);
        // if ($bg->isDone || $bg->isCancel) {
        //     return response()->json([
        //         'type' => 'error',
        //         'code' => 500,
        //         'message' => 'Báo giá đã huỷ, đã hoàn tất không thể xóa KTV!'
        //     ]);
        // }
        
        $xoa = KTVBHPK::find($request->id);
        
        if ($xoa->isDone) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'KTV này đã xác nhận hoàn tất công việc, không thể xoá!'
            ]);
        }

        $xoa->delete();        
        if ($xoa) {
            $ktv = KTVBHPK::select("ktv_bhpk.id","ktv_bhpk.id_baogia","ktv_bhpk.id_bhpk","d.surname","d.id_user")
            ->join("users as u","u.id","=","ktv_bhpk.id_work")
            ->join("users_detail as d","u.id","=","d.id_user")
            ->where([
                ['ktv_bhpk.id_baogia','=',$request->eid],
                ['ktv_bhpk.id_bhpk','=',$request->ehm]
            ])->get();  
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Quản lý phụ kiện";
            $nhatKy->noiDung = "Xóa KTV ra khỏi hạng mục công việc";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();    
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã xóa KTV khỏi hạng mục",               
                "ktv" => $ktv
            ]);
        }
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi xóa KTV"
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
                $nhatKy->ghiChu = Carbon::now();
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

    public function importDanhMuc(Request $request) {
        $this->validate($request,[
            'fileBase'  => 'required|mimes:xls,xlsx|max:20480',
        ]);

        if($request->hasFile('fileBase')){
            $theArray = Excel::toArray([], request()->file('fileBase')); 

        if (strval($theArray[0][0][0]) == "LOAI" && strval($theArray[0][0][1]) == "MA" && 
        strval($theArray[0][0][2]) == "NOIDUNG" && strval($theArray[0][0][3]) == "DVT" && 
        strval($theArray[0][0][4]) == "GV" && strval($theArray[0][0][5]) == "CONG" && 
        strval($theArray[0][0][6]) == "GIABAN" && strval($theArray[0][0][7]) == "DONGXE") {
                $numlen = count($theArray[0]);                    
                for($i = 1; $i < $numlen; $i++) {
                    $check = BHPK::where('ma',strtoupper($theArray[0][$i][1]))->exists();                    
                    if (!$check) {
                        $kh = new BHPK();
                        $kh->id_user_create = Auth::user()->id;
                        $kh->isPK = 1;
                        $kh->ma = strtoupper($theArray[0][$i][1]);
                        $kh->noiDung = $theArray[0][$i][2];
                        $kh->dvt = $theArray[0][$i][3];
                        $kh->donGia = $theArray[0][$i][6];
                        $kh->giaVon = $theArray[0][$i][4];
                        $kh->congKTV = $theArray[0][$i][5] ? $theArray[0][$i][5] : 0;
                        $kh->loai = $theArray[0][$i][0];    
                        $kh->loaiXe = $theArray[0][$i][7];    
                        $kh->save();
                    } else {
                        $id_bhpk = BHPK::where('ma',strtoupper($theArray[0][$i][1]))->first()->id;
                        $kh = BHPK::find($id_bhpk);
                        $kh->id_user_create = Auth::user()->id;
                        $kh->isPK = 1;
                        $kh->ma = strtoupper($theArray[0][$i][1]);
                        $kh->noiDung = $theArray[0][$i][2];
                        $kh->dvt = $theArray[0][$i][3];
                        $kh->donGia = $theArray[0][$i][6];
                        $kh->giaVon = $theArray[0][$i][4];
                        $kh->congKTV = $theArray[0][$i][5] ? $theArray[0][$i][5] : 0;
                        $kh->loai = $theArray[0][$i][0];    
                        $kh->loaiXe = $theArray[0][$i][7];    
                        $kh->save();
                    }            
                }    
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Danh mục phụ kiện - Import excel";
                $nhatKy->noiDung = "Import excel file danh mục phụ kiện vào hệ thống";
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();                
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thực hiện import excel danh mục phụ kiện',
                    'code' => 200
                ]);                  
            }
		} else {
            return response()->json([
                'type' => 'info',
                'message' => 'Không tìm thấy file import danh mục',
                'code' => 200
            ]);    
        }           
    } 

    public function postKTV(Request $request){
        $bg = BaoGiaBHPK::find($request->idbg);
        // if ($bg->isDone || $bg->isCancel) {
        //     return response()->json([
        //         'type' => 'error',
        //         'code' => 500,
        //         'message' => 'Báo giá đã huỷ, đã hoàn tất không thể thêm KTV!'
        //     ]);
        // }

        $check = KTVBHPK::where([
            ["id_baogia","=",$request->idbg],
            ["id_bhpk","=",$request->idhh],
            ["id_work","=",$request->work],
        ])->exists();
        if (!$check) {
            $ktv = new KTVBHPK();
            $ktv->id_baogia = $request->idbg;
            $ktv->id_bhpk = $request->idhh;
            $ktv->id_work = $request->work;
            $ktv->save();
            if ($ktv) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Dịch vụ - Quản lý phụ kiện";
                $nhatKy->noiDung = "Gán KTV vào hạng mục công việc";
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();    
                $ktv = KTVBHPK::select("ktv_bhpk.id","ktv_bhpk.id_baogia","ktv_bhpk.id_bhpk","d.surname","d.id_user")
                ->join("users as u","u.id","=","ktv_bhpk.id_work")
                ->join("users_detail as d","u.id","=","d.id_user")
                ->where([
                    ['ktv_bhpk.id_baogia','=',$request->idbg],
                    ['ktv_bhpk.id_bhpk','=',$request->idhh]
                ])->get();          
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thêm ktv vào hạng mục',
                    'code' => 200,
                    'ktv' => $ktv
                ]);         
            } else {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Không thể thêm KTV',
                    'code' => 500
                ]);    
            }
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'KTV đã có sẵn trên hạng mục',
                'code' => 500
            ]);
        }
    }

    public function exportExcel($from,$to,$loai,$u) {
        return Excel::download(new ExportBaoCaoDoanhThuController($from,$to,$loai,$u), 'baocaodoanhthu.xlsx');
    }

    public function exportExcelToPhuKien($from,$to,$loai,$u) {
        return Excel::download(new ExportBaoCaoDoanhThuToPhuKienController($from,$to,$loai,$u), 'baocaodoanhthutophukien.xlsx');
    }

    public function getDTPK() {
        return view("dichvu.ghinhandoanhthu");
    }

    public function getDTPKList() {
        $result = BaoGiaBHPK::select('baogia_bhpk.*','d.surname as nguoiTao','dd.surname as saleMan')
        ->join('users_detail as d','d.id_user','=','baogia_bhpk.id_user_create')
        ->leftJoin('users_detail as dd','dd.id_user','=','baogia_bhpk.saler')
        ->where([
            ['baogia_bhpk.isBaoHiem','=',false],
            ['baogia_bhpk.isDone','=',true],
            ['baogia_bhpk.isCancel','=',false],
        ])
        ->orderBy('id', 'desc')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function showEditThu(Request $request) {
        $bg = BaoGiaBHPK::find($request->id);
        $ct = ChiTietBHPK::select("b.noiDung","chitiet_bhpk.thanhTien","chitiet_bhpk.donGia","chitiet_bhpk.soLuong","chitiet_bhpk.isTang","chitiet_bhpk.chietKhau")
        ->join("baohiem_phukien as b","b.id","=","chitiet_bhpk.id_baohiem_phukien")
        ->where([
            ['chitiet_bhpk.id_baogia','=',$request->id],
        ])->get();
        if($bg) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã lấy thông tin!',
                'code' => 200,
                'data' => $bg,
                'chiTiet' => $ct
            ]);
        } else {
            return response()->json([
                'message' => 'Lỗi! Lấy thông tin thất bại!',
                'code' => 500
            ]);
        }
    }

    public function updateThu(Request $request) {
        $bg = BaoGiaBHPK::find($request->eid);
        if ($bg->isCancel) {
            return response()->json([
                'type' => 'error',
                'message' => 'Báo giá đã huỷ không thể thu tiền!',
                'code' => 500
            ]);
        }
        $bg->trangThaiThu = $request->trangThaiThu;
        $bg->ngayThu = $request->ngayThu;
        $bg->save();
        if($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Doanh thu phụ kiện";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Xác nhận đã thu tiền phụ kiện số báo giá BG0" . $request->eid 
            . "<br/>Ngày thu: " . $request->ngayThu;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã thu tiền!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function hoanTrang(Request $request) {
        if(!Auth::user()->hasRole('system')) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền hoàn trạng, liên hệ quản trị viên!',
                'code' => 200
            ]);
        }
        $bg = BaoGiaBHPK::find($request->id);
        $bg->trangThaiThu = false;
        $bg->ngayThu = null;
        $bg->save();
        if($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Doanh thu phụ kiện";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Huỷ bỏ xác nhận thu tiền số báo giá BG0" . $request->id;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã hoàn trạng!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function cancelEnd(Request $request) {
        if(!Auth::user()->hasRole('system')) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền bỏ kết thúc cho báo giá này, liên hệ quản trị viên!',
                'code' => 200
            ]);
        }
        $bg = BaoGiaBHPK::find($request->id);
        $bg->isDone = false;
        $bg->isCancel = false;
        $bg->lyDoHuy = "";
        $bg->save();
        if($bg) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Doanh thu phụ kiện";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Bỏ kết thúc số báo giá BG0" . $request->id;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã huỷ bỏ trạng thái kết thúc của báo giá!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function hoanTatCongViec(Request $request) {
        $ktv = KTVBHPK::find($request->id);
        $bhpk = BHPK::find($ktv->id_bhpk);
        $bg = BaoGiaBHPK::find($ktv->id_baogia);
        $ktv->isDone = true;
        $ktv->save();
        if($ktv) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Báo cáo tiến độ";
            $nhatKy->noiDung = "Đã xác nhận hoàn tất công việc: " . $bhpk->noiDung 
            . "; Số báo giá BG0" . $bg->id;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã hoàn tất công việc!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function hoanTrangCongViec(Request $request) {
        $ktv = KTVBHPK::find($request->id);
        $bhpk = BHPK::find($ktv->id_bhpk);
        $bg = BaoGiaBHPK::find($ktv->id_baogia);
        $ktv->isDone = false;
        $ktv->save();
        if($ktv) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Dịch vụ - Báo cáo tiến độ";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Đã bỏ hoàn tất công việc: " . $bhpk->noiDung 
            . "; Số báo giá BG0" . $bg->id;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã hoàn trạng công việc!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }
}
