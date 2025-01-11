<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\GuestDv;
use App\BHPK;
use App\User;
use App\HopDong;
use App\KhoV2;
use App\NhatKy;
use App\ChiTietBHPK;
use App\BaoGiaBHPK;
use App\KTVBHPK;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class ExportBaoCaoDoanhThuToPhuKienController extends Controller implements FromCollection, WithHeadings
{
    use Exportable;
    private $from;
    private $to;
    private $baoCao;
    private $u;

    public function __construct($from,$to,$baoCao,$u){
        $this->from = $from;
        $this->to = $to;
        $this->baoCao = $baoCao;
        $this->u = $u;
    }

    public function collection()
    {
        $hds = [];
        $_from = \HelpFunction::revertDate($this->from);
        $_to = \HelpFunction::revertDate($this->to);
        $nv = $this->u;
        $chon = $this->baoCao;
        $checkFromTuOnlyMonth = false; // Kiểm tra chọn thời gian báo cáo có cùng 1 tháng hay không
        $monthSelect = "";
        $date1 = "";
        $date2 = "";
        if (\HelpFunction::getOnlyMonth($_from) != \HelpFunction::getOnlyMonth($_to)) {
            $checkFromTuOnlyMonth = false;
        } else {
            $checkFromTuOnlyMonth = true;
            $monthSelect = \HelpFunction::getOnlyMonth($_from); 
            // Xử lý tìm lương chưa tính tháng trước
            $yearSelect = \HelpFunction::getOnlyYearV2($this->from);
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
        $i = 1;
        switch($chon) { 
            case 3: {
                $tong_cong = 0;
                $tong_doanhthu_cong = 0;
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
                                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) >= strtotime($_from)) 
                                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) <= strtotime($_to))) {
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
                                        $hds[] = array(
                                            '0' => $i++,
                                            '1' => $ten,
                                            '2' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                            '3' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                                            '4' => $row->user->userDetail->surname,
                                            '5' => $_sale,
                                            '6' => $row->hoTen,
                                            '7' => ($row->saler ? "Báo giá kinh doanh" : "Báo giá khai thác"),
                                            '8' => $_congviec,
                                            '9' => $_doanhthu,
                                            '10' => $_cong,
                                            '11' => "",
                                            '12' => \HelpFunction::getDateRevertCreatedAt($k->updated_at),
                                            '13' => ($_ngayThu ? \HelpFunction::revertDate($_ngayThu) : ""),
                                        );
                                    }
                                }                                
                            }   
                        }
                    }                    
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
                                    $__congcongdon = 0;
                                    $__flag = false;
                                    foreach($_ct as $_item) {  
                                        if ($__congcongdon != 0)
                                            $__flag = true;

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
                                                    if ($tempYear == \HelpFunction::getOnlyYearV2($__ngayThu) && $tempYear == \HelpFunction::getOnlyYearV2($date1)) {
                                                        $_dateKeep = \HelpFunction::getOnlyDateFromCreatedAtKeepFormat($_ktv2->updated_at);
                                                        if (\HelpFunction::getOnlyMonth($_dateKeep) == $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) == $tempMonth) {
                                                            $tongCongDaTinh += $__cong;
                                                            $__congcongdon += $__cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($_dateKeep) < $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) == $tempMonth) {
                                                            $$tongCongDaTinh += $__cong;
                                                            $__congcongdon += $__cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($_dateKeep) == $tempMonth && \HelpFunction::getOnlyMonth($__ngayThu) < $tempMonth) {
                                                            $tongCongDaTinh += $__cong;
                                                            $__congcongdon += $__cong;
                                                        }       
                                                    }                                                                                         
                                                }
                                                // ---------------------------
                                            }
                                        }        
                                    }                                    
                                    $tongCongThangTruoc += ($__flag == true ? $__congcongdon : $__cong);
                                    $__flag = false;
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
                                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) >= strtotime($_from)) 
                                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($k->updated_at)) <= strtotime($_to))) {
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
                                    $flag = false;
                                    foreach($ct as $item) {
                                        if ($_congTinhLuong != 0)
                                            $flag = true;

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
                                                    if ($yearSelect == \HelpFunction::getOnlyYearV2($_ngayThu) && $yearSelect == \HelpFunction::getOnlyYearV2($this->from)) {
                                                        $dateKeep = \HelpFunction::getOnlyDateFromCreatedAtKeepFormat($ktv2->updated_at);
                                                        if (\HelpFunction::getOnlyMonth($dateKeep) == $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) == $monthSelect) {
                                                            $tongCongTinhLuong += $_cong;
                                                            $_congTinhLuong += $_cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($dateKeep) < $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) == $monthSelect) {
                                                            $tongCongTinhLuong += $_cong;
                                                            $_congTinhLuong += $_cong;
                                                        } elseif (\HelpFunction::getOnlyMonth($dateKeep) == $monthSelect && \HelpFunction::getOnlyMonth($_ngayThu) < $monthSelect) {
                                                            $tongCongTinhLuong += $_cong;
                                                            $_congTinhLuong += $_cong;
                                                        }             
                                                    }                                                                                        
                                                }
                                                // ---------------------------
                                            }
                                        }    
                                        
                                    }                                    
                                    $tong_cong += ($flag == true ? $_congTinhLuong : $_cong);
                                    $tong_doanhthu_cong += $_doanhthu;
                                    $hds[] = array(
                                        '0' => $i++,
                                        '1' => $ten,
                                        '2' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                        '3' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                                        '4' => $row->user->userDetail->surname,
                                        '5' => $_sale,
                                        '6' => $row->hoTen,
                                        '7' => ($row->saler ? "Báo giá kinh doanh" : "Báo giá khai thác"),
                                        '8' => $_congviec,
                                        '9' => $_doanhthu,
                                        '10' => ($flag == true ? $_congTinhLuong : $_cong),
                                        '11' => $_congTinhLuong,
                                        '12' => \HelpFunction::getDateRevertCreatedAt($k->updated_at),
                                        '13' => ($_ngayThu ? \HelpFunction::revertDate($_ngayThu) : ""),
                                    );

                                    $flag = false;
                                }
                            }                                
                        }   
                    }
                    if ($checkFromTuOnlyMonth) {
                        $hds[] = array(
                            '0' => "",
                            '1' => "",
                            '2' => "",
                            '3' => "",
                            '4' => "",
                            '5' => "",
                            '6' => "",
                            '7' => "",
                            '8' => "",
                            '9' => "",
                            '10' => "",
                            '11' => "",
                            '12' => "",
                            '13' => "",
                        );
                        $hds[] = array(
                            '0' => "Tổng doanh thu",
                            '1' => "",
                            '2' => "Tổng tiền công",
                            '3' => "",
                            '4' => "Tiền công tháng ".$monthSelect." năm  ".$yearSelect." được xác nhận",
                            '5' => "",
                            '6' => "Tiền công tháng ".($tempMonth > 9 ? $tempMonth : "0".$tempMonth)." năm ".$tempYear." chưa tính",
                            '7' => "",
                            '8' => "Tổng tiền công tháng ".$monthSelect." năm  ".$yearSelect." để tính lương",
                            '9' => "",
                            '10' => "Tiền công tháng ".$monthSelect." năm  ".$yearSelect." chưa tính",
                            '11' => "",
                            '12' => "",
                            '13' => "",
                        );
                        $hds[] = array(
                            '0' => $tong_doanhthu_cong,
                            '1' => "",
                            '2' => $tong_cong,
                            '3' => "",
                            '4' => $tongCongTinhLuong,
                            '5' => "",
                            '6' => $tongCongChuaTinh,
                            '7' => "",
                            '8' => $tongCongChuaTinh+$tongCongTinhLuong,
                            '9' => "",
                            '10' => $tong_cong-$tongCongTinhLuong,
                            '11' => "",
                            '12' => "",
                            '13' => "",
                        );                        
                    }                   
                }
            } break;     
        }

        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Nhân viên',
            'Số BG',
            'Ngày tạo',
            'Người tạo',
            'TVBH',
            'Khách hàng',
            'Loại báo giá',
            'Công việc',
            'Doanh thu',
            'Công',
            'Công tính lương',
            'Ngày hoàn tất',
            'Ngày thu tiền',
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "DỊCH VỤ - BÁO CÁO DOANH THU";
        $nhatKy->noiDung = "Xuất excel báo cáo doanh thu tổ phụ kiện";
        $nhatKy->save();
        return Excel::download(new ExportBaoCaoDoanhThuToPhuKienController(), 'baocaodoanhthutophukien.xlsx');
    }
}
