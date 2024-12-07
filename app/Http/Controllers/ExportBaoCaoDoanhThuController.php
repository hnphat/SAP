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


class ExportBaoCaoDoanhThuController extends Controller implements FromCollection, WithHeadings
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
        $i = 1;
        switch($chon) {                
            case 2: {
                if ($nv == 0) {
                    $_tongdoanhthu = 0;
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['trangThaiThu','=',true],
                        ['isBaoHiem','=', false],
                        ['isCancel','=',false],
                        ['isDone','=',true],
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime($row->ngayThu) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($_to))) {
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
                                    $_tongdoanhthu -= $item->thanhTien;
                                } else {
                                    $_chietKhauCost += (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    $_thucThu += ($item->soLuong * $item->donGia) - (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                }    
                                if ($row->saler) {
                                    $_sale = User::find($row->saler)->userDetail->surname;
                                    $_loaibg = "Báo giá kinh doanh";
                                } else {
                                    $_loaibg = "Báo giá khai thác";
                                }      
                                                       
                            }

                            $hds[] = array(
                                '0' => $i++,
                                '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                                '2' => $row->user->userDetail->surname,
                                '3' => $_sale,
                                '4' => $row->hoTen,
                                '5' => $row->soHopDongKD,
                                '6' => $_loaibg,
                                '7' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                '8' => $_doanhthu,
                                '9' => $_chiphitang,
                                '10' => $_chietKhauCost,
                                '11' => $_thucThu,
                                '12' => \HelpFunction::revertDate($row->ngayThu),
                            );
                        }
                    }
                } else {    
                    $_tongdoanhthu = 0;
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['trangThaiThu','=',true],
                        ['isBaoHiem','=', false],
                        ['isCancel','=',false],
                        ['isDone','=',true],
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime($row->ngayThu) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($_to))) {
                            if ($row->saler && $row->saler == $nv) {
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
                                        $_tongdoanhthu -= $item->thanhTien;
                                    } else {
                                        $_chietKhauCost += (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                        $_thucThu += ($item->soLuong * $item->donGia) - (($item->soLuong * $item->donGia) * $_chietKhau/100);
                                    }    
                                    if ($row->saler) {
                                        $_sale = User::find($row->saler)->userDetail->surname;
                                    }                             
                                }
                                $hds[] = array(
                                    '0' => $i++,
                                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                                    '2' => $row->user->userDetail->surname,
                                    '3' => $_sale,
                                    '4' => $row->hoTen,
                                    '5' => $row->soHopDongKD,
                                    '6' => $_loaibg,
                                    '7' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                    '8' => $_doanhthu,
                                    '9' => $_chiphitang,
                                    '10' => $_chietKhauCost,
                                    '11' => $_thucThu,
                                    '12' => \HelpFunction::revertDate($row->ngayThu),
                                );
                            } 
                            
                            if (!$row->saler && $row->id_user_create == $nv) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $_doanhthu = 0;
                                $_chiphitang = 0;
                                $_chietKhau = 0;
                                $_sale = "";
                                foreach($ct as $item) {
                                    $_doanhthu += $item->thanhTien;
                                    $_tongdoanhthu += $item->thanhTien;
                                    $_chietKhau += $item->chietKhau;
                                    if ($item->isTang) {
                                        $_chiphitang += $item->thanhTien;
                                        $_tongdoanhthu -= $item->thanhTien;
                                    }                            
                                }
                                $hds[] = array(
                                    '0' => $i++,
                                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                                    '2' => $row->user->userDetail->surname,
                                    '3' => $_sale,
                                    '4' => $row->hoTen,
                                    '5' => $row->soHopDongKD,
                                    '6' => "Báo giá khai thác",
                                    '7' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                    '8' => $_doanhthu,
                                    '9' => $_chiphitang . "/" . $_chietKhau,
                                    '10' => ($_doanhthu-$_chiphitang),
                                    '11' => \HelpFunction::revertDate($row->ngayThu)
                                );
                            }                                                         
                            
                        }
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
            'Ngày tạo',
            'Người tạo',
            'Sale',
            'KH',
            'Số hợp đồng kinh doanh',
            'Loại báo giá',
            'Số báo giá',
            'Doanh thu',
            'Tặng',
            'Chiết khấu',
            'Thực tế',
            'KT xác nhận',
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "DỊCH VỤ - BÁO CÁO DOANH THU";
        $nhatKy->noiDung = "Xuất excel báo cáo doanh thu";
        $nhatKy->save();
        return Excel::download(new ExportBaoCaoDoanhThuController(), 'baocaodoanhthu.xlsx');
    }
}
