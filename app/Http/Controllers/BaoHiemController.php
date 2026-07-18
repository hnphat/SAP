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
            $nhatKy->ghiChu = Carbon::now();
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
            if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss') || $guest->id_user_create == Auth::user()->id)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Bạn không có quyền chỉnh sửa khách hàng này!',
                    'code' => 403
                ]);
            }
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

        $guest = GuestBaoHiem::find($request->id);
        if (!$guest) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy thông tin khách hàng bảo hiểm!',
                'code' => 404
            ]);
        }

        if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss') || $guest->id_user_create == Auth::user()->id)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền chỉnh sửa khách hàng này!',
                'code' => 403
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
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Đã cập nhật thông tin khách hàng bảo hiểm thành công!',
            'code' => 200
        ]);
    }

    public function deleteGuestBaoHiem(Request $request) {
        if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền xóa khách hàng bảo hiểm!',
                'code' => 403
            ]);
        }

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
            $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
            if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss') || $hd->id_user_create == Auth::user()->id)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Bạn không có quyền chỉnh sửa hợp đồng bảo hiểm này!',
                    'code' => 403
                ]);
            }
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
        if (!$hd) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy hợp đồng bảo hiểm!',
                'code' => 404
            ]);
        }

        if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss') || $hd->id_user_create == Auth::user()->id)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền chỉnh sửa hợp đồng bảo hiểm này!',
                'code' => 403
            ]);
        }

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
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Đã cập nhật thông tin hợp đồng bảo hiểm thành công!',
            'code' => 200
        ]);
    }

    public function deleteHopDongBaoHiem(Request $request) {
        if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền xóa hợp đồng bảo hiểm!',
                'code' => 403
            ]);
        }

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
            $nhatKy->ghiChu = Carbon::now();
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

    public function importHopDongBaoHiem(Request $request) {
        if (!(Auth::user()->hasRole('system') || Auth::user()->hasRole('boss'))) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền thực hiện chức năng này!',
                'code' => 403
            ]);
        }

        if (!$request->hasFile('excel')) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng chọn tệp Excel!',
                'code' => 400
            ]);
        }

        try {
            $theArray = Excel::toArray([], $request->file('excel'));
            if (empty($theArray) || empty($theArray[0]) || count($theArray[0]) < 2) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Tệp Excel trống hoặc không đúng cấu trúc!',
                    'code' => 400
                ]);
            }

            $rows = $theArray[0];
            $headers = $rows[0];
            
            // Map header names to column indices case-insensitively
            $headersMap = [];
            foreach ($headers as $index => $header) {
                if ($header !== null) {
                    $cleanHeader = mb_strtolower(trim($header), 'UTF-8');
                    $headersMap[$cleanHeader] = $index;
                }
            }

            // Verify essential headers
            $requiredHeaders = ['khách hàng', 'sđt', 'địa chỉ'];
            foreach ($requiredHeaders as $req) {
                if (!isset($headersMap[$req])) {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Tệp Excel thiếu cột bắt buộc: "' . $req . '". Vui lòng kiểm tra lại tệp mẫu!',
                        'code' => 400
                    ]);
                }
            }

            // Helper function to get values dynamically
            $getVal = function($row, $key) use ($headersMap) {
                $keyLower = mb_strtolower($key, 'UTF-8');
                if (isset($headersMap[$keyLower]) && isset($row[$headersMap[$keyLower]])) {
                    $val = $row[$headersMap[$keyLower]];
                    return $val !== null ? trim($val) : null;
                }
                return null;
            };

            // Date parsing helper
            $parseDate = function($val) {
                if (empty($val)) {
                    return null;
                }
                if (is_numeric($val)) {
                    try {
                        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
                $val = trim($val);
                try {
                    if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $val)) {
                        $separator = strpos($val, '/') !== false ? '/' : '-';
                        return \Carbon\Carbon::createFromFormat("d{$separator}m{$separator}Y", $val)->format('Y-m-d');
                    }
                    return \Carbon\Carbon::parse($val)->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            };

            // DateTime parsing helper
            $parseDateTime = function($val) {
                if (empty($val)) {
                    return now()->format('Y-m-d H:i:s');
                }
                if (is_numeric($val)) {
                    try {
                        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
                $val = trim($val);
                try {
                    if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}( \d{1,2}:\d{1,2}(:\d{1,2})?)?$/', $val)) {
                        $parts = explode(' ', $val);
                        $datePart = $parts[0];
                        $timePart = isset($parts[1]) ? $parts[1] : '00:00:00';
                        $separator = strpos($datePart, '/') !== false ? '/' : '-';
                        return \Carbon\Carbon::createFromFormat("d{$separator}m{$separator}Y " . (substr_count($timePart, ':') == 1 ? 'H:i' : 'H:i:s'), $datePart . ' ' . $timePart)->format('Y-m-d H:i:s');
                    }
                    return \Carbon\Carbon::parse($val)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return now()->format('Y-m-d H:i:s');
                }
            };

            // Clean numeric values helper
            $cleanNumeric = function($val) {
                if (empty($val)) {
                    return 0;
                }
                $cleaned = preg_replace('/[^0-9]/', '', $val);
                return (int)$cleaned;
            };

            $numRows = count($rows);
            $importedGuests = 0;
            $importedContracts = 0;

            \DB::beginTransaction();

            // STEP 1: Loop and insert guests
            for ($i = 1; $i < $numRows; $i++) {
                $row = $rows[$i];
                if (empty($row) || !isset($row[0])) {
                    continue;
                }

                $hoTen = $getVal($row, 'Khách hàng');
                $dienThoai = $getVal($row, 'SĐT');
                
                if (empty($hoTen) || empty($dienThoai)) {
                    continue;
                }

                // Clean phone number (leave only digits)
                $dienThoai = preg_replace('/[^0-9]/', '', $dienThoai);

                // Check if phone already exists
                $guestExists = GuestBaoHiem::where('dienThoai', $dienThoai)->exists();
                if (!$guestExists) {
                    // Create guest
                    $guest = new GuestBaoHiem();
                    $guest->hoTen = $hoTen;
                    $guest->dienThoai = $dienThoai;
                    $guest->diaChi = $getVal($row, 'Địa chỉ');
                    $guest->bienSo = $getVal($row, 'Biển số');
                    $guest->soKhung = $getVal($row, 'Số Khung');
                    
                    // ThongTinXe column: check if present, otherwise default to "Loại xe"
                    $thongTinXe = $getVal($row, 'Thông tin xe');
                    if (empty($thongTinXe)) {
                        $thongTinXe = $getVal($row, 'Loại xe');
                    }
                    $guest->thongTinXe = $thongTinXe;

                    // Match user creator
                    $creatorVal = $getVal($row, 'Người tạo');
                    $creatorId = Auth::user()->id;
                    if (!empty($creatorVal)) {
                        $userDetail = \DB::table('users_detail')
                            ->whereRaw('LOWER(surname) = ?', [mb_strtolower($creatorVal, 'UTF-8')])
                            ->first();
                        if ($userDetail) {
                            $creatorId = $userDetail->id_user;
                        } else {
                            $userObj = User::whereRaw('LOWER(name) = ?', [mb_strtolower($creatorVal, 'UTF-8')])->first();
                            if ($userObj) {
                                $creatorId = $userObj->id;
                            }
                        }
                    }
                    $guest->id_user_create = $creatorId;
                    
                    // Date created
                    $ngayTaoVal = $getVal($row, 'Ngày tạo');
                    if (!empty($ngayTaoVal)) {
                        $guest->created_at = $parseDateTime($ngayTaoVal);
                    }
                    
                    $guest->save();
                    $importedGuests++;
                }
            }

            // STEP 2: Loop and insert insurance contracts
            for ($i = 1; $i < $numRows; $i++) {
                $row = $rows[$i];
                if (empty($row)) continue;

                $dienThoai = $getVal($row, 'SĐT');
                if (empty($dienThoai)) continue;

                $dienThoai = preg_replace('/[^0-9]/', '', $dienThoai);

                // Find guest
                $guest = GuestBaoHiem::where('dienThoai', $dienThoai)->first();
                if (!$guest) {
                    continue;
                }

                $hd = new BaoHiemHopDong();
                $hd->id_guest_baohiem = $guest->id;
                $hd->donViBaoHiem = $getVal($row, 'Đơn vị bảo hiểm');
                $hd->loaiHinhBaoHiem = $getVal($row, 'Loại hình');
                $hd->tongPhi = $cleanNumeric($getVal($row, 'Tổng phí'));
                $hd->loaiXe = $getVal($row, 'Loại xe');
                $hd->namSanXuat = $getVal($row, 'Năm sản xuất');
                $hd->giaTriXe = $cleanNumeric($getVal($row, 'Giá trị xe'));
                
                $hd->ngayCap = $parseDate($getVal($row, 'Ngày cấp'));
                $hd->ngayHieuLuc = $parseDate($getVal($row, 'Hiệu lực'));
                $hd->ngayKetThuc = $parseDate($getVal($row, 'Kết thúc'));
                $hd->nvKinhDoanh = $getVal($row, 'Nhân viên KD');

                // Match user creator
                $creatorVal = $getVal($row, 'Người tạo');
                $creatorId = Auth::user()->id;
                if (!empty($creatorVal)) {
                    $userDetail = \DB::table('users_detail')
                        ->whereRaw('LOWER(surname) = ?', [mb_strtolower($creatorVal, 'UTF-8')])
                        ->first();
                    if ($userDetail) {
                        $creatorId = $userDetail->id_user;
                    } else {
                        $userObj = User::whereRaw('LOWER(name) = ?', [mb_strtolower($creatorVal, 'UTF-8')])->first();
                        if ($userObj) {
                            $creatorId = $userObj->id;
                        }
                    }
                }
                $hd->id_user_create = $creatorId;

                $ngayTaoVal = $getVal($row, 'Ngày tạo');
                if (!empty($ngayTaoVal)) {
                    $hd->created_at = $parseDateTime($ngayTaoVal);
                }

                $hd->save();
                $importedContracts++;
            }

            \DB::commit();

            // Log activity
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:i:s");
            $nhatKy->chucNang = "Bảo hiểm - Hợp đồng bảo hiểm";
            $nhatKy->noiDung = "Nhập Excel: đã thêm " . $importedGuests . " khách hàng mới và " . $importedContracts . " hợp đồng bảo hiểm.";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Đã nhập thành công ' . $importedGuests . ' khách hàng mới và ' . $importedContracts . ' hợp đồng bảo hiểm!',
                'code' => 200
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình nhập Excel: ' . $e->getMessage(),
                'code' => 500
            ]);
        }
    }

    public function createSettlement(Request $request) {
        if (empty($request->selected_ids)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Vui lòng chọn ít nhất một bản ghi từ danh sách dữ liệu để thực hiện tạo Quyết toán',
                'code' => 400
            ], 200);
        }

        $ids = explode(',', $request->selected_ids);
        $contracts = BaoHiemHopDong::whereIn('id', $ids)->get();

        if ($contracts->isEmpty()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy các đơn hàng bảo hiểm đã chọn!',
                'code' => 404
            ], 200);
        }

        // Kiểm tra tính đồng nhất của id_guest_baohiem và soQuyetToan
        $guestId = null;
        foreach ($contracts as $contract) {
            if ($contract->soQuyetToan !== null && $contract->soQuyetToan !== '') {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Dữ liệu vừa chọn đã tạo Quyết toán rồi vui lòng chọn dữ liệu chưa tạo quyết toán',
                    'code' => 400
                ], 200);
            }
            if ($guestId === null) {
                $guestId = $contract->id_guest_baohiem;
            } elseif ($guestId != $contract->id_guest_baohiem) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Các đơn hàng được chọn phải thuộc về cùng một khách hàng!',
                    'code' => 400
                ], 200);
            }
        }

        // Lấy thông tin khách hàng
        $guest = GuestBaoHiem::find($guestId);
        if (!$guest) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy thông tin khách hàng!',
                'code' => 404
            ], 200);
        }

        // Lấy thông tin created_at của dữ liệu có id cao nhất
        $maxIdContract = $contracts->sortByDesc('id')->first();
        $createdAt = Carbon::parse($maxIdContract->created_at);
        $ngay = $createdAt->format('d');
        $thang = $createdAt->format('m');
        $nam = $createdAt->format('Y');

        // Tìm soQuyetToan cao nhất trong tháng và năm tương ứng
        $maxSeq = BaoHiemHopDong::whereYear('created_at', $nam)
            ->whereMonth('created_at', $thang)
            ->max('soQuyetToan');

        $newSeq = $maxSeq ? ($maxSeq + 1) : 1;
        $soBG = 'QT' . str_pad($newSeq, 3, '0', STR_PAD_LEFT) . '-' . $nam . $thang;

        // Lưu thông tin vào DB
        $yeuCau = $request->yeuCau;
        foreach ($contracts as $contract) {
            $contract->soQuyetToan = $newSeq;
            $contract->yeuCau = $yeuCau;
            $contract->save();
        }

        // Đường dẫn tệp template
        $templatePath = public_path('template/BAOHIEM/QUYETTOAN.docx');
        if (!file_exists($templatePath)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy tệp mẫu QUYETTOAN.docx tại thư mục public/template/BAOHIEM/',
                'code' => 404
            ], 200);
        }

        // Xử lý tạo tệp Word
        $templateProcessor = new TemplateProcessor($templatePath);

        // Dọn dẹp XML để loại bỏ các thẻ Word tự động chèn vào giữa các ký tự của placeholder
        $cleanXmlFunc = function($xml) {
            $xml = preg_replace('/<(?:w:proofErr|w:noProof)[^>]*?>/', '', $xml);
            return preg_replace_callback('/\$[^\{]*?\{([^\}]+)\}/', function($match) {
                return '${' . strip_tags($match[1]) . '}';
            }, $xml);
        };

        // Sử dụng Reflection để cập nhật các thuộc tính protected của TemplateProcessor
        $reflection = new \ReflectionClass($templateProcessor);
        
        $mainPartProp = $reflection->getProperty('tempDocumentMainPart');
        $mainPartProp->setAccessible(true);
        $mainPartProp->setValue($templateProcessor, $cleanXmlFunc($mainPartProp->getValue($templateProcessor)));

        $headersProp = $reflection->getProperty('tempDocumentHeaders');
        $headersProp->setAccessible(true);
        $headersProp->setValue($templateProcessor, array_map($cleanXmlFunc, $headersProp->getValue($templateProcessor)));

        $footersProp = $reflection->getProperty('tempDocumentFooters');
        $footersProp->setAccessible(true);
        $footersProp->setValue($templateProcessor, array_map($cleanXmlFunc, $footersProp->getValue($templateProcessor)));

        // Lấy người tạo từ đơn hàng có ID lớn nhất
        $creator = User::find($maxIdContract->id_user_create);
        $creatorName = '';
        if ($creator) {
            $creatorName = $creator->userDetail ? $creator->userDetail->surname : $creator->name;
        }

        // Người bán: nvKinhDoanh nếu không có sẽ lấy tên id_user_create
        $nguoiBan = $maxIdContract->nvKinhDoanh ?: $creatorName;

        // Đổ dữ liệu chung vào template
        $templateProcessor->setValues([
            'ngay' => $ngay,
            'thang' => $thang,
            'nam' => $nam,
            'soBG' => $soBG,
            'khachHang' => $guest->hoTen,
            'dienThoai' => $guest->dienThoai,
            'diaChi' => $guest->diaChi,
            'bienSo' => $guest->bienSo ?: '',
            'thongTinXe' => $guest->thongTinXe ?: '',
            'soKhung' => $guest->soKhung ?: '',
            'nguoiTao' => $creatorName,
            'nguoiBan' => $nguoiBan,
            'yeuCau' => $yeuCau ?: ''
        ]);

        // Đổ dữ liệu bảng
        $tt = "";
        $noiDung = "";
        $donGia = "";
        $chietKhau = "";
        $thanhTien = "";
        $tongCong = 0;

        $index = 1;
        foreach ($contracts as $c) {
            $thanhTienVal = $c->tongPhi - $c->giamGia;
            $tongCong += $thanhTienVal;

            $tt .= $index . '<w:br/>';
            $noiDung .= $c->loaiHinhBaoHiem . '<w:br/>';
            $donGia .= number_format($c->tongPhi) . '<w:br/>';
            $chietKhau .= number_format($c->giamGia) . '<w:br/>';
            $thanhTien .= number_format($thanhTienVal) . '<w:br/>';

            $index++;
        }

        // Loại bỏ thẻ <w:br/> ở dòng cuối để tránh khoảng trống thừa
        $tt = rtrim($tt, '<w:br/>');
        $noiDung = rtrim($noiDung, '<w:br/>');
        $donGia = rtrim($donGia, '<w:br/>');
        $chietKhau = rtrim($chietKhau, '<w:br/>');
        $thanhTien = rtrim($thanhTien, '<w:br/>');

        $templateProcessor->setValue('tt', $tt);
        $templateProcessor->setValue('noiDung', $noiDung);
        $templateProcessor->setValue('donGia', $donGia);
        $templateProcessor->setValue('chietKhau', $chietKhau);
        $templateProcessor->setValue('thanhTien', $thanhTien);

        // Tổng cộng & chữ
        $templateProcessor->setValue('tongCong', number_format($tongCong));

        // Chuyển tiền thành chữ
        $tienBangChuText = \HelpFunction::convert($tongCong);
        if ($tienBangChuText) {
            $tienBangChuText = trim($tienBangChuText);
            $firstChar = mb_substr($tienBangChuText, 0, 1, 'UTF-8');
            $rest = mb_substr($tienBangChuText, 1, null, 'UTF-8');
            $tienBangChu = mb_strtoupper($firstChar, 'UTF-8') . $rest . ' đồng';
        } else {
            $tienBangChu = 'Không đồng';
        }
        $templateProcessor->setValue('tienBangChu', $tienBangChu);

        // Lưu tệp tạm thời
        $tempDir = public_path('template/BAOHIEM');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $fileName = 'Quyet_toan_BH_' . \HelpFunction::changeTitle($guest->hoTen) . '_' . date('YmdHis') . '.docx';
        $pathToSave = $tempDir . '/' . $fileName;
        $templateProcessor->saveAs($pathToSave);

        // Ghi nhật ký hệ thống
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:i:s");
        $nhatKy->chucNang = "Bảo hiểm - Quyết toán";
        $nhatKy->noiDung = "Tạo Quyết toán " . $soBG . " cho khách hàng: " . $guest->hoTen;
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->save();

        // Download và tự động xóa file sau khi gởi đi
        return response()->download($pathToSave, $fileName)->deleteFileAfterSend(true);
    }
}

