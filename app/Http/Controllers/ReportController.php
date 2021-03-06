<?php

namespace App\Http\Controllers;

use App\Report;
use App\User;
use App\Nhom;
use App\NhomUser;
use App\ReportNhap;
use App\ReportWork;
use App\ReportCar;
use App\ReportXuat;
use App\TypeCar;
use App\Kpi;
use App\NhatKy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function showReport()
    {
        $typeCar = TypeCar::all();
        return view('report.report', ['typeCar' => $typeCar]);
    }

    public function khoiTao(Request $request)
    {
        $doanhSo = 0;
        $thiPhan = 0;
        $luotXeDV = 0;
        $doanhThuDV = 0;
        $month = Date('m-Y');
        $today = Date('d-m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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
            ['ngayReport', 'like', '%' . $month],
            ['type', 'like', $typeUser],
            ['doanhSoThang', '!=', null],
            ['thiPhanThang', '!=', null]
        ])->first();

        $checkDV = Report::where([
            ['ngayReport', 'like', '%' . $month],
            ['type', 'like', $typeUser],
            ['luotXeDV', '!=', null],
            ['doanhThuDV', '!=', null]
        ])->first();

        if ($checkSale !== null && $checkSale->count() > 0) {
            $doanhSo = $checkSale->doanhSoThang;
            $thiPhan = $checkSale->thiPhanThang;
        }

        if ($checkDV !== null && $checkDV->count() > 0) {
            $luotXeDV = $checkDV->luotXeDV;
            $doanhThuDV = $checkDV->doanhThuDV;
        }

        $checkIn = Report::where([
            ['ngayReport', 'like', $today],
            ['type', 'like', $typeUser]
        ])->exists();

        if ($request->doanhSoThang != null && $request->thiPhanThang != null) {
            $doanhSo = ($doanhSo != 0) ? $doanhSo : $request->doanhSoThang;
            $thiPhan = ($thiPhan != 0) ? $thiPhan : $request->thiPhanThang;
        }

        if ($request->luotXeDV != null && $request->doanhThuDV != null) {
            $luotXeDV = ($luotXeDV != 0) ? $luotXeDV : $request->luotXeDV;
            $doanhThuDV = ($doanhThuDV != 0) ? $doanhThuDV : $request->doanhThuDV;
        }


        if (!$checkIn) {
            $report = Report::insert([
                'type' => $typeUser,
                'user_report' => Auth::user()->id,
                'ngayReport' => Date('d-m-Y'),
                'doanhSoThang' => $doanhSo,
                'thiPhanThang' => $thiPhan,
                'luotXeDV' => $luotXeDV,
                'doanhThuDV' => $doanhThuDV,
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "T???o b??o c??o ng??y";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' Kh???i t???o b??o c??o!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! Li??n h??? qu???n tr??? vi??n!',
                    'code' => 500
                ]);
            }
        } else {
            $report = Report::where([
                ['ngayReport', 'like', $today],
                ['type', 'like', $typeUser]
            ])->update([
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "L??ub??o c??o!";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' ???? l??u!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! M??y ch???!',
                    'code' => 500
                ]);
            }
        }
    }

    public function loadReport()
    {
        $today = Date('d-m-Y');
        $month = Date('m-Y');
        $valIn = null;
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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
            ['ngayReport', 'like', $today],
            ['type', 'like', $typeUser]
        ])->exists();

        
        $inFirst = Kpi::where([
            ['thang', 'like', $month],
            ['type', 'like', $typeUser]
        ])->exists();

        if ($inFirst) {
            $valIn = Kpi::where([
                ['thang', 'like', $month],
                ['type', 'like', $typeUser]
            ])->first();
        }

        if ($checkIn) {
            $report = Report::where([
                ['ngayReport', 'like', $today],
                ['type', 'like', $typeUser]
            ])->first();

            return response()->json([
                'type' => 'info',
                'message' => ' T???i b??o c??o th??nh c??ng!',
                'val' => $valIn,
                'code' => 200,
                'data' => $report
            ]);
        } else {
            return response()->json([
                'pdv' => $valIn,
                'pkd' => $valIn,
                'type' => 'warning',
                'message' => ' Ch??a c?? b??o c??o n??o trong h??m nay!',
                'code' => 500
            ]);
        }
    }

    public function updateKpiKD(Request $request){
        $month = Date('m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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

        $inFirst = Kpi::where([
            ['thang', 'like', $month],
            ['type', 'like', $typeUser]
        ])->exists();

        if ($inFirst) {
            $valIn = Kpi::where([
                ['thang', 'like', $month],
                ['type', 'like', $typeUser]
            ])->update([
                'kpi1' => $request->ds,
                'kpiDecimal' => $request->tp
            ]);
            if ($valIn) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "C???p nh???t KPI kinh doanh";
                $nhatKy->save();
                return response()->json([
                    'message' => ' ???? c???p nh???t!',
                    'code' => 200
                ]);
            }
            else
                return response()->json([
                    'type' => 'warning',
                    'message' => ' Kh??ng th??? c???p nh???t t??? m??y ch???!',
                    'code' => 500
                ]);
        } else {
            $valIn = new Kpi;
            $valIn->thang = $month;
            $valIn->type = $typeUser;
            $valIn->kpi1 = $request->ds;
            $valIn->kpiDecimal = $request->tp;
            $valIn->save();

            if ($valIn) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "C???p nh???t KPI kinh doanh";
                $nhatKy->save();
                return response()->json([
                    'message' => ' ???? c???p nh???t!',
                    'code' => 200
                ]);
            }
            else
                return response()->json([
                    'type' => 'warning',
                    'message' => ' Kh??ng th??? c???p nh???t t??? m??y ch???!',
                    'code' => 500
                ]);
        }
    }

    public function updateKpiDV(Request $request){
        $month = Date('m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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

        $inFirst = Kpi::where([
            ['thang', 'like', $month],
            ['type', 'like', $typeUser]
        ])->exists();

        if ($inFirst) {
            $valIn = Kpi::where([
                ['thang', 'like', $month],
                ['type', 'like', $typeUser]
            ])->update([
                'kpi1' => $request->dt,
                'kpi2' => $request->lx
            ]);
            if ($valIn) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "C???p nh???t KPI d???ch v???";
                $nhatKy->save();
                return response()->json([
                    'message' => ' ???? c???p nh???t!',
                    'code' => 200
                ]);
            }
            else
                return response()->json([
                    'type' => 'warning',
                    'message' => ' Kh??ng th??? c???p nh???t t??? m??y ch???!',
                    'code' => 500
                ]);
        } else {
            $valIn = new Kpi;
            $valIn->thang = $month;
            $valIn->type = $typeUser;
            $valIn->kpi1 = $request->dt;
            $valIn->kpi2 = $request->lx;
            $valIn->save();

            if ($valIn) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "C???p nh???t KPI d???ch v???";
                $nhatKy->save();
                return response()->json([
                    'message' => ' ???? c???p nh???t!',
                    'code' => 200
                ]);
            }
            else
                return response()->json([
                    'type' => 'warning',
                    'message' => ' Kh??ng th??? c???p nh???t t??? m??y ch???!',
                    'code' => 500
                ]);
        }
    }

    public function saveReport(Request $request)
    {
        $month = Date('m-Y');
        $today = Date('d-m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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
            ['ngayReport', 'like', $today],
            ['type', 'like', $typeUser]
        ])->exists();

        if (!$checkIn) {
            $report = Report::insert([
                'type' => $typeUser,
                'user_report' => Auth::user()->id,
                'ngayReport' => Date('d-m-Y'),
                'timeReport' => Date('H:i:s'),
                'xuatNgoaiTinh' => $request->xuatNgoaiTinh,
                'xuatTrongTinh' => $request->xuatTrongTinh,
                'hdHuy' => $request->hdHuy,
                'hdDaiLy' => $request->hdDaiLy,
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
                // 'suaChua' => $request->suaChua,
                // 'Dong' => $request->dong,
                // 'Son' => $request->son,
                'congBaoDuong' => $request->congBaoDuong,
                'congSuaChuaChung' => $request->congSuaChuaChung,
                'congDong' => $request->congDong,
                'congSon' => $request->congSon,
                'pdi' => $request->pdi,
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "L??u v?? g???i b??o c??o (ch???t kh??a b??o c??o)";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' ???? l??u!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! Li??n h??? qu???n tr??? vi??n!',
                    'code' => 500
                ]);
            }
        } else {
            $report = Report::where([
                ['ngayReport', 'like', $today],
                ['type', 'like', $typeUser]
            ])->update([
                'xuatNgoaiTinh' => $request->xuatNgoaiTinh,
                'xuatTrongTinh' => $request->xuatTrongTinh,
                'hdHuy' => $request->hdHuy,
                'hdDaiLy' => $request->hdDaiLy,
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
                // 'suaChua' => $request->suaChua,
                // 'Dong' => $request->dong,
                // 'Son' => $request->son,
                'congBaoDuong' => $request->congBaoDuong,
                'congSuaChuaChung' => $request->congSuaChuaChung,
                'congDong' => $request->congDong,
                'congSon' => $request->congSon,
                'pdi' => $request->pdi,
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "L??u v?? g???i b??o c??o (ch???t kh??a b??o c??o)";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' ???? l??u!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! M??y ch???!',
                    'code' => 500
                ]);
            }
        }
    }

    public function saveNotSend(Request $request)
    {
        $month = Date('m-Y');
        $today = Date('d-m-Y');
        $typeUser = "";
        if (Auth::user()->hasRole('tpkd'))
            $typeUser = "pkd";
        elseif (Auth::user()->hasRole('tpdv'))
            $typeUser = "pdv";
        elseif (Auth::user()->hasRole('ketoan'))
            $typeUser = "ketoan";
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
            ['ngayReport', 'like', $today],
            ['type', 'like', $typeUser]
        ])->exists();

        if (!$checkIn) {
            $report = Report::insert([
                'type' => $typeUser,
                'user_report' => Auth::user()->id,
                'ngayReport' => Date('d-m-Y'),
                'timeReport' => Date('H:i:s'),
                'xuatHoaDon' => $request->xuatHoaDon,
                'xuatNgoaiTinh' => $request->xuatNgoaiTinh,
                'xuatTrongTinh' => $request->xuatTrongTinh,
                'hdHuy' => $request->hdHuy,
                'hdDaiLy' => $request->hdDaiLy,
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
                // 'suaChua' => $request->suaChua,
                // 'Dong' => $request->dong,
                // 'Son' => $request->son,
                'congBaoDuong' => $request->congBaoDuong,
                'congSuaChuaChung' => $request->congSuaChuaChung,
                'congDong' => $request->congDong,
                'congSon' => $request->congSon,
                'pdi' => $request->pdi,
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->noiDung = "L??u b??o c??o (ch??a g???i, ch??a ch???t b??o c??o)";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' ???? l??u!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! Li??n h??? qu???n tr??? vi??n!',
                    'code' => 500
                ]);
            }
        } else {
            $report = Report::where([
                ['ngayReport', 'like', $today],
                ['type', 'like', $typeUser]
            ])->update([
                'xuatHoaDon' => $request->xuatHoaDon,
                'xuatNgoaiTinh' => $request->xuatNgoaiTinh,
                'xuatTrongTinh' => $request->xuatTrongTinh,
                'hdHuy' => $request->hdHuy,
                'hdDaiLy' => $request->hdDaiLy,
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
                // 'suaChua' => $request->suaChua,
                // 'Dong' => $request->dong,
                // 'Son' => $request->son,
                'congBaoDuong' => $request->congBaoDuong,
                'congSuaChuaChung' => $request->congSuaChuaChung,
                'congDong' => $request->congDong,
                'congSon' => $request->congSon,
                'pdi' => $request->pdi,
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
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
                $nhatKy->noiDung = "L??u b??o c??o (ch??a g???i, ch??a ch???t b??o c??o)";
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => ' ???? l??u!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => ' L???i! M??y ch???!',
                    'code' => 500
                ]);
            }
        }
    }

    public function addCar(Request $request)
    {
        $reportCar = new ReportCar();
        $reportCar->ngayTao = Date('d-m-Y');
        $reportCar->id_report = $request->idReport;
        $reportCar->soLuong = $request->soLuong;
        $reportCar->dongXe = $request->typeCar;
        $reportCar->save();
        if ($reportCar) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
            $nhatKy->noiDung = "B??o c??o th??ng tin xe h???p ?????ng";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => " ???? th??m chi ti???t h???p ?????ng",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Kh??ng th??? th??m chi ti???t h???p ?????ng",
                'code' => 500
            ]);
        }
    }

    public function loadAddCar($id)
    {
        $reportCar = ReportCar::where('id_report', $id)->get();
        $checkExist = ReportCar::where('id_report', $id)->exists();
        if ($checkExist)
            $checkClock = $reportCar->first()->report->clock;
        echo "<table class='table table-striped'><tr>
               <th>D??ng xe</th>";
        foreach ($reportCar as $row)
            echo "<th>" . $row->typeCar->name . "</th>";
        echo "</tr><tr><td><strong>S??? l?????ng</strong></td>";
        foreach ($reportCar as $row) {
            if (!$checkClock)
                echo "<td>" . $row->soLuong . " <button type='button' id='delCar' data-id='" . $row->id . "' class='badge badge-danger'>X??a</button></td>";
            else
                echo "<td>" . $row->soLuong . "</td>";
        }
        echo "</tr></table>";
    }

    public function deleteCar(Request $request)
    {
        $reportCar = ReportCar::where('id', $request->id)->delete();
        if ($reportCar) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
            $nhatKy->noiDung = "X??a b??o c??o th??ng tin xe h???p ?????ng";
            $nhatKy->save();
            return response()->json([
                'message' => '???? x??a!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function addWork(Request $request)
    {
        $reportWork = new ReportWork();
        $reportWork->user_tao = Auth::user()->id;
        $reportWork->ngayTao = Date('d-m-Y');
        $reportWork->tenCongViec = $request->tenCongViec;
        $reportWork->tienDo = $request->tienDo;
        $reportWork->ngayStart = $request->ngayStart;
        $reportWork->ngayEnd = $request->ngayEnd;
        $reportWork->ghiChu = $request->ghiChu;
        $reportWork->ketQua = $request->ketQua;
        $reportWork->save();
        if ($reportWork) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
            $nhatKy->noiDung = "Th??m c??ng vi???c trong ng??y";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => " ???? th??m c??ng vi???c",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Kh??ng th??? th??m c??ng vi???c",
                'code' => 500
            ]);
        }
    }

    public function loadWork()
    {
        $i = 1;
        $reportWork = ReportWork::where([
            ['ngayTao', 'like', Date('d-m-Y')],
            ['user_tao', '=', Auth::user()->id]
        ])->get();
        echo "<table class='table table-striped'>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ng??y</th>
                                        <th>T??n c??ng vi???c</th>
                                        <th>Ti???n ?????</th>
                                        <th>Ng??y b???t ?????u</th>
                                        <th>Ng??y k???t th??c</th>
                                        <th>Ghi ch??</th>
                                        <th>H??nh ?????ng</th>
                                    </tr>";
        foreach ($reportWork as $row) {
            echo "<tr>
                                        <td>" . $i++ . "</td>
                                        <td>" . $row->ngayTao . "</td>
                                        <td>" . $row->tenCongViec . "</td>
                                        <td>" . $row->tienDo . "%</td>
                                        <td>" . \HelpFunction::revertDate($row->ngayStart) . "</td>
                                        <td>" . \HelpFunction::revertDate($row->ngayEnd) . "</td>
                                        <td>" . $row->ghiChu . "</td>
                                        <td>
                                            <button id='delWork' data-id='" . $row->id . "' type='button' class='btn btn-danger btn-sm'>X??a</button>
                                        </td>
                                    </tr>";
        }
        echo "</table>";
    }

    public function deleteWork(Request $request)
    {
        $reportWork = ReportWork::where('id', $request->id)->delete();
        if ($reportWork) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "B??o c??o - B??o c??o ng??y";
            $nhatKy->noiDung = "X??a c??ng vi???c trong ng??y";
            $nhatKy->save();
            return response()->json([
                'message' => '???? x??a!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function overviewList()
    {
        return view('report.overview');
    }

    public function overviewWorkList()
    {
        return view('report.reportwork');
    }

    public function getWorkList() {
        if (Auth::user()->hasRole('system') 
        || Auth::user()->hasRole('boss') 
        || Auth::user()->hasRole('watch')) {
            $nhom = Nhom::all();
            foreach($nhom as $row) {
                $nhomUser = NhomUser::select("*")->where("id_nhom",$row->id)->get();
                foreach($nhomUser as $item){
                    $stt = ($item->leader) ? "[Qu???n l??]" : "";
                    $user = User::find($item->id_user);
                    if ($user->hasRole('report') && $user->hasNhom($row->name)) {                            
                        echo "<option value='".$user->id."'>[".$row->name."] ".$user->userDetail->surname." $stt</option>";
                    }    
                }
            }    
            // $user = User::all();
            // foreach($user as $row) {
            //     if ($row->hasRole('report'))
            //         echo "<option value='".$row->id."'>".$row->userDetail->surname."</option>";
            // }            
        }
        else {
            $nhom = NhomUser::select("*")->where("id_user",Auth::user()->id)->get();
            foreach($nhom as $row) {
                $tenNhom = Nhom::select("*")->where("id",$row->id_nhom)->first();  
               if ($row->leader == true) {                                                      
                    $user = User::all();
                    foreach($user as $item) {
                        $stt = ($item->id == $row->id_user) ? "[Qu???n l??]" : "";
                        if ($item->hasRole('report') && $item->hasNhom($tenNhom->name)) {                            
                            echo "<option value='".$item->id."'>".$item->userDetail->surname." [".$tenNhom->name."] $stt</option>";
                        }
                    }                         
               } else {
                    $user = User::find($row->id_user);
                    if ($user->hasRole('report'))
                        echo "<option value='".$user->id."'>".$user->userDetail->surname." [".$tenNhom->name."]</option>";
               }
            }   
        }
    }

    public function getPKDAll($_from, $_to)
    {
        $i = 1;
        $sum = 0;
        $doanhSoThang = 0;
        $thiPhanThang = 0;
        $xuatHoaDon = 0;
        $xuatNgoaiTinh = 0;
        $xuatTrongTinh = 0;
        $hdHuy = 0;
        $hdDaiLy = 0;
        $ctInternet = 0;
        $ctShowroom = 0;
        $ctHotline = 0;
        $ctSuKien = 0;
        $ctBLD = 0;
        $saleInternet = 0;
        $saleMoiGioi = 0;
        $saleThiTruong = 0;
        $khShowRoom = 0;
        $kyHopDong = 0;

        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);

        

        // --- only for pkd
        $doanhSoThang = 0;
        $thiPhanThang = 0;
        $thang = \HelpFunction::getMonthInDay($_from);
        $ds = Kpi::where([
            ['type', 'like', 'pkd'],
            ['thang', 'like', $thang]
        ])->first();

        if ($ds !== null) {
            $doanhSoThang = $ds->kpi1;
            $thiPhanThang = $ds->kpiDecimal;
        } else {
            $doanhSoThang = 1;
            $thiPhanThang = 1;
        }
        //-------------------

        $report = Report::where([
            ['type', 'like', 'pkd'],
            ['clock', '=', true]
        ])->get();

        // $report = Report::where([
        //     ['type', 'like', 'pkd'],
        //     ['clock', '=', true]
        // ])->whereBetween('ngayReport', [$_from, $_to])->get();

        // $personal = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', true],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $getWork = ReportWork::where([
        //     ['user_nhan', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $pushWork = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReportPush', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        if ($report !== null) {
            foreach ($report as $row) {
                if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
                    $xuatHoaDon += $row->xuatHoaDon;
                    $xuatNgoaiTinh += $row->xuatNgoaiTinh;
                    $xuatTrongTinh += $row->xuatTrongTinh;
                    $hdHuy += $row->hdHuy;
                    $hdDaiLy += $row->hdDaiLy;
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
            }
            $arrIndex = [];
            $arrName = [];
            $arrValue = [];
            $list = 0;
            $carList = TypeCar::all();
               foreach($carList as $row) {
                    $arrName[$list] = $row->name;
                    $arrIndex[$list] = $row->id;
                    $arrValue[$list] = 0;
                    $list++;
               }
            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>
            <h5>Doanh s??? (ch??? ti??u th??ng <strong>" . $thang . "</strong>): <span class='text-blue'><strong>" . (($doanhSoThang == 1) ? "0" : $doanhSoThang) . " (" . number_format((($xuatNgoaiTinh + $xuatTrongTinh) * 100 / $doanhSoThang), 2) . "%)</strong></span></h5>
                    <h5>Th??? ph???n th??ng (ch??? ti??u th??ng <strong>" . $thang . "</strong>): <span class='text-blue'><strong>" . number_format((($thiPhanThang == 1) ? "0" : $thiPhanThang),2) . "</strong></span></h5><br>
                    <div class='row'>
                        <div class='col-md-8'>
                            <h5>- Xu???t xe: <span class='text-success'><strong>" . ($xuatNgoaiTinh + $xuatTrongTinh) . "</strong></span></h5>
                            <p>+ Xu???t ngo??i t???nh: <span class='text-success'><strong>" . $xuatNgoaiTinh . "</strong></span><br/>
                            + Xu???t trong t???nh: <span class='text-success'><strong>" . $xuatTrongTinh . "</strong></span></p>
                            ";
            foreach ($report as $row) {
                        if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
                            foreach ($row->reportCar as $item) {
                                for ($i = 0; $i < count($arrIndex); $i++) { 
                                    if ($arrIndex[$i] == $item->dongXe) {
                                        $kyHopDong += $item->soLuong;
                                        $arrValue[$i] += $item->soLuong;
                                    }
                                    continue;
                                }
                            }
                        }
            }
            echo "<h5>- K?? h???p ?????ng: <span class='text-success'><strong>".$kyHopDong."</strong></span></h5><p>";
            for ($i = 0; $i < count($arrIndex); $i++) { 
                echo "+ " . $arrName[$i] . ": <span class='text-success'><strong>" . $arrValue[$i] . "</strong></span><br/>";
            }
            echo "</p>";
            echo "<h5>- H???p ?????ng ?????i l??: <span class='text-success'><strong>" . $hdDaiLy . "</strong></span></h5>
            <h5>- H???y h???p ?????ng: <span class='text-success'><strong>" . $hdHuy . "</strong></span></h5>
                        </div>
                        <div class='col-md-4'>
                            <h4>KHTN C??NG TY: <span class='text-success'><strong>" . ($ctInternet+$ctHotline+$ctSuKien) . "</strong></span></h4>
                            <h5>- Marketing - Internet: <span class='text-success'><strong>" . $ctInternet . "</strong></span></h5>                            
                            <h5>- Hotline & CSKH: <span class='text-success'><strong>" . $ctHotline . "</strong></span></h5>
                            <h5>- S??? ki???n: <span class='text-success'><strong>" . $ctSuKien . "</strong></span></h5>
                            <h4>KHTN SALER: <span class='text-success'><strong>" . $saleInternet . "</strong></span></h4>                          
                        </div>
                    </div>
                    <h5>L?????t kh??ch showroom: <span class='text-success'><strong>" . $khShowRoom . "</strong></span></h5>
                    <br>";
        }
    }

    public function getPDVAll($_from, $_to)
    {
        $i = 1;
        // $baoDuong = 0;
        // $suaChua = 0;
        // $Dong = 0;
        // $Son = 0;
        $luotXe = 0;

        $congBaoDuong = 0;
        $congSuaChuaChung = 0;
        $congDong = 0;
        $congSon = 0;
        $pdi = 0;

        $dtPhuTung = 0;
        $dtDauNhot = 0;
        $dtPhuTungBan = 0;
        $dtDauNhotBan = 0;
        $phuTungMua = 0;
        $dauNhotMua = 0;

        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);
        $thang = \HelpFunction::getMonthInDay($_from);
        // --- only for pdv
        $doanhThuDV = 0;
        $luotXeDV = 0;
        $dv = Kpi::where([
            ['type', 'like', 'pdv'],
            ['thang', 'like', $thang]
        ])->first();

        if ($dv !== null) {
            $doanhThuDV = $dv->kpi1;
            $luotXeDV = $dv->kpi2;
        } else {
            $doanhThuDV = 1;
            $luotXeDV = 1;
        }
        //-------------------
        $report = Report::where([
            ['type', 'like', 'pdv'],
            ['clock', '=', true]
        ])->get();

        // $personal = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', true],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $getWork = ReportWork::where([
        //     ['user_nhan', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $pushWork = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReportPush', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        if ($report !== null) {
            foreach ($report as $row) {
                if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
                    // $baoDuong += $row->baoDuong;
                    // $suaChua += $row->suaChua;
                    // $Dong += $row->Dong;
                    // $Son += $row->Son;

                    $luotXe += $row->baoDuong;

                    $congBaoDuong += $row->congBaoDuong;
                    $congSuaChuaChung += $row->congSuaChuaChung;
                    $congDong += $row->congDong;
                    $congSon += $row->congSon;
                    $pdi += $row->pdi;

                    $dtPhuTung += $row->dtPhuTung;
                    $dtDauNhot += $row->dtDauNhot;
                    $dtPhuTungBan += $row->dtPhuTungBan;
                    $dtDauNhotBan += $row->dtDauNhotBan;

                    $phuTungMua += $row->phuTungMua;
                    $dauNhotMua += $row->dauNhotMua;
                }  
            }

            // $sum1 = $baoDuong + $suaChua + $Dong + $Son;
            $sum1 = $luotXe;
            $sum2 = $congBaoDuong + $congSuaChuaChung + $congDong + $congSon + $pdi;
            $sum3 = $dtPhuTung + $dtDauNhot + $dtPhuTungBan + $dtDauNhotBan;
            $sum4 = $phuTungMua + $dauNhotMua;

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>


            <h5>L?????t xe (ch??? ti??u th??ng <strong>" . $thang . "</strong>): <span class='text-blue'><strong>" . (($luotXeDV == 1) ? "0" : $luotXeDV) . " (Th???c hi???n: ".$luotXe. " ?????t " . number_format((($luotXe) * 100 / $luotXeDV), 2) . "%)</strong></span></h5>
                    <h5>Doanh thu (ch??? ti??u th??ng <strong>" . $thang . "</strong>): <span class='text-blue'><strong>" . number_format(($doanhThuDV == 1) ? "0" : $doanhThuDV) . " (Th???c hi???n: ".number_format($dtPhuTung + $dtDauNhot + $dtPhuTungBan + $dtDauNhotBan + $congBaoDuong + $congSuaChuaChung + $congDong + $congSon + $pdi). " ?????t " . number_format((($dtPhuTung + $dtDauNhot + $dtPhuTungBan + $dtDauNhotBan + $congBaoDuong + $congSuaChuaChung + $congDong + $congSon + $pdi) * 100 / $doanhThuDV), 2) . "%)</strong></span></h5><br>

                <div class='row'>
                        <div class='col-md-6'>
                            <h4>L?????T XE: <span class='text-success'><strong>" . number_format($sum1) . "</strong></span></h4>
                        </div>
                        <div class='col-md-6'>
                            <h4>DOANH THU D???CH V???: <span class='text-success'><strong>" . number_format($sum2) . "</strong></span></h4>
                            <h5>- C??ng b???o d?????ng: <span class='text-success'><strong>" . number_format($congBaoDuong) . "</strong></span></h5>
                            <h5>- C??ng s???a ch???a: <span class='text-success'><strong>" . number_format($congSuaChuaChung) . "</strong></span></h5>
                            <h5>- C??ng ?????ng: <span class='text-success'><strong>" . number_format($congDong) . "</strong></span></h5>
                            <h5>- C??ng s??n: <span class='text-success'><strong>" . number_format($congSon) . "</strong></span></h5>
                            <h5>- C??ng PDI: <span class='text-success'><strong>" . number_format($pdi) . "</strong></span></h5>
                        </div>
                        </div>
                        <div class='row'>
                        <div class='col-md-6'>
                            <h4>DOANH THU PH??? T??NG - D???U NH???T: <span class='text-success'><strong>" . number_format($sum3) . "</strong></span></h4>
                            <h5>- Ph??? t??ng s???a ch???a: <span class='text-success'><strong>" . number_format($dtPhuTung) . "</strong></span></h5>
                            <h5>- D???u nh???t s???a ch???a: <span class='text-success'><strong>" . number_format($dtDauNhot) . "</strong></span></h5>
                            <h5>- Ph??? t??ng b??n ngo??i: <span class='text-success'><strong>" . number_format($dtPhuTungBan) . "</strong></span></h5>
                            <h5>- D???u nh???t b??n ngo??i: <span class='text-success'><strong>" . number_format($dtDauNhotBan) . "</strong></span></h5>
                        </div>
                        <div class='col-md-6'>
                            <h4>MUA PH??? T??NG/D???U NH???T HTV/TST: <span class='text-success'><strong>" . number_format($sum4) . "</strong></span></h4>
                            <h5>- Ti???n mua ph??? t??ng: <span class='text-success'><strong>" . number_format($phuTungMua) . "</strong></span></h5>
                            <h5>- Ti???n mua d???u nh???t: <span class='text-success'><strong>" . number_format($dauNhotMua) . "</strong></span></h5>
                        </div>
                    </div>
                    <br>";
                    
                    echo "<hr/>";
                    echo "<h4>CHI TI???T THEO NG??Y</h4>";
                    echo "<table class='table table-bordered'>
                       <thead>
                         <tr>
                           <th>Ng??y b??o c??o</th>
                           <th>L?????t Xe</th>
                           <th>C??ng BD</th>
                           <th>C??ng SCC</th>
                           <th>C??ng ?????ng</th>
                           <th>C??ng S??n</th>
                           <th>C??ng PDI</th>
                           <th>Ph??? t??ng SC</th>
                           <th>D???u nh???t SC</th>
                           <th>Ph??? t??ng b??n</th>
                           <th>D???u nh??t b??n</th>
                           <th>Ti???n mua PT</th>
                           <th>Ti???n mua DN</th>
                         </tr>
                       </thead>
                       <tbody>";

                    // $dbd = 0;
                    // $dsc = 0;
                    // $ddong = 0;
                    // $dson = 0;
                    $dluotXe = 0;


                    $dcbd = 0;
                    $dcsc = 0;
                    $dcdong = 0;
                    $dcson = 0;
                    $dpdi = 0;

                    $dpt = 0;
                    $ddn = 0;
                    $dptb = 0;
                    $ddnb = 0;

                    $dptm = 0;
                    $ddnm = 0;
                    foreach ($report as $row) {
                        if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
                            // $dbd += $row->baoDuong;
                            // $dsc += $row->suaChua;
                            // $ddong += $row->Dong;
                            // $dson += $row->Son;
                            $dluotXe += $row->baoDuong;

                            $dcbd += $row->congBaoDuong;
                            $dcsc += $row->congSuaChuaChung;
                            $dcdong += $row->congDong;
                            $dcson += $row->congSon;
                            $dpdi += $row->pdi;

                            $dpt += $row->dtPhuTung;
                            $ddn += $row->dtDauNhot;
                            $dptb += $row->dtPhuTungBan;
                            $ddnb += $row->dtDauNhotBan;

                            $dptm += $row->phuTungMua;
                            $ddnm += $row->dauNhotMua;
                            echo "<tr>
                                <td>".$row->ngayReport."</td>
                                <td>".$row->baoDuong."</td>

                                <td>".number_format($row->congBaoDuong)."</td>
                                <td>".number_format($row->congSuaChuaChung)."</td>
                                <td>".number_format($row->congDong)."</td>
                                <td>".number_format($row->congSon)."</td>
                                <td>".number_format($row->pdi)."</td>

                                <td>".number_format($row->dtPhuTung)."</td>
                                <td>".number_format($row->dtDauNhot)."</td>
                                <td>".number_format($row->dtPhuTungBan)."</td>
                                <td>".number_format($row->dtDauNhotBan)."</td>

                                <td>".number_format($row->phuTungMua)."</td>
                                <td>".number_format($row->dauNhotMua)."</td>
                            </tr>";
                        }
                    }
                    echo "<tr>
                            <td></td>
                            <td>".$dluotXe."</td>

                            <td>".number_format($dcbd)."</td>
                            <td>".number_format($dcsc)."</td>
                            <td>".number_format($dcdong)."</td>
                            <td>".number_format($dcson)."</td>
                            <td>".number_format($dpdi)."</td>

                            <td>".number_format($dpt)."</td>
                            <td>".number_format($ddn)."</td>
                            <td>".number_format($dptb)."</td>
                            <td>".number_format($ddnb)."</td>

                            <td>".number_format($dptm)."</td>
                            <td>".number_format($ddnm)."</td>
                        </tr>";
            echo "</tbody>
            </table>";
        }
    }

    public function getXuongAll($_from, $_to)
    {
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

        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);

        $report = Report::where([
            ['type', 'like', 'xuong'],
            ['clock', '=', true]
        ])->get();

        // $personal = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', true],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $getWork = ReportWork::where([
        //     ['user_nhan', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $pushWork = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReportPush', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();


        if ($report !== null) {
            foreach ($report as $row) {
                if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
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
            }

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>
                <div class='row'>
                    <div class='col-md-4'>
                        <h4>XE T???N</h4>
                        <h5>- B???o d?????ng: <span class='text-success'><strong>" . $tonBaoDuong . "</strong></span></h5>
                        <h5>- S???a ch???a chung: <span class='text-success'><strong>" . $tonSuaChuaChung . "</strong></span></h5>
                        <h5>- ?????ng: <span class='text-success'><strong>" . $tonDong . "</strong></span></h5>
                        <h5>- S??n: <span class='text-success'><strong>" . $tonSon . "</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>L?????T XE TI???P NH???N</h4>
                        <h5>- B???o d?????ng: <span class='text-success'><strong>" . $tiepNhanBaoDuong . "</strong></span></h5>
                        <h5>- S???a ch???a chung: <span class='text-success'><strong>" . $tiepNhanSuaChuaChung . "</strong></span></h5>
                        <h5>- ?????ng: <span class='text-success'><strong>" . $tiepNhanDong . "</strong></span></h5>
                        <h5>- S??n: <span class='text-success'><strong>" . $tiepNhanSon . "</strong></span></h5>
                    </div>
                    <div class='col-md-4'>
                        <h4>HO??N TH??NH</h4>
                        <h5>- B???o d?????ng: <span class='text-success'><strong>" . $hoanThanhBaoDuong . "</strong></span></h5>
                        <h5>- S???a ch???a chung: <span class='text-success'><strong>" . $hoanThanhSuaChuaChung . "</strong></span></h5>
                        <h5>- ?????ng: <span class='text-success'><strong>" . $hoanThanhDong . "</strong></span></h5>
                        <h5>- S??n: <span class='text-success'><strong>" . $hoanThanhSon . "</strong></span></h5>
                    </div>
                </div>";
        }
    }

    public function getCSKHAll($_from, $_to)
    {
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

        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);

        $report = Report::where([
            ['type', 'like', 'cskh'],
            ['clock', '=', true]
        ])->get();

        // $personal = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', true],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $getWork = ReportWork::where([
        //     ['user_nhan', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $pushWork = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReportPush', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        if ($report !== null) {
            foreach ($report as $row) {
                if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
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
            }

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>
                <div class='row'>
                    <div class='col-md-6'>
                        <h4>NH???C B???O D?????NG / ?????T H???N</h4>
                        <h5>- Cu???c g???i th??nh c??ng: <span class='text-success'><strong>" . $callDatHenSuccess . "</strong></span></h5>
                        <h5>- Cu???c g???i kh??ng th??nh c??ng: <span class='text-success'><strong>" . $callDatHenFail . "</strong></span></h5>
                        <h5>- ?????t h???n: <span class='text-success'><strong>" . $datHen . "</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>THEO D??I SAU D???CH V???</h4>
                        <h5>- Kh??ch h??ng h??i l??ng: <span class='text-success'><strong>" . $dvHaiLong . "</strong></span></h5>
                        <h5>- Kh??ch h??ng kh??ng h??i l??ng: <span class='text-success'><strong>" . $dvKhongHaiLong . "</strong></span></h5>
                        <h5>- Cu???c g???i kh??ng th??nh c??ng: <span class='text-success'><strong>" . $dvKhongThanhCong . "</strong></span></h5>
                    </div>
                </div>
                 <div class='row'>
                    <div class='col-md-6'>
                        <h4>THEO D??I SAU MUA XE</h4>
                        <h5>- Cu???c g???i th??nh c??ng: <span class='text-success'><strong>" . $muaXeSuccess . "</strong></span></h5>
                        <h5>- Cu???c g???i kh??ng th??nh c??ng: <span class='text-success'><strong>" . $muaXeFail . "</strong></span></h5>
                        <h5>- Ki???m ch???ng b??n l???: <span class='text-success'><strong>" . $duyetBanLe . "</strong></span></h5>
                    </div>
                    <div class='col-md-6'>
                        <h4>KHI???U N???I</h4>
                        <h5>- Th??i ????? nh??n vi??n: <span class='text-success'><strong>" . $knThaiDo . "</strong></span></h5>
                        <h5>- Ch???t l?????ng s???a ch???a: <span class='text-success'><strong>" . $knChatLuong . "</strong></span></h5>
                        <h5>- Th???i gian s???a ch???a: <span class='text-success'><strong>" . $knThoiGian . "</strong></span></h5>
                        <h5>- V??? sinh: <span class='text-success'><strong>" . $knVeSinh . "</strong></span></h5>
                        <h5>- Gi?? c???: <span class='text-success'><strong>" . $knGiaCa . "</strong></span></h5>
                        <h5>- H???u m??i - khuy???n m??i: <span class='text-success'><strong>" . $knKhuyenMai . "</strong></span></h5>
                        <h5>- ?????t h???n - ti???p nh???n: <span class='text-success'><strong>" . $knDatHen . "</strong></span></h5>
                        <h5>- Tr???i nghi???m kh??ch h??ng: <span class='text-success'><strong>" . $knTraiNghiem . "</strong></span></h5>
                    </div>
                </div>";
        }
    }

    public function getMktAll($_from, $_to)
    {
        $i = 1;
        $khBanGiao = 0;
        $khSuKien = 0;

        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);

        $report = Report::where([
            ['type', 'like', 'mkt'],
            ['clock', '=', true]
        ])->get();

        // $personal = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', true],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $getWork = ReportWork::where([
        //     ['user_nhan', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReport', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        // $pushWork = ReportWork::where([
        //     ['user_tao', '=', Auth::user()->id],
        //     ['isPersonal', '=', false],
        //     ['isReportPush', '=', true]
        // ])->whereBetween('ngayTao', [$_from, $_to])->get();

        if ($report !== null) {

            foreach ($report as $row) {
                if ((strtotime($row->ngayReport) >= strtotime($_from)) 
                &&  (strtotime($row->ngayReport) <= strtotime($_to))) {
                    $khBanGiao += $row->khBanGiao;
                    $khSuKien += $row->khSuKien;
                }
            }

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>
                    <div>
                        <h5>- KHTN b??n giao: <span class='text-success'><strong>" . $khBanGiao . "</strong></span></h5>
                        <h5>- KHTN s??? ki???n: <span class='text-success'><strong>" . $khSuKien . "</strong></span></h5>
                    </div>";
        }
    }


    public function status()
    {
        if (Auth::user()->hasRole('system') 
        || Auth::user()->hasRole('boss') || Auth::user()->hasRole('watch')) {
            $_date = Date('d-m-Y');
            // $arr = ['pkd', 'pdv', 'mkt', 'xuong', 'cskh'];
            $arr = ['pkd', 'pdv', 'mkt', 'cskh'];
            echo "<h3>B??O C??O S??? LI???U</h3><table class='table table-striped table-border'>
                                <tr>
                                    <th>Th???i gian</th>
                                    <th>Ph??ng ban</th>
                                    <th>Tr???ng th??i</th>
                                </tr>";
            for ($i = 0; $i < count($arr); $i++) {
                $phong = "";
                $check = Report::where([
                    ['ngayReport', 'like', $_date],
                    ['type', 'like', $arr[$i]],
                    ['clock', '=', true]
                ])->exists();
                switch ($arr[$i]) {
                    case 'pkd':
                        $phong = "Ph??ng kinh doanh";
                        break;
                    case 'pdv':
                        $phong = "Ph??ng d???ch v???";
                        break;
                    case 'mkt':
                        $phong = "Marketing";
                        break;
                    // case 'xuong':
                    //     $phong = "X?????ng";
                    //     break;
                    case 'cskh':
                        $phong = "CSKH";
                        break;
                }
                if ($check) {
                    echo "<tr>
                        <td>" . $_date . "</td>
                        <td>" . $phong . "</td>
                        <td class='text-success'><strong>???? g???i b??o c??o</strong></td>
                    </tr>";
                } else {
                    echo "<tr>
                        <td>" . $_date . "</td>
                        <td>" . $phong . "</td>
                        <td class='text-danger'><strong>Ch??a g???i b??o c??o</strong></td>
                    </tr>";
                }

            }
            echo "</table>";
            echo "<div><div class='col-md-4'><form id='statusThang'>
                    <div class='form-group row'>
                        <div class='col-8'>
                            <input type='month' name='monthStatus' value='" . Date('Y-m') . "' class='form-control'>
                        </div>
                        <div class='col-2'>
                            <button type='button' id='watchMonthStatus' class='btn btn-success'>Xem th??ng</button>
                        </div>
                    </div>
                </form></div></div>";
        }
    }

    public function statusMonth($_month, $_room)
    {
        $phong = "";
        $month = explode('-', $_month)[1];
        $year = explode('-', $_month)[0];
        $sumDay = \HelpFunction::countDayInMonth($month, $year);
        switch ($_room) {
            case 'pkd':
                $phong = "Ph??ng kinh doanh";
                break;
            case 'pdv':
                $phong = "Ph??ng d???ch v???";
                break;
            case 'mkt':
                $phong = "Marketing";
                break;
            // case 'xuong':
            //     $phong = "X?????ng";
            //     break;
            case 'cskh':
                $phong = "CSKH";
                break;
        }
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('boss') || Auth::user()->hasRole('watch')) {
            echo "<table class='table table-striped table-border'>
                                <tr>
                                    <th>Th???i gian</th>
                                    <th>Ph??ng ban</th>
                                    <th>Tr???ng th??i</th>
                                </tr>";
            for ($i = 1; $i <= $sumDay; $i++) {
                $_date = str_pad($i . '-' . $month . '-' . $year, 10, "0", STR_PAD_LEFT);
                $check = Report::where([
                    ['ngayReport', 'like', $_date],
                    ['type', 'like', $_room],
                    ['clock', '=', true]
                ])->exists();
                if ($phong != "")
                    if ($check) {
                        echo "<tr>
                            <td>" . $i . "-" . $month . "-" . $year . "</td>
                            <td>" . $phong . "</td>
                            <td class='text-success'><strong>???? g???i b??o c??o</strong></td>
                        </tr>";
                    } else {
                        echo "<tr>
                            <td>" . $i . "-" . $month . "-" . $year . "</td>
                            <td>" . $phong . "</td>
                            <td class='text-danger'><strong>Ch??a g???i b??o c??o</strong></td>
                        </tr>";
                    }

            }
            echo "</table>";
            echo "<div><div class='col-md-4'><form id='statusThang'>
                    <div class='form-group row'>
                        <div class='col-8'>
                            <input type='month' name='monthStatus' value='" . Date('Y-m') . "' class='form-control'>
                        </div>
                        <div class='col-2'>
                            <button type='button' id='watchMonthStatus' class='btn btn-success'>Xem th??ng</button>
                        </div>
                    </div>
                </form></div></div>";
        }
    }

    public function getReportWorkAdmin($id, $_from, $_to, $check)
    {
        $i = 1;
        $_from = \HelpFunction::revertDate($_from);
        $_to = \HelpFunction::revertDate($_to);
        $check = ($check == "true") ? true : false;
        $check = ($check == "false") ? false : true;
        // $_user = Auth::user()->id;
        // if (Auth::user()->hasRole('system') || Auth::user()->hasRole('watch') || Auth::user()->hasRole('boss')) {
            $_user = $id;
        // }

        if (!$check) {
            $personal = ReportWork::where([
                ['user_tao', '=', $_user],
                ['isPersonal', '=', true]
            ])->get();

            $getWork = ReportWork::where([
                ['user_nhan', '=', $_user],
                ['isPersonal', '=', false]
            ])->get();

            $pushWork = ReportWork::where([
                ['user_tao', '=', $_user],
                ['isPersonal', '=', false]
            ])->get();

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>";

            echo "<h4>C??NG VI???C C?? NH??N</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-info'>
                        <th>STT</th>
                        <th>Ng??y BC</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                    </tr>";

            foreach ($personal as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->ngayTao . "</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                        </tr>
                    ";
                }                
            }
            echo "</table></div>";

            echo "<h4>C??NG VI???C ???????C GIAO</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-primary'>
                        <th>STT</th>
                        <th>Ng?????i giao<br/>(Ng??y t???o)</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>Y??u c???u</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                        <th>Tr???ng th??i</th>
                    </tr>";
            $i = 1;
            foreach ($getWork as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    if ($row->tienDo == 100 && $row->acceptApply == true)
                        $stt = "<td class='text-success'><strong>???? x??c nh???n</strong></td>";
                    elseif ($row->tienDo == 100 && $row->acceptApply == false)
                        $stt = "<td class='text-warning'><strong>?????i x??c nh???n</strong></td>";
                    elseif ($row->tienDo < 100)
                        $stt = "<td class='text-info'><strong>??ang th???c hi???n</strong></td>";
                    else $stt = "<td></td>";
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->userTao->userDetail->surname . "<br/>(" . $row->ngayTao . ")</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-orange'><i>" . $row->requestWork . "</i></td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                            $stt
                        </tr>
                    ";
                }
            }
            echo "</table></div>";

            echo "<h4>C??NG VI???C ???? GIAO</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-success'>
                        <th>STT</th>
                        <th>Ng?????i nh???n<br/>(Ng??y t???o)</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>Y??u c???u</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                        <th>Tr???ng th??i</th>
                    </tr>";
            $i = 1;
            foreach ($pushWork as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    if ($row->tienDo == 100 && $row->acceptApply == true)
                        $stt = "<td class='text-success'><strong>???? x??c nh???n</strong></td>";
                    elseif ($row->tienDo == 100 && $row->acceptApply == false)
                        $stt = "<td class='text-warning'><strong>?????i x??c nh???n</strong></td>";
                    elseif ($row->tienDo < 100 && $row->apply == true)
                        $stt = "<td class='text-info'><strong>??ang th???c hi???n</strong></td>";
                    elseif ($row->apply !== null && $row->apply == false)
                        $stt = "<td class='text-danger'><strong>???? t??? ch???i</strong></td>";
                    elseif ($row->apply === null)
                        $stt = "<td class='text-primary'><strong>Ch??a nh???n</strong></td>";
                    else $stt = "<td></td>";
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->userNhan->userDetail->surname . "<br/>(" . $row->ngayTao . ")</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-orange'><i>" . $row->requestWork . "</i></td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                            $stt
                        </tr>
                    ";
                }
            }
            echo "</table></div>";
        } else {
            $personal = ReportWork::where([
                ['user_tao', '=', $_user],
                ['isPersonal', '=', true],
                ['isReport','=', true]
            ])->get();

            $getWork = ReportWork::where([
                ['user_nhan', '=', $_user],
                ['isPersonal', '=', false],
                ['isReport','=', true]
            ])->get();

            $pushWork = ReportWork::where([
                ['user_tao', '=', $_user],
                ['isPersonal', '=', false],
                ['isReportPush','=', true]
            ])->get();

            echo "<h5>B??o c??o t???: <span class='text-red'><strong>" . $_from . "</strong> ?????n <strong>" . $_to . "</strong></span></h5>";

            echo "<h4>C??NG VI???C C?? NH??N</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-info'>
                        <th>STT</th>
                        <th>Ng??y BC</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                    </tr>";

            foreach ($personal as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->ngayTao . "</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                        </tr>
                    ";
                }
            }
            echo "</table></div>";

            echo "<h4>C??NG VI???C ???????C GIAO</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-primary'>
                        <th>STT</th>
                        <th>Ng?????i giao<br/>(Ng??y t???o)</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>Y??u c???u</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                        <th>Tr???ng th??i</th>
                    </tr>";
            $i = 1;
            foreach ($getWork as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    if ($row->tienDo == 100 && $row->acceptApply == true)
                        $stt = "<td class='text-success'><strong>???? x??c nh???n</strong></td>";
                    elseif ($row->tienDo == 100 && $row->acceptApply == false)
                        $stt = "<td class='text-warning'><strong>?????i x??c nh???n</strong></td>";
                    elseif ($row->tienDo < 100)
                        $stt = "<td class='text-info'><strong>??ang th???c hi???n</strong></td>";
                    else $stt = "<td></td>";
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->userTao->userDetail->surname . "<br/>(" . $row->ngayTao . ")</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-orange'><i>" . $row->requestWork . "</i></td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                            $stt
                        </tr>
                    ";
                }
            }
            echo "</table></div>";

            echo "<h4>C??NG VI???C ???? GIAO</h4>
            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <tr class='bg-success'>
                        <th>STT</th>
                        <th>Ng?????i nh???n<br/>(Ng??y t???o)</th>
                        <th>C??ng vi???c</th>
                        <th>Ti???n ?????</th>
                        <th>Deadline</th>
                        <th>Y??u c???u</th>
                        <th>K???t qu???</th>
                        <th>Ghi ch??</th>
                        <th>Tr???ng th??i</th>
                    </tr>";
            $i = 1;
            foreach ($pushWork as $row) {
                if ((strtotime($row->ngayTao) >= strtotime($_from)) 
                &&  (strtotime($row->ngayTao) <= strtotime($_to))) {
                    if ($row->tienDo == 100 && $row->acceptApply == true)
                        $stt = "<td class='text-success'><strong>???? x??c nh???n</strong></td>";
                    elseif ($row->tienDo == 100 && $row->acceptApply == false)
                        $stt = "<td class='text-warning'><strong>?????i x??c nh???n</strong></td>";
                    elseif ($row->tienDo < 100 && $row->apply == true)
                        $stt = "<td class='text-info'><strong>??ang th???c hi???n</strong></td>";
                    elseif ($row->apply !== null && $row->apply == false)
                        $stt = "<td class='text-danger'><strong>???? t??? ch???i</strong></td>";
                    elseif ($row->apply === null)
                        $stt = "<td class='text-primary'><strong>Ch??a nh???n</strong></td>";
                    else $stt = "<td></td>";
                    echo "
                        <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row->userNhan->userDetail->surname . "<br/>(" . $row->ngayTao . ")</td>
                            <td>" . $row->tenCongViec . "</td>
                            <td>" . (($row->tienDo == 100) ? "<strong class='text-info'>" . $row->tienDo . "%</strong>" : "<strong class='text-danger'>" . $row->tienDo . "%</strong>") . "</td>
                            <td class='text-bold' style='font-size:70%;'>"
                        . \HelpFunction::revertDate($row->ngayStart) . "<br/>
                                "
                        . \HelpFunction::revertDate($row->ngayEnd) . "
                            </td>
                            <td class='text-orange'><i>" . $row->requestWork . "</i></td>
                            <td class='text-info'><i>" . $row->ketQua . "</i></td>
                            <td class='text-info'><i>" . $row->ghiChu . "</i></td>
                            $stt
                        </tr>
                    ";
                }
            }
            echo "</table></div>";
        }
    }
}
