<?php

namespace App\Http\Controllers;

use App\Report;
use App\ReportCar;
use App\TypeCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function showReport() {
        $typeCar = TypeCar::all();
        return view('report.report', ['typeCar' => $typeCar]);
    }

    public function khoiTao(Request $request) {
        $doanhSo = 0;
        $thiPhan = 0;
        $month = Date('m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('mkt'))
            $typeUser = "mkt";
        elseif (Auth::user()->hasRole('xuong'))
            $typeUser = "xuong";
        elseif (Auth::user()->hasRole('cskh'))
            $typeUser = "cskh";
        elseif (Auth::user()->hasRole('hcns'))
            $typeUser = "hcns";
        elseif (Auth::user()->hasRole('it'))
            $typeUser = "it";
        elseif (Auth::user()->hasRole('drp'))
            $typeUser = "ptdl";

        $checkSale = Report::where([
            ['ngayReport','like', '%'.$month],
            ['type','like', $typeUser],
            ['doanhSoThang', '!=', null],
            ['thiPhanThang', '!=', null]
        ])->first();

        if ($checkSale !== null && $checkSale->count() > 0) {
            $doanhSo = $checkSale->doanhSoThang;
            $thiPhan = $checkSale->thiPhanThang;
        }

        $today = Date('d-m-Y');
        $checkIn = Report::where([
            ['ngayReport','like', $today],
            ['type','like', $typeUser]
        ])->exists();
        if (!$checkIn) {
            $report = new Report();
            $report->type = $typeUser;
            $report->user_report = Auth::user()->id;
            $report->ngayReport = Date('d-m-Y');
            $report->timeReport = Date('H:s:i');
            $report->doanhSoThang = ($doanhSo != 0) ? $doanhSo : null;
            $report->thiPhanThang = ($thiPhan != 0) ? $thiPhan : null;
            $report->save();
            if ($report) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Hệ thống đã khởi tạo báo cáo hôm nay!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'danger',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => ' Báo cáo hôm nay đã được tạo!',
                'code' => 200
            ]);
        }
    }

    public function loadReport() {
        $ds = 0;
        $tp = 0;
        $today = Date('d-m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('mkt'))
            $typeUser = "mkt";
        elseif (Auth::user()->hasRole('xuong'))
            $typeUser = "xuong";
        elseif (Auth::user()->hasRole('cskh'))
            $typeUser = "cskh";
        elseif (Auth::user()->hasRole('hcns'))
            $typeUser = "hcns";
        elseif (Auth::user()->hasRole('it'))
            $typeUser = "it";
        elseif (Auth::user()->hasRole('drp'))
            $typeUser = "ptdl";

        $checkIn = Report::where([
            ['ngayReport','like', $today],
            ['type','like', $typeUser]
        ])->exists();

        if ($checkIn) {
            $report = Report::select('*')->where([
                ['ngayReport','like', $today],
                ['type','like', $typeUser]
            ])->first();
            if ($report !== null) {
                $ds = ($report->doanhSoThang != null) ? 1 : 0;
                $tp = ($report->thiPhanThang != null) ? 1 : 0;
            }
            return response()->json([
                'ds' => $ds,
                'tp' => $tp,
                'type' => 'info',
                'message' => ' Tải báo cáo thành công!',
                'code' => 200,
                'data' => $report
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => ' Chưa có báo cáo nào trong hôm nay!',
                'code' => 500
            ]);
        }
    }

    public function saveReport(Request $request) {
        $today = Date('d-m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('mkt'))
            $typeUser = "mkt";
        elseif (Auth::user()->hasRole('xuong'))
            $typeUser = "xuong";
        elseif (Auth::user()->hasRole('cskh'))
            $typeUser = "cskh";
        elseif (Auth::user()->hasRole('hcns'))
            $typeUser = "hcns";
        elseif (Auth::user()->hasRole('it'))
            $typeUser = "it";
        elseif (Auth::user()->hasRole('drp'))
            $typeUser = "ptdl";

        $report = Report::where([
            ['ngayReport','like', $today],
            ['type','like', $typeUser]
        ])->update([
           'doanhSoThang' => $request->doanhSoThang,
            'thiPhanThang' => $request->thiPhanThang,
            'xuatHoaDon' => $request->xuatHoaDon,
            'xuatNgoaiTinh' => $request->xuatNgoaiTinh,
            'xuatTrongTinh' => $request->xuatTrongTinh,
            'hdHuy' => $request->hdHuy,
            'ctInternet' => $request->ctInternet,
            'ctShowroom' => $request->ctShowroom,
            'ctHotline' => $request->ctHotline,
            'ctSuKien' => $request->ctSuKien,
            'ctBLD' => $request->ctBLD,
            'saleInternet' => $request->saleInternet,
            'saleMoiGioi' => $request->saleMoiGioi,
            'saleThiTruong' => $request->saleThiTruong,
            'khShowRoom' => $request->khShowRoom,
            'baoDuong' => $request->baoDuong,
            'suaChua' => $request->suaChua,
            'Dong' => $request->dong,
            'Son' => $request->son,
            'congBaoDuong' => $request->congBaoDuong,
            'congSuaChuaChung' => $request->congSuaChuaChung,
            'congDong' => $request->congDong,
            'congSon' => $request->congSon,
            'dtPhuTung' => $request->dtPhuTung,
            'dtDauNhot' => $request->dtDauNhot,
            'dtPhuTungBan' => $request->dtPhuTungBan,
            'dtDauNhotBan' => $request->dtDauNhotBan,
            'phuTungMua' => $request->phuTungMua,
            'dauNhotMua' => $request->dauNhotMua,
            'tonBaoDuong' => $request->tonBaoDuong,
            'tonSuaChuaChung' => $request->tonSuaChuaChung,
            'tonDong' => $request->tonDong,
            'tonSon' => $request->tonSon,
            'tiepNhanBaoDuong' => $request->tiepNhanBaoDuong,
            'tiepNhanSuaChuaChung' => $request->tiepNhanSuaChuaChung,
            'tiepNhanDong' => $request->tiepNhanDong,
            'tiepNhanSon' => $request->tiepNhanSon,
            'hoanThanhBaoDuong' => $request->hoanThanhBaoDuong,
            'hoanThanhSuaChuaChung' => $request->hoanThanhSuaChuaChung,
            'hoanThanhDong' => $request->hoanThanhDong,
            'hoanThanhSon' => $request->hoanThanhSon,
            'callDatHenSuccess' => $request->callDatHenSuccess,
            'callDatHenFail' => $request->callDatHenFail,
            'datHen' => $request->datHen,
            'dvHaiLong' => $request->dvHaiLong,
            'dvKhongHaiLong' => $request->dvKhongHaiLong,
            'dvKhongThanhCong' => $request->dvKhongThanhCong,
            'muaXeSuccess' => $request->muaXeSuccess,
            'muaXeFail' => $request->muaXeFail,
            'duyetBanLe' => $request->duyetBanLe,
            'knThaiDo' => $request->knThaiDo,
            'knChatLuong' => $request->knChatLuong,
            'knThoiGian' => $request->knThoiGian,
            'knVeSinh' => $request->knVeSinh,
            'knGiaCa' => $request->knGiaCa,
            'knKhuyenMai' => $request->knKhuyenMai,
            'knDatHen' => $request->knDatHen,
            'knTraiNghiem' => $request->knTraiNghiem,
            'khBanGiao' => $request->khBanGiao,
            'khSuKien' => $request->khSuKien
        ]);
        if ($report) {
            return response()->json([
                'type' => 'success',
                'message' => ' Đã lưu báo cáo!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => ' Lỗi! Bạn phải khởi tạo báo cáo trước!',
                'code' => 500
            ]);
        }
    }

    public function addCar(Request $request) {
        $reportCar = new ReportCar();
        $reportCar->ngayTao = Date('d-m-Y');
        $reportCar->id_report = $request->idReport;
        $reportCar->soLuong = $request->soLuong;
        $reportCar->dongXe = $request->typeCar;
        $reportCar->save();
        if($reportCar) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã thêm chi tiết hợp đồng",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể thêm chi tiết hợp đồng",
                'code' => 500
            ]);
        }
    }

    public function loadAddCar($id) {
        $reportCar = ReportCar::where('id_report', $id)->get();
        echo "<table class='table table-striped'><tr>
               <th>Dòng xe</th>";
                foreach($reportCar as $row)
                    echo "<th>".$row->typeCar->name."</th>";
            echo "</tr><tr><td><strong>Số lượng</strong></td>";
                foreach($reportCar as $row)
                    echo "<td>".$row->soLuong." <button type='button' id='delCar' data-id='".$row->id."' class='badge badge-danger'>Xóa</button></td>";
                echo "</tr></table>";
    }

    public function deleteCar(Request $request) {
        $reportCar = ReportCar::where('id', $request->id)->delete();
        if($reportCar) {
            return response()->json([
                'message' => 'Đã xóa!',
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
