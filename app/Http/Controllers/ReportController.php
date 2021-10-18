<?php

namespace App\Http\Controllers;

use App\Report;
use App\ReportNhap;
use App\ReportWork;
use App\ReportCar;
use App\ReportXuat;
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
           'timeReport' => Date('H:s'),
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
            'tonSon' => $request->tonSong,
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
            'khSuKien' => $request->khSuKien,
            'clock' => true
        ]);
        if ($report) {
            return response()->json([
                'type' => 'success',
                'message' => ' Đã lưu và gửi báo cáo!',
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
    public function saveNotSend(Request $request) {
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
            'tonSon' => $request->tonSong,
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
                'message' => ' Đã lưu!',
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
        $checkExist = ReportCar::where('id_report', $id)->exists();
        if($checkExist)
            $checkClock = $reportCar->first()->report->clock;
        echo "<table class='table table-striped'><tr>
               <th>Dòng xe</th>";
                foreach($reportCar as $row)
                    echo "<th>".$row->typeCar->name."</th>";
            echo "</tr><tr><td><strong>Số lượng</strong></td>";
                foreach($reportCar as $row) {
                        if (!$checkClock)
                            echo "<td>".$row->soLuong." <button type='button' id='delCar' data-id='".$row->id."' class='badge badge-danger'>Xóa</button></td>";
                        else
                            echo "<td>".$row->soLuong."</td>";
                }
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

    public function addWork(Request $request) {
        $reportWork = new ReportWork();
        $reportWork->id_report = $request->idReport;
        $reportWork->tenCongViec = $request->tenCongViec;
        $reportWork->tienDo = $request->tienDo;
        $reportWork->deadLine = $request->deadLine;
        $reportWork->type = "cv";
        $reportWork->ketQua = $request->ketQua;
        $reportWork->ghiChu = $request->ghiChu;
        $reportWork->save();
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã thêm công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể thêm công việc",
                'code' => 500
            ]);
        }
    }

    public function loadWork($id) {
        $i = 1;
        $reportWork = ReportWork::where('id_report', $id)->get();
        $checkExist = ReportWork::where('id_report', $id)->exists();
        if($checkExist)
        $checkClock = $reportWork->first()->report->clock;
        echo "<table class='table table-striped'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>";
                foreach ($reportWork as $row) {
                    if (!$checkClock)
                        echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                        <td>
                                            <button id='delWork' data-id='".$row->id."' type='button' class='btn btn-danger btn-sm'>Xóa</button>
                                        </td>
                                    </tr>";
                    else
                        echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                        <td>
                                        </td>
                                    </tr>";
                }
        echo "</table>";
    }

    public function deleteWork(Request $request) {
        $reportWork = ReportWork::where('id', $request->id)->delete();
        if($reportWork) {
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

    public function addNhap(Request $request) {
        $reportNhap = new ReportNhap();
        $reportNhap->id_report = $request->idReport;
        $reportNhap->nhaCungCap = $request->nhaCungCap;
        $reportNhap->hanMuc = $request->hanMuc;
        $reportNhap->soLuong = $request->soLuong;
        $reportNhap->tongTon = $request->tongTon;
        $reportNhap->ghiChu = $request->ghiChu;
        $reportNhap->save();
        if($reportNhap) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã nhập kho",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể nhập kho",
                'code' => 500
            ]);
        }
    }

    public function loadNhap($id) {
        $i = 1;
        $reportNhap = ReportNhap::where('id_report', $id)->get();
        $checkExist = ReportNhap::where('id_report', $id)->exists();
        if($checkExist)
        $checkClock = $reportNhap->first()->report->clock;
        echo "<table class='table table-striped'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Nhà cung cấp</th>
                                        <th>Hạn mục</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tồn</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>";
                foreach($reportNhap as $row) {
                    if (!$checkClock)
                        echo " <tr>
                                <td>".$i++."</td>
                                <td>".$row->nhaCungCap."</td>
                                <td>".$row->hanMuc."</td>
                                <td>".$row->soLuong."</td>
                                <td>".$row->tongTon."</td>
                                <td>".$row->ghiChu."</td>
                                <td>
                                   <button id='delNhap' data-id='".$row->id."' type='button' class='btn btn-danger btn-sm'>Xóa</button>
                                </td>
                            </tr>";
                    else
                        echo " <tr>
                                <td>".$i++."</td>
                                <td>".$row->nhaCungCap."</td>
                                <td>".$row->hanMuc."</td>
                                <td>".$row->soLuong."</td>
                                <td>".$row->tongTon."</td>
                                <td>".$row->ghiChu."</td>
                                <td>
                                </td>
                            </tr>";
                }
        echo "</table>";
    }

    public function deleteNhap(Request $request) {
        $reportNhap = ReportNhap::where('id', $request->id)->delete();
        if($reportNhap) {
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

    public function addXuat(Request $request) {
        $reportXuat = new ReportXuat();
        $reportXuat->id_report = $request->idReport;
        $reportXuat->tenNhanVien = $request->tenNhanVien;
        $reportXuat->hanMuc = $request->hanMuc;
        $reportXuat->soLuong = $request->soLuong;
        $reportXuat->tongTon = $request->tongTon;
        $reportXuat->ghiChu = $request->ghiChu;
        $reportXuat->save();
        if($reportXuat) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã xuất kho",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể xuất kho",
                'code' => 500
            ]);
        }
    }

    public function loadXuat($id) {
        $i = 1;
        $reportXuat = ReportXuat::where('id_report', $id)->get();
        $checkExist = ReportXuat::where('id_report', $id)->exists();
        if($checkExist)
        $checkClock = $reportXuat->first()->report->clock;
        echo "<table class='table table-striped'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên nhân viên</th>
                                        <th>Hạn mục</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tồn</th>
                                        <th>Ghi chú</th>
                                        <th>Hành động</th>
                                    </tr>";
                      foreach ($reportXuat as $row) {
                          if (!$checkClock)
                            echo " <tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenNhanVien."</td>
                                        <td>".$row->hanMuc."</td>
                                        <td>".$row->soLuong."</td>
                                        <td>".$row->tongTon."</td>
                                        <td>".$row->ghiChu."</td>
                                        <td>
                                            <button id='delXuat' data-id='".$row->id."' type='button'  class='btn btn-danger btn-sm'>Xóa</button>
                                        </td>
                                    </tr>";
                          else
                              echo " <tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenNhanVien."</td>
                                        <td>".$row->hanMuc."</td>
                                        <td>".$row->soLuong."</td>
                                        <td>".$row->tongTon."</td>
                                        <td>".$row->ghiChu."</td>
                                        <td>
                                        </td>
                                    </tr>";
                      }
        echo "</table>";
    }

    public function deleteXuat(Request $request) {
        $reportXuat = ReportXuat::where('id', $request->id)->delete();
        if($reportXuat) {
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

    public function overviewList() {
        return view('report.overview');
    }

    public function getPKD($_date) {
        $i = 1;
        $sum = 0;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'pkd'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <h5>Doanh số tháng: <span class='text-blue'><strong>".$report->doanhSoThang."</strong></span></h5>
                    <h5>Thị phần tháng: <span class='text-blue'><strong>".$report->thiPhanThang."%</strong></span></h5><br>
                    <div class='row'>
                        <div class='col-md-8'>
                            <h4>Xuất hóa đơn</h4>
                            <h5>- Xuất hóa đơn: <span class='text-success'><strong>".$report->xuatHoaDon."</strong></span></h5>
                            <h5>- Xuất trong tỉnh: <span class='text-success'><strong>".$report->xuatNgoaiTinh."</strong></span></h5>
                            <h5>- Xuất ngoài tỉnh: <span class='text-success'><strong>".$report->xuatTrongTinh."</strong></span></h5>
                            <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'><tr>
               <th>Ký hợp đồng</th>";
            foreach($report->reportCar as $row)
                echo "<th>".$row->typeCar->name."</th>";
            echo "</tr><tr><td><strong>Số lượng</strong></td>";
            foreach($report->reportCar as $row) {
                echo "<td>".$row->soLuong."</td>";
                $sum+=$row->soLuong;
            }
            echo "</tr></table>";
                            echo "</div>
                            <h5>- Ký hợp đồng: <span class='text-success'><strong>".$sum."</strong></span></h5>
                            <h5>- Hủy hợp đồng: <span class='text-success'><strong>".$report->hdHuy."</strong></span></h5>
                        </div>
                        <div class='col-md-4'>
                            <h4>KHTN Công ty</h4>
                            <h5>- Internet: <span class='text-success'><strong>".$report->ctInternet."</strong></span></h5>
                            <h5>- Showroom: <span class='text-success'><strong>".$report->ctShowroom."</strong></span></h5>
                            <h5>- Hotline: <span class='text-success'><strong>".$report->ctHotline."</strong></span></h5>
                            <h5>- Sự kiện: <span class='text-success'><strong>".$report->ctSuKien."</strong></span></h5>
                            <h5>- Ban lãnh đạo: <span class='text-success'><strong>".$report->ctBLD."</strong></span></h5>
                            <h4>KHTN SALER</h4>
                            <h5>- Internet: <span class='text-success'><strong>".$report->saleInternet."</strong></span></h5>
                            <h5>- Môi giới: <span class='text-success'><strong>".$report->saleMoiGioi."</strong></span></h5>
                            <h5>- Thị trường: <span class='text-success'><strong>".$report->saleThiTruong."</strong></span></h5>
                        </div>
                    </div>
                    <h5>Lượt khách showroom: <span class='text-success'><strong>".$report->khShowRoom."</strong></span></h5>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td $color>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                    </tr>";
            }
            echo "</table>";
                    echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getPKDAll($_month) {
        $i = 1;
        $sum = 0;
        $doanhSoThang = 0;
        $thiPhanThang = 0;
        $xuatHoaDon = 0;
        $xuatNgoaiTinh = 0;
        $xuatTrongTinh = 0;
        $hdHuy = 0;
        $ctInternet = 0;
        $ctShowroom = 0;
        $ctHotline = 0;
        $ctSuKien = 0;
        $ctBLD = 0;
        $saleInternet = 0;
        $saleMoiGioi = 0;
        $saleThiTruong = 0;
        $khShowRoom = 0;

        $ds = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'pkd']
        ])->first();
        if ($ds !== null) {
            $doanhSoThang = $ds->doanhSoThang;
            $thiPhanThang = $ds->thiPhanThang;
        }

        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'pkd'],
            ['clock','=', true]
        ])->get();

        if ($report !== null) {
            foreach($report as $row) {
                $xuatHoaDon += $row->xuatHoaDon;
                $xuatNgoaiTinh += $row->xuatNgoaiTinh;
                $xuatTrongTinh += $row->xuatTrongTinh;
                $hdHuy += $row->hdHuy;
                $ctInternet += $row->ctInternet;
                $ctShowroom += $row->ctShowroom;
                $ctHotline += $row->ctHotline;
                $ctSuKien += $row->ctSuKien;
                $ctBLD += $row->ctBLD;
                $saleInternet += $row->saleInternet;
                $saleMoiGioi += $row->saleMoiGioi;
                $saleThiTruong += $row->saleThiTruong;
                $khShowRoom += $row->khShowRoom;
            }


            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                    <h5>Doanh số tháng: <span class='text-blue'><strong>".$doanhSoThang." (".number_format((($xuatNgoaiTinh+$xuatTrongTinh)*100/$doanhSoThang),2)."%)</strong></span></h5>
                    <h5>Thị phần tháng: <span class='text-blue'><strong>".$thiPhanThang."%</strong></span></h5><br>
                    <div class='row'>
                        <div class='col-md-8'>
                            <h4>Xuất hóa đơn</h4>
                            <h5>- Xuất hóa đơn: <span class='text-success'><strong>".$xuatHoaDon."</strong></span></h5>
                            <h5>- Xuất trong tỉnh: <span class='text-success'><strong>".$xuatNgoaiTinh."</strong></span></h5>
                            <h5>- Xuất ngoài tỉnh: <span class='text-success'><strong>".$xuatTrongTinh."</strong></span></h5>
                            ";
            echo "<h5>- Hủy hợp đồng: <span class='text-success'><strong>".$hdHuy."</strong></span></h5>
                        </div>
                        <div class='col-md-4'>
                            <h4>KHTN Công ty</h4>
                            <h5>- Internet: <span class='text-success'><strong>".$ctInternet."</strong></span></h5>
                            <h5>- Showroom: <span class='text-success'><strong>".$ctShowroom."</strong></span></h5>
                            <h5>- Hotline: <span class='text-success'><strong>".$ctHotline."</strong></span></h5>
                            <h5>- Sự kiện: <span class='text-success'><strong>".$ctSuKien."</strong></span></h5>
                            <h5>- Ban lãnh đạo: <span class='text-success'><strong>".$ctBLD."</strong></span></h5>
                            <h4>KHTN SALER</h4>
                            <h5>- Internet: <span class='text-success'><strong>".$saleInternet."</strong></span></h5>
                            <h5>- Môi giới: <span class='text-success'><strong>".$saleMoiGioi."</strong></span></h5>
                            <h5>- Thị trường: <span class='text-success'><strong>".$saleThiTruong."</strong></span></h5>
                        </div>
                    </div>
                    <h5>Lượt khách showroom: <span class='text-success'><strong>".$khShowRoom."</strong></span></h5>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
                    echo "<table class='table table-striped table-bordered'>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Ngày</th>
                                                        <th>Tên công việc</th>
                                                        <th>Tiến độ</th>
                                                        <th>Deadline</th>
                                                        <th>Kết quả</th>
                                                        <th>Ghi chú</th>
                                                    </tr>";
                    foreach($report as $row) {
                        foreach ($row->reportWork as $row2) {
                            $color = "";
                            if ($row->tienDo < 100)
                                $color = "class='text-red text-bold'";
                            echo "<tr>
                                                <td>" . $i++ . "</td>
                                                <td>" . $row->ngayReport . "</td>
                                                <td>" . $row2->tenCongViec . "</td>
                                                <td $color>" . $row2->tienDo . "%</td>
                                                <td>" . \HelpFunction::revertDate($row2->deadLine) . "</td>
                                                <td>" . $row2->ketQua . "</td>
                                                <td>" . $row2->ghiChu . "</td>
                                            </tr>";
                        }
                    }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getPDV($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'pdv'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <div class='row'>
                        <div class='col-md-6'>
                            <h4>LƯỢT XE</h4>
                            <h5>- Bảo dưỡng: <span class='text-success'><strong>".$report->baoDuong."</strong></span></h5>
                            <h5>- Sữa chữa: <span class='text-success'><strong>".$report->suaChua."</strong></span></h5>
                            <h5>- Đồng: <span class='text-success'><strong>".$report->Dong."</strong></span></h5>
                            <h5>- Sơn: <span class='text-success'><strong>".$report->Son."</strong></span></h5>
                        </div>
                        <div class='col-md-6'>
                            <h4>DOANH THU DỊCH VỤ</h4>
                            <h5>- Công bảo dưỡng: <span class='text-success'><strong>".number_format($report->congBaoDuong)."</strong></span></h5>
                            <h5>- Công sữa chữa: <span class='text-success'><strong>".number_format($report->congSuaChuaChung)."</strong></span></h5>
                            <h5>- Công đồng: <span class='text-success'><strong>".number_format($report->congDong)."</strong></span></h5>
                            <h5>- Công sơn: <span class='text-success'><strong>".number_format($report->congSon)."</strong></span></h5>
                        </div>
                        </div>
                        <div class='row'>
                        <div class='col-md-6'>
                            <h4>DOANH THU PHỤ TÙNG - DẦU NHỚT</h4>
                            <h5>- Phụ tùng sửa chữa: <span class='text-success'><strong>".number_format($report->dtPhuTung)."</strong></span></h5>
                            <h5>- Dầu nhớt sữa chữa: <span class='text-success'><strong>".number_format($report->dtDauNhot)."</strong></span></h5>
                            <h5>- Phụ tùng bán ngoài: <span class='text-success'><strong>".number_format($report->dtPhuTungBan)."</strong></span></h5>
                            <h5>- Dầu nhớt bán ngoài: <span class='text-success'><strong>".number_format($report->dtDauNhotBan)."</strong></span></h5>
                        </div>
                        <div class='col-md-6'>
                            <h4>MUA PHỤ TÙNG/DẦU NHỚT HTV/TST</h4>
                            <h5>- Tiền mua phụ tùng: <span class='text-success'><strong>".number_format($report->phuTungMua)."</strong></span></h5>
                            <h5>- Tiền mua dầu nhớt: <span class='text-success'><strong>".number_format($report->dauNhotMua)."</strong></span></h5>
                        </div>
                    </div>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td $color>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                    </tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getPDVAll($_month) {
        $i = 1;
        $baoDuong = 0;
        $suaChua = 0;
        $Dong = 0;
        $Son = 0;
        $congBaoDuong = 0;
        $congSuaChuaChung = 0;
        $congDong = 0;
        $congSon = 0;
        $dtPhuTung = 0;
        $dtDauNhot = 0;
        $dtPhuTungBan = 0;
        $dtDauNhotBan = 0;
        $phuTungMua = 0;
        $dauNhotMua = 0;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'pdv'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            foreach ($report as $row) {
                $baoDuong += $row->baoDuong;
                $suaChua += $row->suaChua;
                $Dong += $row->Dong;
                $Son += $row->Son;
                $congBaoDuong += $row->congBaoDuong;
                $congSuaChuaChung += $row->congSuaChuaChung;
                $congDong += $row->congDong;
                $congSon += $row->congSon;
                $dtPhuTung += $row->dtPhuTung;
                $dtDauNhot += $row->dtDauNhot;
                $dtPhuTungBan += $row->dtPhuTungBan;
                $dtDauNhotBan += $row->dtDauNhotBan;
                $phuTungMua += $row->phuTungMua;
                $dauNhotMua += $row->dauNhotMua;
            }

            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                <div class='row'>
                        <div class='col-md-6'>
                            <h4>LƯỢT XE</h4>
                            <h5>- Bảo dưỡng: <span class='text-success'><strong>".$baoDuong."</strong></span></h5>
                            <h5>- Sữa chữa: <span class='text-success'><strong>".$suaChua."</strong></span></h5>
                            <h5>- Đồng: <span class='text-success'><strong>".$Dong."</strong></span></h5>
                            <h5>- Sơn: <span class='text-success'><strong>".$Son."</strong></span></h5>
                        </div>
                        <div class='col-md-6'>
                            <h4>DOANH THU DỊCH VỤ</h4>
                            <h5>- Công bảo dưỡng: <span class='text-success'><strong>".number_format($congBaoDuong)."</strong></span></h5>
                            <h5>- Công sữa chữa: <span class='text-success'><strong>".number_format($congSuaChuaChung)."</strong></span></h5>
                            <h5>- Công đồng: <span class='text-success'><strong>".number_format($congDong)."</strong></span></h5>
                            <h5>- Công sơn: <span class='text-success'><strong>".number_format($congSon)."</strong></span></h5>
                        </div>
                        </div>
                        <div class='row'>
                        <div class='col-md-6'>
                            <h4>DOANH THU PHỤ TÙNG - DẦU NHỚT</h4>
                            <h5>- Phụ tùng sửa chữa: <span class='text-success'><strong>".number_format($dtPhuTung)."</strong></span></h5>
                            <h5>- Dầu nhớt sữa chữa: <span class='text-success'><strong>".number_format($dtDauNhot)."</strong></span></h5>
                            <h5>- Phụ tùng bán ngoài: <span class='text-success'><strong>".number_format($dtPhuTungBan)."</strong></span></h5>
                            <h5>- Dầu nhớt bán ngoài: <span class='text-success'><strong>".number_format($dtDauNhotBan)."</strong></span></h5>
                        </div>
                        <div class='col-md-6'>
                            <h4>MUA PHỤ TÙNG/DẦU NHỚT HTV/TST</h4>
                            <h5>- Tiền mua phụ tùng: <span class='text-success'><strong>".number_format($phuTungMua)."</strong></span></h5>
                            <h5>- Tiền mua dầu nhớt: <span class='text-success'><strong>".number_format($dauNhotMua)."</strong></span></h5>
                        </div>
                    </div>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getXuong($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'xuong'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <div class='row'>
                    <div class='col-md-4'>
                        <h4>XE TỒN</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$report->tonBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$report->tonSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$report->tonDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$report->tonSon."</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>LƯỢT XE TIẾP NHẬN</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$report->tiepNhanBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$report->tiepNhanSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$report->tiepNhanDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$report->tiepNhanSon."</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>HOÀN THÀNH</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$report->hoanThanhBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$report->hoanThanhSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$report->hoanThanhDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$report->hoanThanhSon."</strong></span></h5>
                    </div>
                </div>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td $color>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                    </tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getXuongAll($_month) {
        $i = 1;
        $tonBaoDuong = 0;
        $tonSuaChuaChung = 0;
        $tonDong = 0;
        $tonSon = 0;
        $tiepNhanBaoDuong = 0;
        $tiepNhanSuaChuaChung = 0;
        $tiepNhanDong = 0;
        $tiepNhanSon = 0;
        $hoanThanhBaoDuong = 0;
        $hoanThanhSuaChuaChung = 0;
        $hoanThanhDong = 0;
        $hoanThanhSon = 0;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'xuong'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            foreach ($report as $row) {
                $tonBaoDuong += $row->tonBaoDuong;
                $tonSuaChuaChung += $row->tonSuaChuaChung;
                $tonDong += $row->tonDong;
                $tonSon += $row->tonSon;
                $tiepNhanBaoDuong += $row->tiepNhanBaoDuong;
                $tiepNhanSuaChuaChung += $row->tiepNhanSuaChuaChung;
                $tiepNhanDong += $row->tiepNhanDong;
                $tiepNhanSon += $row->tiepNhanSon;
                $hoanThanhBaoDuong += $row->hoanThanhBaoDuong;
                $hoanThanhSuaChuaChung += $row->hoanThanhSuaChuaChung;
                $hoanThanhDong += $row->hoanThanhDong;
                $hoanThanhSon += $row->hoanThanhSon;
            }

            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                <div class='row'>
                    <div class='col-md-4'>
                        <h4>XE TỒN</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$tonBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$tonSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$tonDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$tonSon."</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>LƯỢT XE TIẾP NHẬN</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$tiepNhanBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$tiepNhanSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$tiepNhanDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$tiepNhanSon."</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>HOÀN THÀNH</h4>
                        <h5>- Bảo dưỡng: <span class='text-success'><strong>".$hoanThanhBaoDuong."</strong></span></h5>
                        <h5>- Sữa chữa chung: <span class='text-success'><strong>".$hoanThanhSuaChuaChung."</strong></span></h5>
                        <h5>- Đồng: <span class='text-success'><strong>".$hoanThanhDong."</strong></span></h5>
                        <h5>- Sơn: <span class='text-success'><strong>".$hoanThanhSon."</strong></span></h5>
                    </div>
                </div>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getCSKH($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'cskh'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <div class='row'>
                    <div class='col-md-6'>
                        <h4>NHẮC BẢO DƯỠNG / ĐẶT HẸN</h4>
                        <h5>- Cuộc gọi thành công: <span class='text-success'><strong>".$report->callDatHenSuccess."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$report->callDatHenFail."</strong></span></h5>
                        <h5>- Đặt hẹn: <span class='text-success'><strong>".$report->datHen."</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>THEO DÕI SAU DỊCH VỤ</h4>
                        <h5>- Khách hàng hài lòng: <span class='text-success'><strong>".$report->dvHaiLong."</strong></span></h5>
                        <h5>- Khách hàng không hài lòng: <span class='text-success'><strong>".$report->dvKhongHaiLong."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$report->dvKhongThanhCong."</strong></span></h5>
                    </div>
                </div>
                 <div class='row'>
                    <div class='col-md-6'>
                        <h4>THEO DÕI SAU MUA XE</h4>
                        <h5>- Cuộc gọi thành công: <span class='text-success'><strong>".$report->muaXeSuccess."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$report->muaXeFail."</strong></span></h5>
                        <h5>- Kiểm chứng bán lẻ: <span class='text-success'><strong>".$report->duyetBanLe."</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>KHIẾU NẠI</h4>
                        <h5>- Thái độ nhân viên: <span class='text-success'><strong>".$report->knThaiDo."</strong></span></h5>
                        <h5>- Chất lượng sửa chữa: <span class='text-success'><strong>".$report->knChatLuong."</strong></span></h5>
                        <h5>- Thời gian sửa chữa: <span class='text-success'><strong>".$report->knThoiGian."</strong></span></h5>
                        <h5>- Vệ sinh: <span class='text-success'><strong>".$report->knVeSinh."</strong></span></h5>
                        <h5>- Giá cả: <span class='text-success'><strong>".$report->knGiaCa."</strong></span></h5>
                        <h5>- Hậu mãi - khuyến mãi: <span class='text-success'><strong>".$report->knKhuyenMai."</strong></span></h5>
                        <h5>- Đặt hẹn - tiếp nhận: <span class='text-success'><strong>".$report->knDatHen."</strong></span></h5>
                        <h5>- Trải nghiệm khách hàng: <span class='text-success'><strong>".$report->knTraiNghiem."</strong></span></h5>
                    </div>
                </div>
                    <br>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->tenCongViec."</td>
                                        <td $color>".$row->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                                        <td>".$row->ketQua."</td>
                                        <td>".$row->ghiChu."</td>
                                    </tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getCSKHAll($_month) {
        $i = 1;
        $callDatHenSuccess = 0;
        $callDatHenFail = 0;
        $datHen = 0;
        $dvHaiLong = 0;
        $dvKhongHaiLong = 0;
        $dvKhongThanhCong = 0;
        $muaXeSuccess = 0;
        $muaXeFail = 0;
        $duyetBanLe = 0;
        $knThaiDo = 0;
        $knChatLuong = 0;
        $knThoiGian = 0;
        $knVeSinh = 0;
        $knGiaCa = 0;
        $knKhuyenMai = 0;
        $knDatHen = 0;
        $knTraiNghiem = 0;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'cskh'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            foreach ($report as $row) {
                $callDatHenSuccess += $row->callDatHenSuccess;
                $callDatHenFail += $row->callDatHenFail;
                $datHen += $row->datHen;
                $dvHaiLong += $row->dvHaiLong;
                $dvKhongHaiLong += $row->dvKhongHaiLong;
                $dvKhongThanhCong += $row->dvKhongThanhCong;
                $muaXeSuccess += $row->muaXeSuccess;
                $muaXeFail += $row->muaXeFail;
                $duyetBanLe += $row->duyetBanLe;
                $knThaiDo += $row->knThaiDo;
                $knChatLuong += $row->knChatLuong;
                $knThoiGian += $row->knThoiGian;
                $knVeSinh += $row->knVeSinh;
                $knGiaCa += $row->knGiaCa;
                $knKhuyenMai += $row->knKhuyenMai;
                $knDatHen += $row->knDatHen;
                $knTraiNghiem += $row->knTraiNghiem;
            }

            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                <div class='row'>
                    <div class='col-md-6'>
                        <h4>NHẮC BẢO DƯỠNG / ĐẶT HẸN</h4>
                        <h5>- Cuộc gọi thành công: <span class='text-success'><strong>".$callDatHenSuccess."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$callDatHenFail."</strong></span></h5>
                        <h5>- Đặt hẹn: <span class='text-success'><strong>".$datHen."</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>THEO DÕI SAU DỊCH VỤ</h4>
                        <h5>- Khách hàng hài lòng: <span class='text-success'><strong>".$dvHaiLong."</strong></span></h5>
                        <h5>- Khách hàng không hài lòng: <span class='text-success'><strong>".$dvKhongHaiLong."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$dvKhongThanhCong."</strong></span></h5>
                    </div>
                </div>
                 <div class='row'>
                    <div class='col-md-6'>
                        <h4>THEO DÕI SAU MUA XE</h4>
                        <h5>- Cuộc gọi thành công: <span class='text-success'><strong>".$muaXeSuccess."</strong></span></h5>
                        <h5>- Cuộc gọi không thành công: <span class='text-success'><strong>".$muaXeFail."</strong></span></h5>
                        <h5>- Kiểm chứng bán lẻ: <span class='text-success'><strong>".$duyetBanLe."</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>KHIẾU NẠI</h4>
                        <h5>- Thái độ nhân viên: <span class='text-success'><strong>".$knThaiDo."</strong></span></h5>
                        <h5>- Chất lượng sửa chữa: <span class='text-success'><strong>".$knChatLuong."</strong></span></h5>
                        <h5>- Thời gian sửa chữa: <span class='text-success'><strong>".$knThoiGian."</strong></span></h5>
                        <h5>- Vệ sinh: <span class='text-success'><strong>".$knVeSinh."</strong></span></h5>
                        <h5>- Giá cả: <span class='text-success'><strong>".$knGiaCa."</strong></span></h5>
                        <h5>- Hậu mãi - khuyến mãi: <span class='text-success'><strong>".$knKhuyenMai."</strong></span></h5>
                        <h5>- Đặt hẹn - tiếp nhận: <span class='text-success'><strong>".$knDatHen."</strong></span></h5>
                        <h5>- Trải nghiệm khách hàng: <span class='text-success'><strong>".$knTraiNghiem."</strong></span></h5>
                    </div>
                </div>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getHCNS($_date) {
        $i = 1;
        $j = 1;
        $k = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'hcns'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <br>
                <h4>CÔNG VIỆC</h4>
                <div class='table-responsive'>";
                echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Tên công việc</th>
                        <th>Tiến độ</th>
                        <th>Deadline</th>
                        <th>Kết quả</th>
                        <th>Ghi chú</th>
                        </tr>";
                            foreach ($report->reportWork as $row) {
                                $color = "";
                                if ($row->tienDo < 100)
                                    $color = "class='text-red text-bold'";
                            echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->tenCongViec."</td>
                            <td $color>".$row->tienDo."%</td>
                            <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                            <td>".$row->ketQua."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
                        }
                echo "</table></div>";

                            echo "<br>
                <h4>NHẬP KHO</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Nhà cung cấp</th>
                        <th>Hạn mục</th>
                        <th>Số lượng</th>
                        <th>Tổng tồn</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach ($report->reportNhap as $row) {
                echo "<tr>
                            <td>".$j++."</td>
                            <td>".$row->nhaCungCap."</td>
                            <td>".$row->hanMuc."</td>
                            <td>".$row->soLuong."</td>
                            <td>".$row->tongTon."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
            }
            echo "</table></div>";

            echo "<br>
                <h4>XUẤT KHO</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Tên nhân viên</th>
                        <th>Hạn mục</th>
                        <th>Số lượng</th>
                        <th>Tổng tồn</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach ($report->reportXuat as $row) {
                echo "<tr>
                            <td>".$k++."</td>
                            <td>".$row->tenNhanVien."</td>
                            <td>".$row->hanMuc."</td>
                            <td>".$row->soLuong."</td>
                            <td>".$row->tongTon."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getHCNSAll($_month) {
        $i = 1;
        $j = 1;
        $k = 1;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'hcns'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
            echo "<br>
                <h4>NHẬP KHO</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Ngày</th>
                        <th>Nhà cung cấp</th>
                        <th>Hạn mục</th>
                        <th>Số lượng</th>
                        <th>Tổng tồn</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach($report as $row){
                foreach ($row->reportNhap as $row2) {
                    echo "<tr>
                            <td>".$j++."</td>
                            <td>".$row->ngayReport."</td>
                            <td>".$row2->nhaCungCap."</td>
                            <td>".$row2->hanMuc."</td>
                            <td>".$row2->soLuong."</td>
                            <td>".$row2->tongTon."</td>
                            <td>".$row2->ghiChu."</td>
                        </tr>";
                }
            }
            echo "</table></div>";

            echo "<br>
                <h4>XUẤT KHO</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Ngày</th>
                        <th>Tên nhân viên</th>
                        <th>Hạn mục</th>
                        <th>Số lượng</th>
                        <th>Tổng tồn</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach($report as $row){
                foreach ($row->reportXuat as $row2) {
                    echo "<tr>
                            <td>".$k++."</td>
                            <td>".$row->ngayReport."</td>
                            <td>".$row2->tenNhanVien."</td>
                            <td>".$row2->hanMuc."</td>
                            <td>".$row2->soLuong."</td>
                            <td>".$row2->tongTon."</td>
                            <td>".$row2->ghiChu."</td>
                        </tr>";
                }
            }
            echo "</table></div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getMkt($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'mkt'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <div>
                    <h5>- KHTN bàn giao: <span class='text-success'><strong>".$report->khBanGiao."</strong></span></h5>
                    <h5>- KHTN sự kiện: <span class='text-success'><strong>".$report->khSuKien."</strong></span></h5>
                </div>
                <br>
                <h4>CÔNG VIỆC</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Tên công việc</th>
                        <th>Tiến độ</th>
                        <th>Deadline</th>
                        <th>Kết quả</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->tenCongViec."</td>
                            <td $color>".$row->tienDo."%</td>
                            <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                            <td>".$row->ketQua."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getMktAll($_month) {
        $i = 1;
        $khBanGiao = 0;
        $khSuKien = 0;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'mkt'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {

            foreach ($report as $row) {
                $khBanGiao += $row->khBanGiao;
                $khSuKien += $row->khSuKien;
            }

            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                    <div>
                        <h5>- KHTN bàn giao: <span class='text-success'><strong>".$khBanGiao."</strong></span></h5>
                        <h5>- KHTN sự kiện: <span class='text-success'><strong>".$khSuKien."</strong></span></h5>
                    </div>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getIt($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'it'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <br>
                <h4>CÔNG VIỆC</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Tên công việc</th>
                        <th>Tiến độ</th>
                        <th>Deadline</th>
                        <th>Kết quả</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->tenCongViec."</td>
                            <td $color>".$row->tienDo."%</td>
                            <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                            <td>".$row->ketQua."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getItAll($_month) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'it'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getPtdl($_date) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', \HelpFunction::revertDate($_date)],
            ['type','like', 'ptdl'],
            ['clock','=', true]
        ])->first();
        if ($report !== null) {
            echo "<h5>Thời gian báo cáo: <span class='text-red'><strong>".$report->timeReport."</strong></span></h5>
                <h5>Ngày báo cáo: <span class='text-red'><strong>".$report->ngayReport."</strong></span></h5>
                <br>
                <h4>CÔNG VIỆC</h4>
                <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                        <tr>
                        <th>STT</th>
                        <th>Tên công việc</th>
                        <th>Tiến độ</th>
                        <th>Deadline</th>
                        <th>Kết quả</th>
                        <th>Ghi chú</th>
                        </tr>";
            foreach ($report->reportWork as $row) {
                $color = "";
                if ($row->tienDo < 100)
                    $color = "class='text-red text-bold'";
                echo "<tr>
                            <td>".$i++."</td>
                            <td>".$row->tenCongViec."</td>
                            <td $color>".$row->tienDo."%</td>
                            <td>".\HelpFunction::revertDate($row->deadLine)."</td>
                            <td>".$row->ketQua."</td>
                            <td>".$row->ghiChu."</td>
                        </tr>";
            }
            echo "</table></div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }

    public function getPtdlAll($_month) {
        $i = 1;
        $report = Report::where([
            ['ngayReport','like', '%'.\HelpFunction::revertMonth($_month)],
            ['type','like', 'ptdl'],
            ['clock','=', true]
        ])->get();
        if ($report !== null) {
            echo "<h5>Báo cáo tháng: <span class='text-red'><strong>".\HelpFunction::revertMonth($_month)."</strong></span></h5>
                    <h4>CÔNG VIỆC</h4>
                    <div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày</th>
                                        <th>Tên công việc</th>
                                        <th>Tiến độ</th>
                                        <th>Deadline</th>
                                        <th>Kết quả</th>
                                        <th>Ghi chú</th>
                                    </tr>";
            foreach($report as $row){
                foreach ($row->reportWork as $row2) {
                    $color = "";
                    if ($row->tienDo < 100)
                        $color = "class='text-red text-bold'";
                    echo "<tr>
                                        <td>".$i++."</td>
                                        <td>".$row->ngayReport."</td>
                                        <td>".$row2->tenCongViec."</td>
                                        <td $color>".$row2->tienDo."%</td>
                                        <td>".\HelpFunction::revertDate($row2->deadLine)."</td>
                                        <td>".$row2->ketQua."</td>
                                        <td>".$row2->ghiChu."</td>
                                    </tr>";
                }
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<br/><h4 class='center text-red'>Không có báo cáo!</h4>";
        }
    }
}
