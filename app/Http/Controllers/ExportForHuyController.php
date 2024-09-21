<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
use App\KhoV2;
use App\NhatKy;
use App\BHPK;
use App\HopDong;
use App\TypeCarDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class ExportForHuyController extends Controller implements FromCollection, WithHeadings
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
            '5' => "6",
            '6' => "7",
            '7' => "8",
            '8' => "9",
            '9' => "10",
            '10' => "11",
            '11' => "12",
            '12' => "13",
            '13' => "14",
            '14' => "15",
            '15' => "16",
            '16' => "17",
            '17' => "18",
            '18' => "19",
            '19' => "20",
            '20' => "21",
            '21' => "22",
            '22' => "23",
            '23' => "24",
            '24' => "25",
            '25' => "26",
            '26' => "27",
            '27' => "28",
            '28' => "29",
            '29' => "30",
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
                $magiamgia = $row->magiamgia;

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
                $sokhung = "";
                if ($row->id_car_kho != null) {
                    $ktKho = KhoV2::find($row->id_car_kho); 
                    $sokhung = $ktKho->vin;
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
                    if ($row2->type == 'free' && $row2->free_kem == false) {
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += $p->giaVon;
                        $khuyenMai += $p->giaVon;
                       } else {
                        $tangPK += $row2->cost;
                        $khuyenMai += $row2->cost;
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
                        $pkban += $row2->cost;
                    }
                }

                $loinhuanbaohiem = $bhvc*15/100;
                $loinhuancongdk = $dangky*55/100;
                $loinhuanpkban = ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100)))*50/100;

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
                    $status = "Hợp đồng đại lý";
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
                    && $row->hdWait == false)
                        $status = "Hợp đồng xe kho";
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => $row->guest->nguon,
                    '2' => $status,
                    '3' => $sale,
                    '4' => $guest,
                    '5' => $dongxe,
                    '6' => $mau,
                    '7' => $isTienMat,
                    '8' => $giaXe,
                    '9' => $giaVon,
                    '10' => $truTienMat,
                    '11' => $htvSupport,
                    '12' => $tangTB,
                    '13' => $tangBH,
                    '14' => $tangPK,
                    '15' => $tangCongDK,
                    '16' => $khuyenMai,
                    '17' => $bhvc,
                    '18' => $loinhuanbaohiem,
                    '19' => ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))),
                    '20' => $loinhuanpkban,
                    '21' => $dangky,
                    '22' => $loinhuancongdk,
                    '23' => $pvc,
                    '24' => $loiNhuan,
                    '25' => $tiSuat,             
                    '26' => $loinhuanfinal,
                    '27' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '28' => $phone,
                    '29' => $sokhung,
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
                $magiamgia = $row->magiamgia;

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
               $sokhung = "";
               if ($row->id_car_kho != null) {
                   $ktKho = KhoV2::find($row->id_car_kho);
                   $sokhung = $ktKho->vin; 
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
                    if ($row2->type == 'free' && $row2->free_kem == false) {                      
                       // ---- Suport KT --------
                       if ($row2->mapk && $row2->mode && $row2->mode == "GIABAN") {
                        $p = BHPK::find($row2->mapk);
                        $tangPK += $p->giaVon;
                        $khuyenMai += $p->giaVon;
                       } else {
                        $tangPK += $row2->cost;
                        $khuyenMai += $row2->cost;
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
                        $pkban += $row2->cost;
                    }
                }

                $loinhuanbaohiem = $bhvc*15/100;
                $loinhuancongdk = $dangky*55/100;
                $loinhuanpkban = ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100)))*50/100;

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
                    $status = "Hợp đồng đại lý";
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
                    && $row->hdWait == false)
                        $status = "Hợp đồng xe kho";
                }                
                $hds[] = array(
                    '0' => $i++,
                    '1' => $row->guest->nguon,
                    '2' => $status,
                    '3' => $sale,
                    '4' => $guest,
                    '5' => $dongxe,
                    '6' => $mau,
                    '7' => $isTienMat,
                    '8' => $giaXe,
                    '9' => $giaVon,
                    '10' => $truTienMat,
                    '11' => $htvSupport,
                    '12' => $tangTB,
                    '13' => $tangBH,
                    '14' => $tangPK,
                    '15' => $tangCongDK,
                    '16' => $khuyenMai,
                    '17' => $bhvc,
                    '18' => $loinhuanbaohiem,
                    '19' => ($magiamgia == 0 ? $pkban : ($pkban - ($pkban*$magiamgia/100))),
                    '20' => $loinhuanpkban,
                    '21' => $dangky,
                    '22' => $loinhuancongdk,
                    '23' => $pvc,
                    '24' => $loiNhuan,
                    '25' => $tiSuat,             
                    '26' => $loinhuanfinal,
                    '27' => \HelpFunction::getDateRevertCreatedAt($row->created_at),
                    '28' => $phone,
                    '29' => $sokhung,
                );
            }  
        }
        return (collect($hds));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Nguồn KH',
            'Trạng thái',
            'Sale',
            'Khách hàng',
            'Dòng xe',
            'Màu',
            'Thanh toán',
            'Giá bán',
            'Giá vốn',
            'Bán giảm (So với giá niêm yết)',
            'Hỗ trợ HTV',
            'Tặng trước bạ',
            'Tặng bảo hiểm',
            'Tặng phụ kiện',
            'Tặng công ĐK',
            'Tổng khuyến mãi',
            'Bảo hiểm bán',
			'Lợi nhuận BH',
            'Phụ kiện bán',
			'Lợi nhuận pk',
            'Công đăng ký',
			'Lợi nhuận công đk',
            'Phí vận chuyển',
            'Lợi nhuận xe',
            'Tỉ suất LN xe',
			'Tổng lợi nhuận HĐ',
            'Ngày tạo',
            'Số điện thoại',
            'Số khung',
        ];
    }
    
    public function export(){
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "KINH DOANH - BÁO CÁO HỢP ĐỒNG";
        $nhatKy->noiDung = "Xuất excel báo cáo hợp đồng custom dummy";
        $nhatKy->save();
        return Excel::download(new ExportForHuyController(), 'baocaohopdongcustom.xlsx');
    }
}
