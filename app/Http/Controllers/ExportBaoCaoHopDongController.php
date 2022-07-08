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
                $loaihd = ($row->hdDaiLy) ? "Đại lý" : "Bán lẻ";
                $isTienMat = ($row->isTienMat) ? "Tiền mặt" : "Ngân hàng";
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
                    && $row2->name == "Bảo hiểm vật chất") {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                    }
                }

                $loiNhuan = ($giaXe + $htvSupport) - ($khuyenMai + $giaVon + $hh);
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
                        $status = "Hợp đồng ký";
                }                
                $hd[] = array(
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
                    '10' => $giaVon,
                    '11' => $htvSupport,
                    '12' => $khuyenMai,
                    '13' => $bhvc,
                    '14' => $pkban,
                    '15' => $dangky,
                    '16' => $hh,
                    '17' => $loiNhuan,
                    '18' => $tiSuat,
                    '19' => $status,
                    '20' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
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
                    && $row2->name == "Bảo hiểm vật chất") {
                        $bhvc += $row2->cost;
                    } elseif ($row2->type == 'cost' 
                    && $row2->cost_tang == false
                    && $row2->name == "Hỗ trợ đăng ký - đăng kiểm") {
                        $dangky += $row2->cost;
                    }

                    if ($row2->type == 'pay') {
                        $pkban += $row2->cost;
                    }
                }

                $loiNhuan = ($giaXe + $htvSupport) - ($khuyenMai + $giaVon + $hh);
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
                        $status = "Hợp đồng ký";
                }                
                $hd[] = array(
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
                    '10' => $giaVon,
                    '11' => $htvSupport,
                    '12' => $khuyenMai,
                    '13' => $bhvc,
                    '14' => $pkban,
                    '15' => $dangky,
                    '16' => $hh,
                    '17' => $loiNhuan,
                    '18' => $tiSuat,
                    '19' => $status,
                    '20' => (($ngayXuatXe) ? \HelpFunction::revertDate($ngayXuatXe) : ""),
                );
            }  
        }


        return (collect($hd));
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ngày tạo',
            'Nguồn KH',
            'Loại HĐ',
            'Sale',
            'Khách hàng',
            'Dòng xe',
            'Màu',
            'Thanh toán',
            'Giá bán',
            'Giá vốn',
            'Hỗ trợ HTV',
            'Khuyến mãi',
            'Bảo hiểm bán',
            'Phụ kiện bán',
            'Đăng ký',
            'Hoa hồng MG',
            'Lợi nhuận',
            'Tỉ suất',
            'Trạng thái',
            'Ngày xuất xe',
        ];
    }
    
    public function export(){
        return Excel::download(new ExportBaoCaoHopDongController(), 'baocaohopdong.xlsx');
    }
}
