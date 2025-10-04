<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\KhoV2;
use App\NhatKy;
use App\SaleOffV2;
use App\BHPK;
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
        //-------------------------------
        $hds[] = array(
            '0' => "1",
            '1' => "2",
            '2' => "3",
            '3' => "4",
            '4' => "5",
            '6' => "6",
            '7' => "7",
            '8' => "8",
            '9' => "9",
            '10' => "10",
            '11' => "11",
            '12' => "12",
            '13' => "13",
            '14' => "14",
            '15' => "15",
            '16' => "16",
            '17' => "17",
            '18' => "18",
            '19' => "19",
            '20' => "20",
            '21' => "21",
            '22' => "22",
            '23' => "23",
            '24' => "24=22-23",
            '25' => "25",
            '26' => "26",
            '27' => "27=25-26",
            '28' => "28",
            '29' => "29",
            '30' => "30=28-29",
            '31' => "31",
            '32' => "32=(12+15+16)-(13+21+31)",
            '33' => "33=(32*100)/13",              
            '34' => "34",
            '35' => "35",
            '36' => "36",
            '37' => "37",
            '38' => "38",
            '39' => "39=32-(36+37+38)",
            '40' => "40=(39*100)/13",
            '41' => "41=39+24+27+30",
            '42' => "42=(41*100/13)",
        );
        //-------------------------------
        $i = 1;
        foreach($hd as $row) {
            if ($this->baoCao != 9 && (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                $codeCar = $row->carSale->typeCar->code;
                $guest = $row->guest->name;
                $phone = $row->guest->phone;
                $sale = $row->user->userDetail->surname;
                $loaihd = ($row->hdDaiLy) ? "Đại lý" : "Bán lẻ";
                $isTienMat = ($row->isTienMat) ? "Tiền mặt" : "Ngân hàng";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                // $magiamgia = $row->magiamgia;

                $giaNiemYet = $row->giaNiemYet;
                $truTienMat = ($giaNiemYet > $giaXe) ? ($giaNiemYet - $giaXe) : 0;

                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $phiVanChuyen = $row->phiVanChuyen;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
                // Support KT ----------
                $tangTB = 0;
                $tangBH = 0;
                $tangPK = 0;
                $tangCongDK = 0;
                $ngayNhanNo = 0;
                $phiLaiVay = 0;
                $phiLuuKho = 0;
                $hhSale = $row->hoaHongSale;                
                $pvc = $row->phiVanChuyen;
                //---------------
                $giavonbh = 0;
                $hhcongdk = 0;
                $_giavonpk = 0;
                $loinhuanbaohiem = 0;
                $loinhuancongdk = 0;
                if ($row->id_car_kho != null) {
                    $ktKho = KhoV2::find($row->id_car_kho); 
                    $phiLuuKho = $ktKho->xangLuuKho;    
                    $giavonbh = $ktKho->giavonbh;
                    $hhcongdk = $ktKho->hhcongdk;    
                    $_giavonpk = $ktKho->giavonpk;          
                    if ($ktKho->ngayNhanNo != null) {
                        $date_1 = strtotime($ktKho->ngayNhanNo);
                        if ($ktKho->ngayRutHoSo != null)
                            $date_2 = strtotime($ktKho->ngayRutHoSo);
                        else
                            $date_2 = time();
                        $datediff = $date_2 - $date_1;
                        $ngayNhanNo = round($datediff / (60 * 60 * 24)) + 1;
                        if (($ktKho->giaTriVay != null && $ktKho->giaTriVay != 0) && ($ktKho->laiSuatVay != null &&  $ktKho->laiSuatVay != 0)) {
                            // let countNgayNhanNo = Math.abs(CountTheDays(date_1, date_2)) + 1;
                            // formatNumber(Math.round((row.giaVon * (row.giaTriVay/100) * (row.laiSuatVay/100)) / 365) * countNgayNhanNo) + "</strong>";
                            $phiLaiVay = round(($giaVon * ($ktKho->giaTriVay / 100) * ($ktKho->laiSuatVay / 100)) / 365) * $ngayNhanNo;
                        }                       
                    }
                }                  
                // ---------------------    
                $package = $row->package;
                foreach($package as $row2) {    
                    if ($row2->isLanDau == false && $row2->isDuyetLanSau == false)
                        continue;
                    
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                        // if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        //     $p = BHPK::find($row2->mapk);
                        //     $khuyenMai += $p->giaVon;
                        // } else {
                        //     $khuyenMai += $row2->cost;
                        // }
            
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += ($p->giaVon + $p->congKTV);
                        $khuyenMai += ($p->giaVon + $p->congKTV);
                       } else {
                            $p = BHPK::find($row2->mapk);
                            if ($row2->mapk && $row2->mode && $row2->mode == "TANGTHEM") {
                                $tangPK += ($p->giaVon + $p->congKTV);
                                $khuyenMai += ($p->giaVon + $p->congKTV);
                            }
                            elseif ($row2->mapk && $row2->mode && $row2->mode == "CTKM") {
                                $tangPK += ($p->giaVon + $p->congKTV);
                                $khuyenMai += ($p->giaVon + $p->congKTV);
                            }
                            else {
                                $tangPK += $row2->cost;
                                $khuyenMai += $row2->cost;
                            }
                       }
                       // -----------------------    
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                       // ---- Suport KT --------
                       if ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS") {
                        $tangBH = $row2->cost;
                       }
                       if ($row2->name == "Phí trước bạ") {
                        $tangTB = $row2->cost;
                       }
                       if ($row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $tangCongDK = $row2->cost;
                       }
                       // -----------------------
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS")) {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $saleOff = SaleOffV2::select("*")->where([
                            ['id_hd','=',$row->id],
                            ['id_bh_pk_package','=',$row2->id]
                        ])->first();
                        $pkban += $row2->cost - ($row2->cost*$saleOff->giamGia/100);
                    }
                }

                $loinhuanbaohiem = $bhvc - $giavonbh;
                $loinhuancongdk = $dangky - ($dangky*$hhcongdk/100);
                $loinhuanpkban = $pkban - $_giavonpk;

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $phiVanChuyen);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "".round($tiSuat,2)."" : "".round($tiSuat,2)."";
                // ---- Suport KT --------
                $laiGop = $loiNhuan - ($phiLuuKho + $phiLaiVay + $hhSale);
                $tiSuatLaiGop = ($giaVon) ? ($laiGop*100/$giaVon) : 0;
                $tiSuatLaiGop = ($tiSuatLaiGop < 3) ? round($tiSuatLaiGop,2) : round($tiSuatLaiGop,2);
                // -----------------------
                $loinhuanfinal = $laiGop + $loinhuanbaohiem + $loinhuancongdk + $loinhuanpkban;
                $tiSuatFinal = ($giaVon) ? ($loinhuanfinal*100/$giaVon) : 0;
                $tiSuatFinal = ($tiSuatFinal < 3) ? round($tiSuatFinal,2) : round($tiSuatFinal,2);

                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    if ($row->hdWait == true) 
                        $status = "Hợp đồng chờ";
                    else 
                        $status = "Hợp đồng ký";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "Hợp đồng huỷ";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "Mới tạo";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "Đợi duyệt (admin)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "Đợi duyệt (Trưởng phòng)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "Hợp đồng chờ";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false) {
                        if ($ngayXuatXe) 
                            $status = "Đã giao xe";
                        else
                            $status = "Hợp đồng ký";
                    }
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '2' => $row->guest->nguon,
                    '3' => $loaihd,
                    '4' => $status,
                    '6' => $sale,
                    '7' => $guest,
                    '8' => $dongxe,
                    '9' => $mau,
                    '10' => $isTienMat,
                    '11' => $giaNiemYet,
                    '12' => $giaXe,
                    '13' => $giaVon,
                    '14' => $truTienMat,
                    '15' => $cpkhac,
                    '16' => $htvSupport,
                    '17' => $tangTB,
                    '18' => $tangBH,
                    '19' => $tangPK,
                    '20' => $tangCongDK,
                    '21' => $khuyenMai,
                    '22' => $bhvc,
                    '23' => $giavonbh,
                    '24' => $loinhuanbaohiem,
                    '25' => $pkban,
                    '26' => $_giavonpk,
                    '27' => $loinhuanpkban,
                    '28' => $dangky,
                    '29' => ($dangky*$hhcongdk/100),
                    '30' => $loinhuancongdk,
                    '31' => $pvc,
                    '32' => $loiNhuan,
                    '33' => $tiSuat,                    
                    '34' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
                    '35' => $ngayNhanNo,
                    '36' => $phiLaiVay,
                    '37' => $phiLuuKho,
                    '38' => $hhSale,
                    '39' => $laiGop,
                    '40' => $tiSuatLaiGop,
                    '41' => $loinhuanfinal,
                    '42' => $tiSuatFinal,
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
                $loaihd = ($row->hdDaiLy) ? "Đại lý" : "Bán lẻ";
                $isTienMat = ($row->isTienMat) ? "Tiền mặt" : "Ngân hàng";
                $dongxe = TypeCarDetail::find($row->id_car_sale)->name;
                $mau = $row->mau;
                $giaXe = $row->giaXe;
                // $magiamgia = $row->magiamgia;

                $giaNiemYet = $row->giaNiemYet;
                $truTienMat = ($giaNiemYet > $giaXe) ? ($giaNiemYet - $giaXe) : 0;

                $giaVon = 0;
                if ($row->isGiaVon) {
                    $giaVon = TypeCarDetail::find($row->id_car_sale)->giaVon;
                } else {
                    $giaVon = $row->giaVon;
                }
                $htvSupport = $row->htvSupport;
                $phiVanChuyen = $row->phiVanChuyen;
                $khuyenMai = 0;
                $bhvc = 0;
                $pkban = 0;
                $dangky = 0;
                $cpkhac = 0;
               // Support KT ----------
               $tangTB = 0;
               $tangBH = 0;
               $tangPK = 0;
               $tangCongDK = 0;
               $ngayNhanNo = 0;
               $phiLaiVay = 0;
               $phiLuuKho = 0;
               $hhSale = $row->hoaHongSale;                
               $pvc = $row->phiVanChuyen;
               $giavonbh = 0;
               $hhcongdk = 0;
               $_giavonpk = 0;
               $loinhuanbaohiem = 0;
               $loinhuancongdk = 0;
               if ($row->id_car_kho != null) {
                   $ktKho = KhoV2::find($row->id_car_kho); 
                   $phiLuuKho = $ktKho->xangLuuKho;      
                   $giavonbh = $ktKho->giavonbh;
                   $hhcongdk = $ktKho->hhcongdk;
                   $_giavonpk = $ktKho->giavonpk;            
                   if ($ktKho->ngayNhanNo != null) {
                       $date_1 = strtotime($ktKho->ngayNhanNo);
                       if ($ktKho->ngayRutHoSo != null)
                           $date_2 = strtotime($ktKho->ngayRutHoSo);
                       else
                           $date_2 = time();
                       $datediff = $date_2 - $date_1;
                       $ngayNhanNo = round($datediff / (60 * 60 * 24)) + 1;
                       if (($ktKho->giaTriVay != null && $ktKho->giaTriVay != 0) && ($ktKho->laiSuatVay != null &&  $ktKho->laiSuatVay != 0)) {
                           // let countNgayNhanNo = Math.abs(CountTheDays(date_1, date_2)) + 1;
                           // formatNumber(Math.round((row.giaVon * (row.giaTriVay/100) * (row.laiSuatVay/100)) / 365) * countNgayNhanNo) + "</strong>";
                           $phiLaiVay = round(($giaVon * ($ktKho->giaTriVay / 100) * ($ktKho->laiSuatVay / 100)) / 365) * $ngayNhanNo;
                       }                       
                   }
               }                  
               // ---------------------   
                $package = $row->package;
                foreach($package as $row2) { 
                    if ($row2->isLanDau == false && $row2->isDuyetLanSau == false)
                        continue;

                    if ($row2->type == 'free' && $row2->free_kem == false) {                      
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += ($p->giaVon + $p->congKTV);
                        $khuyenMai += ($p->giaVon + $p->congKTV);
                       } else {
                            $p = BHPK::find($row2->mapk);
                            if ($row2->mapk && $row2->mode && $row2->mode == "TANGTHEM") {
                                $tangPK += ($p->giaVon + $p->congKTV);
                                $khuyenMai += ($p->giaVon + $p->congKTV);
                            }
                            elseif ($row2->mapk && $row2->mode && $row2->mode == "CTKM") {
                                $tangPK += ($p->giaVon + $p->congKTV);
                                $khuyenMai += ($p->giaVon + $p->congKTV);
                            }
                            else {
                                $tangPK += $row2->cost;
                                $khuyenMai += $row2->cost;
                            }
                       }
                       // -----------------------   
                    }
                    if ($row2->type == 'cost' && $row2->cost_tang == true) {
                       $khuyenMai += $row2->cost;
                       // ---- Suport KT --------
                       if ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS") {
                        $tangBH = $row2->cost;
                       }
                       if ($row2->name == "Phí trước bạ") {
                        $tangTB = $row2->cost;
                       }
                       if ($row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $tangCongDK = $row2->cost;
                       }
                       // -----------------------
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && ($row2->name == "Bảo hiểm vật chất" || $row2->name == "Bảo hiểm TNDS")) {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Chi phí khác") {
                        $cpkhac += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $saleOff = SaleOffV2::select("*")->where([
                            ['id_hd','=',$row->id],
                            ['id_bh_pk_package','=',$row2->id]
                        ])->first();
                        $pkban += $row2->cost - ($row2->cost*$saleOff->giamGia/100);
                    }
                }

                $loinhuanbaohiem = $bhvc - $giavonbh;
                $loinhuancongdk = $dangky - ($dangky*$hhcongdk/100);
                $loinhuanpkban = $pkban - $_giavonpk;

                $loiNhuan = ($giaXe + $cpkhac + $htvSupport) - ($khuyenMai + $giaVon + $phiVanChuyen);
                // $tiSuat = ($giaXe) ? ($loiNhuan*100/$giaXe) : 0;
                $tiSuat = ($giaVon) ? ($loiNhuan*100/$giaVon) : 0;
                $tiSuat = ($tiSuat < 3) ? "".round($tiSuat,2)."" : "".round($tiSuat,2)."";
                // ---- Suport KT --------
                $laiGop = $loiNhuan - ($phiLuuKho + $phiLaiVay + $hhSale);
                $tiSuatLaiGop = ($giaVon) ? ($laiGop*100/$giaVon) : 0;
                $tiSuatLaiGop = ($tiSuatLaiGop < 3) ? round($tiSuatLaiGop,2) : round($tiSuatLaiGop,2);
                // -----------------------
                $loinhuanfinal = $laiGop + $loinhuanbaohiem + $loinhuancongdk + $loinhuanpkban;
                $tiSuatFinal = ($giaVon) ? ($loinhuanfinal*100/$giaVon) : 0;
                $tiSuatFinal = ($tiSuatFinal < 3) ? round($tiSuatFinal,2) : round($tiSuatFinal,2);

                $ngayXuatXe = "";
                if ($row->id_car_kho != null) {
                    $kho = KhoV2::find($row->id_car_kho);
                    $ngayXuatXe = ($kho->ngayGiaoXe) ? $kho->ngayGiaoXe : "";
                }               

                $status = "";
                if ($row->hdDaiLy == true && $row->lead_check == true && $row->lead_check_cancel == false) {
                    if ($row->hdWait == true) 
                        $status = "Hợp đồng chờ";
                    else 
                        $status = "Hợp đồng ký";
                } elseif ($row->lead_check_cancel == true) {
                    $status = "Hợp đồng huỷ";
                } else {
                    if ($row->requestCheck == false) 
                    $status = "Mới tạo";
                    elseif ($row->requestCheck == true && $row->admin_check == false)
                        $status = "Đợi duyệt (admin)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == false)
                        $status = "Đợi duyệt (Trưởng phòng)";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == true)
                        $status = "Hợp đồng chờ";
                    elseif ($row->requestCheck == true 
                    && $row->admin_check == true 
                    && $row->lead_check == true 
                    && $row->hdWait == false) {
                        if ($ngayXuatXe) 
                            $status = "Đã giao xe";
                        else
                            $status = "Hợp đồng ký";
                    }
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '2' => $row->guest->nguon,
                    '3' => $loaihd,
                    '4' => $status,
                    '6' => $sale,
                    '7' => $guest,
                    '8' => $dongxe,
                    '9' => $mau,
                    '10' => $isTienMat,
                    '11' => $giaNiemYet,
                    '12' => $giaXe,
                    '13' => $giaVon,
                    '14' => $truTienMat,
                    '15' => $cpkhac,
                    '16' => $htvSupport,
                    '17' => $tangTB,
                    '18' => $tangBH,
                    '19' => $tangPK,
                    '20' => $tangCongDK,
                    '21' => $khuyenMai,
                    '22' => $bhvc,
                    '23' => $giavonbh,
                    '24' => $loinhuanbaohiem,
                    '25' => $pkban,
                    '26' => $_giavonpk,
                    '27' => $loinhuanpkban,
                    '28' => $dangky,
                    '29' => ($dangky*$hhcongdk/100),
                    '30' => $loinhuancongdk,
                    '31' => $pvc,
                    '32' => $loiNhuan,
                    '33' => $tiSuat,                    
                    '34' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
                    '35' => $ngayNhanNo,
                    '36' => $phiLaiVay,
                    '37' => $phiLuuKho,
                    '38' => $hhSale,
                    '39' => $laiGop,
                    '40' => $tiSuatLaiGop,
                    '41' => $loinhuanfinal,
                    '42' => $tiSuatFinal,
                );
            }  
        }
        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ngày tạo',
            'Nguồn KH',
            'Loại HĐ',
            'Trạng thái',
            'Sale',
            'Khách hàng',
            'Dòng xe',
            'Màu',
            'Thanh toán',
            'Giá niêm yết (Nhà máy)',
            'Giá bán',
            'Giá vốn',
            'Bán giảm (So với giá niêm yết)',
            'Cộng tiền mặt',
            'Hỗ trợ HTV',
            'Tặng trước bạ',
            'Tặng bảo hiểm',
            'Tặng phụ kiện',
            'Tặng công ĐK',
            'Tổng khuyến mãi',
            'Bảo hiểm bán',
			'Giá vốn BH',
			'Lợi nhuận BH',
            'Phụ kiện bán',
			'Giá vốn pk',
			'Lợi nhuận pk',
            'Công đăng ký',
			'HH công đk',
			'Lợi nhuận công đk',
            'Phí vận chuyển',
            'Lợi nhuận xe',
            'Tỉ suất LN xe',
            'Ngày xuất xe',
            'Ngày nhận nợ',
            'Phí lãi vay',
            'Phí lưu kho',
            'HH sale',
            'Lãi gộp',
            'Tỉ suất lãi gộp',
			'Chốt lợi nhuận',
            'Tỉ suất',
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "KINH DOANH - BÁO CÁO HỢP ĐỒNG";
        $nhatKy->noiDung = "Xuất excel báo cáo hợp đồng";
        $nhatKy->save();
        return Excel::download(new ExportBaoCaoHopDongController(), 'baocaohopdong.xlsx');
    }
}
