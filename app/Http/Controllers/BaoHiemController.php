<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GuestBaoHiem;
use Illuminate\Support\Facades\Auth;
use App\NhatKy;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Excel;

use App\TypeCar;
use App\BaoHiemHopDong;
use App\User;

class BaoHiemController extends Controller
{
    //
    public function getGuestBaoHiemPanel() {
        $cars = TypeCar::all();
        return view('baohiem.khachhangbaohiem', compact('cars'));
    }

    public function getListGuestBaoHiem(Request $request) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        
        $data = GuestBaoHiem::select('guest_baohiem.*', 'd.surname as creator')
            ->leftJoin('users as u', 'u.id', '=', 'guest_baohiem.id_user_create')
            ->leftJoin('users_detail as d', 'd.id_user', '=', 'u.id')
            ->whereBetween('guest_baohiem.created_at', [$from, $to])
            ->orderBy('guest_baohiem.id', 'desc')
            ->get();
            
        return response()->json([
            'type' => 'success',
            'message' => 'Đã tải danh sách khách hàng bảo hiểm!',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function addGuestBaoHiem(Request $request) {
        if (empty($request->hoTen) || empty($request->dienThoai) || empty($request->diaChi)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc!',
                'code' => 400
            ]);
        }

        // Kiểm tra định dạng số điện thoại (phải gồm 10 chữ số)
        if (!preg_match('/^[0-9]{10}$/', $request->dienThoai)) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Số điện thoại không hợp lệ! Phải gồm 10 chữ số (Ví dụ: 0918222333).',
                'code' => 400
            ]);
        }

        // Kiểm tra trùng số điện thoại
        $check = GuestBaoHiem::where('dienThoai', $request->dienThoai)->exists();
        if ($check) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Số điện thoại này đã tồn tại trong hệ thống! Vui lòng cập nhật các thông tin khác.',
                'code' => 400
            ]);
        }

        $guest = new GuestBaoHiem();
        $guest->hoTen = $request->hoTen;
        $guest->dienThoai = $request->dienThoai;
        $guest->mst = $request->mst;
        $guest->diaChi = $request->diaChi;
        $guest->bienSo = $request->bienSo;
        $guest->soKhung = $request->soKhung;
        $guest->soMay = $request->soMay;
        $guest->thongTinXe = $request->thongTinXe;
        $guest->taiXe = $request->taiXe;
        $guest->dienThoaiTaiXe = $request->dienThoaiTaiXe;
        $guest->id_user_create = Auth::user()->id;
        $guest->save();

        if ($guest) {
            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Khách hàng bảo hiểm";
            $nhatKy->noiDung = "Thêm mới khách hàng bảo hiểm: " . $guest->hoTen . " - SĐT: " . $guest->dienThoai;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã thêm khách hàng bảo hiểm mới thành công!',
                'code' => 200,
                'data' => $guest
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể thêm khách hàng bảo hiểm!',
            'code' => 500
        ]);
    }

    public function getGuestBaoHiemEdit(Request $request) {
        $guest = GuestBaoHiem::find($request->id);
        if ($guest) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã tải thông tin khách hàng bảo hiểm!',
                'code' => 200,
                'data' => $guest
            ]);
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Không tìm thấy thông tin khách hàng bảo hiểm!',
            'code' => 404
        ]);
    }

    public function updateGuestBaoHiem(Request $request) {
        if (empty($request->id) || empty($request->ehoTen) || empty($request->edienThoai) || empty($request->ediaChi)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc!',
                'code' => 400
            ]);
        }

        // Kiểm tra định dạng số điện thoại (phải gồm 10 chữ số)
        if (!preg_match('/^[0-9]{10}$/', $request->edienThoai)) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Số điện thoại không hợp lệ! Phải gồm 10 chữ số (Ví dụ: 0918222333).',
                'code' => 400
            ]);
        }

        // Kiểm tra trùng số điện thoại (ngoại trừ bản ghi hiện tại)
        $check = GuestBaoHiem::where('dienThoai', $request->edienThoai)->where('id', '!=', $request->id)->exists();
        if ($check) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Số điện thoại này đã tồn tại cho một khách hàng khác! Vui lòng kiểm tra lại.',
                'code' => 400
            ]);
        }

        $guest = GuestBaoHiem::find($request->id);
        if ($guest) {
            $guest->hoTen = $request->ehoTen;
            $guest->dienThoai = $request->edienThoai;
            $guest->mst = $request->emst;
            $guest->diaChi = $request->ediaChi;
            $guest->bienSo = $request->ebienSo;
            $guest->soKhung = $request->esoKhung;
            $guest->soMay = $request->esoMay;
            $guest->thongTinXe = $request->ethongTinXe;
            $guest->taiXe = $request->etaiXe;
            $guest->dienThoaiTaiXe = $request->edienThoaiTaiXe;
            $guest->save();

            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Khách hàng bảo hiểm";
            $nhatKy->noiDung = "Chỉnh sửa thông tin khách hàng bảo hiểm ID " . $guest->id . ": " . $guest->hoTen . " - SĐT: " . $guest->dienThoai;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật thông tin khách hàng bảo hiểm thành công!',
                'code' => 200
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể cập nhật thông tin khách hàng bảo hiểm!',
            'code' => 500
        ]);
    }

    public function deleteGuestBaoHiem(Request $request) {
        $guest = GuestBaoHiem::find($request->id);
        if ($guest) {
            $name = $guest->hoTen;
            $phone = $guest->dienThoai;
            $guest->delete();

            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Khách hàng bảo hiểm";
            $nhatKy->noiDung = "Xóa khách hàng bảo hiểm: " . $name . " - SĐT: " . $phone;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa khách hàng bảo hiểm thành công!',
                'code' => 200
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể xóa khách hàng bảo hiểm hoặc khách hàng không tồn tại!',
            'code' => 500
        ]);
    }

    public function getHopDongBaoHiemPanel() {
        $cars = TypeCar::all();
        $guests = GuestBaoHiem::all();
        $sales = User::where('active', 1)
            ->whereHas('roles', function($query) {
                $query->where('name', 'sale');
            })
            ->with('userDetail')
            ->get()
            ->pluck('userDetail')
            ->filter();
        return view('baohiem.hopdongbaohiem', compact('cars', 'guests', 'sales'));
    }

    public function getListHopDongBaoHiem(Request $request) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        
        $data = BaoHiemHopDong::select('baohiem_hopdong.*', 'g.hoTen as guest_name', 'g.dienThoai as guest_phone', 'd.surname as creator')
            ->leftJoin('guest_baohiem as g', 'g.id', '=', 'baohiem_hopdong.id_guest_baohiem')
            ->leftJoin('users as u', 'u.id', '=', 'baohiem_hopdong.id_user_create')
            ->leftJoin('users_detail as d', 'd.id_user', '=', 'u.id')
            ->whereBetween('baohiem_hopdong.created_at', [$from, $to])
            ->orderBy('baohiem_hopdong.id', 'desc')
            ->get();
            
        return response()->json([
            'type' => 'success',
            'message' => 'Đã tải danh sách hợp đồng bảo hiểm!',
            'code' => 200,
            'data' => $data
        ]);
    }

    public function addHopDongBaoHiem(Request $request) {
        if (empty($request->id_guest_baohiem) || empty($request->donViBaoHiem) || empty($request->loaiHinhBaoHiem)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc!',
                'code' => 400
            ]);
        }

        $hd = new BaoHiemHopDong();
        $hd->id_guest_baohiem = $request->id_guest_baohiem;
        $hd->donViBaoHiem = $request->donViBaoHiem;
        $hd->loaiHinhBaoHiem = $request->loaiHinhBaoHiem;
        $hd->tongPhi = $request->tongPhi ?? 0;
        $hd->loaiXe = $request->loaiXe;
        $hd->namSanXuat = $request->namSanXuat;
        $hd->giaTriXe = $request->giaTriXe ?? 0;
        $hd->ngayCap = $request->ngayCap;
        $hd->ngayHieuLuc = $request->ngayHieuLuc;
        $hd->ngayKetThuc = $request->ngayKetThuc;
        $hd->nvKinhDoanh = $request->nvKinhDoanh;
        $hd->id_user_create = Auth::user()->id;
        $hd->save();

        if ($hd) {
            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Hợp đồng bảo hiểm";
            $nhatKy->noiDung = "Thêm mới hợp đồng bảo hiểm cho khách hàng ID: " . $hd->id_guest_baohiem . " - Tổng phí: " . $hd->tongPhi;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã thêm hợp đồng bảo hiểm mới thành công!',
                'code' => 200
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể thêm hợp đồng bảo hiểm!',
            'code' => 500
        ]);
    }

    public function getHopDongBaoHiemEdit(Request $request) {
        $hd = BaoHiemHopDong::find($request->id);
        if ($hd) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã tải thông tin hợp đồng bảo hiểm!',
                'code' => 200,
                'data' => $hd
            ]);
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Không tìm thấy thông tin hợp đồng bảo hiểm!',
            'code' => 404
        ]);
    }

    public function updateHopDongBaoHiem(Request $request) {
        if (empty($request->id) || empty($request->eid_guest_baohiem) || empty($request->edonViBaoHiem) || empty($request->eloaiHinhBaoHiem)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc!',
                'code' => 400
            ]);
        }

        $hd = BaoHiemHopDong::find($request->id);
        if ($hd) {
            $hd->id_guest_baohiem = $request->eid_guest_baohiem;
            $hd->donViBaoHiem = $request->edonViBaoHiem;
            $hd->loaiHinhBaoHiem = $request->eloaiHinhBaoHiem;
            $hd->tongPhi = $request->etongPhi ?? 0;
            $hd->loaiXe = $request->eloaiXe;
            $hd->namSanXuat = $request->enamSanXuat;
            $hd->giaTriXe = $request->egiaTriXe ?? 0;
            $hd->ngayCap = $request->engayCap;
            $hd->ngayHieuLuc = $request->engayHieuLuc;
            $hd->ngayKetThuc = $request->engayKetThuc;
            $hd->nvKinhDoanh = $request->envKinhDoanh;
            $hd->save();

            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Hợp đồng bảo hiểm";
            $nhatKy->noiDung = "Chỉnh sửa thông tin hợp đồng bảo hiểm ID " . $hd->id;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật thông tin hợp đồng bảo hiểm thành công!',
                'code' => 200
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể cập nhật thông tin hợp đồng bảo hiểm!',
            'code' => 500
        ]);
    }

    public function deleteHopDongBaoHiem(Request $request) {
        $hd = BaoHiemHopDong::find($request->id);
        if ($hd) {
            $id = $hd->id;
            $hd->delete();

            // Ghi nhật ký hoạt động
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Hợp đồng bảo hiểm";
            $nhatKy->noiDung = "Xóa hợp đồng bảo hiểm ID: " . $id;
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa hợp đồng bảo hiểm thành công!',
                'code' => 200
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Lỗi hệ thống không thể xóa hợp đồng bảo hiểm hoặc hợp đồng không tồn tại!',
            'code' => 500
        ]);
    }
}
