<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\KhoV2;
use App\HopDong;
use App\TypeCarDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class ExportBaoCaoHopDongController extends Controller implements FromCollection, WithHeadings
{
    use Exportable;
    private $from;
    private $to;
    private $baoCao;
    public function __construct($from,$to,$baoCao){
        $this->from = $from;
        $this->to = $to;
        $this->baoCao = $baoCao;
    }

    public function collection()
    {
        $hds = [];
        $_from = \HelpFunction::revertDate($this->from);
        $_to = \HelpFunction::revertDate($this->to);

        if (Auth::user()->hasRole('system') 
        || Auth::user()->hasRole('baocaohopdong')) {
            switch($this->baoCao) {
                case 1: {
                    $hd = HopDong::orderBy('id','desc')->get();
                } break;
                case 2: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 3: {
                    $hd = HopDong::where([
                        ['requestCheck','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 4: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 5: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',true],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 6: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 7: {
                    $hd = HopDong::where([
                        ['lead_check_cancel','=',true]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 8: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 9: {
                    $hd = HopDong::select("hop_dong.*")
                    ->join("kho_v2 as k","k.id","=","hop_dong.id_car_kho")
                    ->where([
                        ['hop_dong.requestCheck','=',true],
                        ['hop_dong.admin_check','=',true],
                        ['hop_dong.lead_check','=',true],
                        ['hop_dong.hdWait','=',false],
                        ['hop_dong.lead_check_cancel','=',false],
                        ['k.xuatXe','=',true],
                        ['hop_dong.hdDaily','=',false]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                default: $type = 0;
            }
        } else {
            switch($request->baoCao) {
                case 1: {
                    $hd = HopDong::where('id_user_create','=',Auth::user()->id)
                    ->orderBy('id','desc')->get();
                } break;
                case 2: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 3: {
                    $hd = HopDong::where([
                        ['requestCheck','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 4: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 5: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',true],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 6: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',true],
                        ['hdWait','=',false],
                        ['lead_check_cancel','=',false],
                        ['hdDaily','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 7: {
                    $hd = HopDong::where([
                        ['lead_check_cancel','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 8: {
                    $hd = HopDong::where([
                        ['requestCheck','=',true],
                        ['admin_check','=',true],
                        ['lead_check','=',false],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                case 9: {
                    $hd = HopDong::select("hop_dong.*")
                    ->join("kho_v2 as k","k.id","=","hop_dong.id_car_kho")
                    ->where([
                        ['hop_dong.requestCheck','=',true],
                        ['hop_dong.admin_check','=',true],
                        ['hop_dong.lead_check','=',true],
                        ['hop_dong.hdWait','=',false],
                        ['hop_dong.lead_check_cancel','=',false],
                        ['hop_dong.hdDaily','=',false],
                        ['k.xuatXe','=',true],
                        ['id_user_create','=',Auth::user()->id]
                    ])
                    ->orderBy('id','desc')
                    ->get();
                } break;
                default: abort(403);
            }
        }        
        $i = 1;
        foreach($hd as $row) {
            if ($this->baoCao != 9 && (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                $codeCar = $row->carSale->typeCar->code;
                $guest = $row->guest->name;
                $phone = $row->guest->phone;
                $sale = $row->user->userDetail->surname;
                $loaihd = ($row->hdDaiLy) ? "?????i l??" : "B??n l???";
                $isTienMat = ($row->isTienMat) ? "Ti???n m???t" : "Ng??n h??ng";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
                $hh = $row->hoaHongMoiGioi;               
                
               
                $package = $row->package;
                foreach($package as $row2) {                
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                       $khuyenMai += $row2->cost;
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "B???o hi???m v???t ch???t") {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "H??? tr??? ????ng k?? - ????ng ki???m") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi ph?? kh??c") {
                        $cpkhac += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                    }
                }

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $hh);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "".round($tiSuat,2)."" : "".round($tiSuat,2)."";

                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    $status = "H???p ?????ng ?????i l??";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "H???p ?????ng hu???";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "M???i t???o";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "?????i duy???t (admin)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "?????i duy???t (Tr?????ng ph??ng)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "H???p ?????ng ch???";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false)
                        $status = "H???p ?????ng k??";
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '2' => $row->nguonKH,
                    '3' => $loaihd,
                    '4' => $sale,
                    '5' => $guest,
                    '6' => $dongxe,
                    '7' => $mau,
                    '8' => $isTienMat,
                    '9' => $giaXe,
                    '10' => $cpkhac,
                    '11' => $giaVon,
                    '12' => $htvSupport,
                    '13' => $khuyenMai,
                    '14' => $bhvc,
                    '15' => $pkban,
                    '16' => $dangky,
                    '17' => $hh,
                    '18' => $loiNhuan,
                    '19' => $tiSuat,
                    '20' => $status,
                    '21' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
                );
            }    
            $ngayGiaoXe = "";
            if ($row->id_car_kho != null) {
                $kho = KhoV2::find($row->id_car_kho);
                $ngayGiaoXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
            }                
            if ($ngayGiaoXe != "" && $this->baoCao == 9 && (strtotime(\HelpFunction::revertDate($ngayGiaoXe)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::revertDate($ngayGiaoXe)) <= strtotime($_to))) {
                $codeCar = $row->carSale->typeCar->code;
                $guest = $row->guest->name;
                $phone = $row->guest->phone;
                $sale = $row->user->userDetail->surname;
                $loaihd = ($row->hdDaiLy) ? "?????i l??" : "B??n l???";
                $isTienMat = ($row->isTienMat) ? "Ti???n m???t" : "Ng??n h??ng";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
                $hh = $row->hoaHongMoiGioi;               
                
               
                $package = $row->package;
                foreach($package as $row2) {                
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                       $khuyenMai += $row2->cost;
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "B???o hi???m v???t ch???t") {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "H??? tr??? ????ng k?? - ????ng ki???m") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi ph?? kh??c") {
                        $dangky += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                    }
                }

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $hh);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "".round($tiSuat,2)."" : "".round($tiSuat,2)."";

                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    $status = "H???p ?????ng ?????i l??";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "H???p ?????ng hu???";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "M???i t???o";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "?????i duy???t (admin)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "?????i duy???t (Tr?????ng ph??ng)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "H???p ?????ng ch???";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false)
                        $status = "H???p ?????ng k??";
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '2' => $row->nguonKH,
                    '3' => $loaihd,
                    '4' => $sale,
                    '5' => $guest,
                    '6' => $dongxe,
                    '7' => $mau,
                    '8' => $isTienMat,
                    '9' => $giaXe,
                    '10' => $cpkhac,
                    '11' => $giaVon,
                    '12' => $htvSupport,
                    '13' => $khuyenMai,
                    '14' => $bhvc,
                    '15' => $pkban,
                    '16' => $dangky,
                    '17' => $hh,
                    '18' => $loiNhuan,
                    '19' => $tiSuat,
                    '20' => $status,
                    '21' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
                );
            }  
        }


        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ng??y t???o',
            'Ngu???n KH',
            'Lo???i H??',
            'Sale',
            'Kh??ch h??ng',
            'D??ng xe',
            'M??u',
            'Thanh to??n',
            'Gi?? b??n',
            'C???ng ti???n m???t',
            'Gi?? v???n',
            'H??? tr??? HTV',
            'Khuy???n m??i',
            'B???o hi???m b??n',
            'Ph??? ki???n b??n',
            '????ng k??',
            'Hoa h???ng MG',
            'L???i nhu???n',
            'T??? su???t',
            'Tr???ng th??i',
            'Ng??y xu???t xe',
        ];
    }
    
    public function export(){
        return Excel::download(new ExportBaoCaoHopDongController(), 'baocaohopdong.xlsx');
    }
}
