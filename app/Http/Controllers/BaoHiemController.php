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

class BaoHiemController extends Controller
{
    //
    public function getGuestBaoHiemPanel() {
        return view('baohiem.khachhangbaohiem');
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
                'code' => 200
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
}
