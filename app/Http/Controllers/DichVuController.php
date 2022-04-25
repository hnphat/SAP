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
        if($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã thêm khách hàng'
            ]);
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function delKhachHang(Request $request) {
        $kh = GuestDv::find($request->id);
        if (Auth::user()->hasRole('system'))
            $kh->delete();
        elseif(Auth::user()->id == $kh->id_user_create) {
            $kh->delete();
        } else return response()->json([
            'type' => 'error',
            'code' => 500,
            'message' => 'Bạn không có quyền xoá nội dung này'
        ]);

        if ($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã xoá'
            ]);
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
        if($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã cập nhật khách hàng'
            ]);
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
        if($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã thêm hạng mục'
            ]);
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function delHangMuc(Request $request) {
        $kh = BHPK::find($request->id);
        if (Auth::user()->hasRole('system'))
            $kh->delete();
        elseif(Auth::user()->id == $kh->id_user_create) {
            $kh->delete();
        } else return response()->json([
            'type' => 'error',
            'code' => 500,
            'message' => 'Bạn không có quyền xoá nội dung này'
        ]);

        if ($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã xoá'
            ]);
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
        $kh->isPK = $request->eisPK;
        $kh->ma = strtoupper($request->ema);
        $kh->noiDung = $request->enoiDung;
        $kh->dvt = $request->edvt;
        $kh->donGia = $request->edonGia;
        $kh->type = $request->eloai;        
        $kh->save();
        if($kh)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã cập nhật hạng mục'
            ]);
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
        if ($bg)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tạo báo giá',
                'soBG' => "BG0".$bg->id."-".Date('Y')."".Date('m'),
                'idBG' => $bg->id
            ]);
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
        if ($bg)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã chỉnh sửa báo giá',
                'soBG' => "BG0".$bg->id."-".Date('Y')."".Date('m'),
                'idBG' => $bg->id
            ]);
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
            if ($bg)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã xoá báo giá'
                ]);
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
        if ($bg)
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã tiến hành thực hiện báo giá',
                'data' => $bg
            ]);
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
            $bg->save();
            if ($bg)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã huỷ báo giá',
                    'data' => $bg
                ]);
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
            if ($bg)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã hoàn tất báo giá',
                    'data' => $bg
                ]);
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
        $ct->id_user_work = $request->kyThuatVien;
        $ct->thanhTien = ($request->soLuong * $request->donGia) - $request->chietKhau;
        $ct->save();
        if ($ct)
                return response()->json([
                    'type' => 'info',
                    'code' => 200,
                    'message' => 'Đã lưu!'
                ]);
            else
                return response()->json([
                    'type' => 'info',
                    'code' => 500,
                    'message' => 'Lỗi '
                ]);
    }
}
