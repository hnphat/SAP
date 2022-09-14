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
            case 1: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Người tạo</th>
                    <th>Sale</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>                    
                    <th>Tổng</th>
                </tr>
                <tbody>";   
                if ($nv == 0) {
                    $bg = BaoGiaBHPK::select("*")
                    ->where([
                        ['isDone','=',true],
                        ['isCancel','=',false],
                        ['isBaoHiem','=', true]
                    ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                        &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $temp_cong = 0;
                            $temp_phutung = 0;
                            $temp_chietkhau = 0;
                            foreach($ct as $item) {
                                $bhpk = BHPK::where([
                                    ['id','=',$item->id_baohiem_phukien],
                                    ['isPK','=',false]
                                ])->exists();
                                if ($bhpk) {
                                    $temp_cong += $item->thanhTien;
                                    $temp_chietkhau += $item->chietKhau;
                                    if ($row->isPKD) {
                                        $c_kd += $item->thanhTien;
                                        $tong_kd += $item->thanhTien;
                                        $tong_ck_kd += $item->chietKhau;
                                    } else {
                                        $c_kt += $item->thanhTien;
                                        $tong_kt += $item->thanhTien;
                                        $tong_ck_kt += $item->chietKhau;
                                    }         
                                }                                      
                            }
                            echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                        <td>".$row->user->userDetail->surname."</td>
                                        <td>".$row->nvKD."</td>
                                        <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                        <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                        <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_chietkhau).")</span></td>
                                        <td class='text-bold text-success'></td>
                                        <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                        <td class='text-bold text-primary'>".number_format($temp_cong)."</td>
                                    </tr>";
                        }
                    }
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kd)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($c_kt)."</span>
                                </p>
                            </div>
                    </div>";
                } else {    
                    $bg = BaoGiaBHPK::select("*")
                        ->where([
                            ['isDone','=',true],
                            ['isCancel','=',false],
                            ['isBaoHiem','=', true],
                            ['id_user_create','=',$nv]
                        ])
                        ->orderBy('isPKD','desc')->get();
                        foreach($bg as $row) {
                            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($den))) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $temp_cong = 0;
                                $temp_phutung = 0;
                                $temp_chietkhau = 0;
                                foreach($ct as $item) {
                                    $bhpk = BHPK::where([
                                        ['id','=',$item->id_baohiem_phukien],
                                        ['isPK','=',false]
                                    ])->exists();
                                    if ($bhpk) {
                                        $temp_cong += $item->thanhTien;
                                        $temp_chietkhau += $item->chietKhau;
                                        if ($row->isPKD) {
                                            $c_kd += $item->thanhTien;
                                            $tong_kd += $item->thanhTien;
                                            $tong_ck_kd += $item->chietKhau;
                                        } else {
                                            $c_kt += $item->thanhTien;
                                            $tong_kt += $item->thanhTien;
                                            $tong_ck_kt += $item->chietKhau;
                                        }         
                                    }                
                                }
                                echo "<tr>
                                            <td>".($i++)."</td>
                                            <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                                            <td>".$row->user->userDetail->surname."</td>
                                            <td>".$row->nvKD."</td>
                                            <td>".($row->isPKD ? "Báo giá kinh doanh" : "Báo giá khai thác")."</td>
                                            <td>BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at)."</td>
                                            <td class='text-bold text-success'>".number_format($temp_cong)."<span class='text-secondary'> (".number_format($temp_chietkhau).")</span></td>
                                            <td class='text-bold text-success'></td>
                                            <td class='text-bold text-secondary'>".number_format($temp_chietkhau)."</td>
                                            <td class='text-bold text-primary'>".number_format($temp_cong)."</td>
                                        </tr>";
                            }
                        }
                        echo "</tbody>
                            </table>        
                            <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($tong_kd + $tong_kt)."</span> trong đó:</h5>
                        <div class='row'>
                                <div class='col-md-3'>
                                    <h6>Báo giá kinh doanh:</h6>
                                    <p>
                                        - Công: <span class='text-bold text-success'>".number_format($c_kd)."</span> <span class='text-secondary'> (".number_format($tong_ck_kd).")</span><br/>
                                        - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                        - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kd)."</span><br/>
                                        => Tổng: <span class='text-bold text-primary'>".number_format($c_kd)."</span>
                                    </p>
                                </div>
                                <div class='col-md-3'>
                                    <h6>Báo giá khai thác:</h6>
                                    <p>
                                    - Công: <span class='text-bold text-success'>".number_format($c_kt)."</span> <span class='text-secondary'> (".number_format($tong_ck_kt).")</span><br/>
                                    - Phụ tùng: <span class='text-bold text-success'>0</span> <span class='text-secondary'>(0)</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($tong_ck_kt)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($c_kt)."</span>
                                    </p>
                                </div>
                        </div>";
                }
            } break;     
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
                    // ->where([
                    //     ['isDone','=',true],
                    //     ['isCancel','=',false],
                    //     ['isBaoHiem','=', false]
                    // ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        // if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                        // &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                        if ((strtotime($row->ngayThu) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($_to))) {
                            $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                            $_doanhthu = 0;
                            $_chiphitang = 0;
                            $_sale = "";
                            $_loaibg = "";
                            foreach($ct as $item) {
                                $_doanhthu += $item->thanhTien;
                                $_tongdoanhthu += $item->thanhTien;
                                if ($item->isTang) {
                                    $_chiphitang += $item->thanhTien;
                                    $_tongdoanhthu -= $item->thanhTien;
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
                                '4' => $_loaibg,
                                '5' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                '6' => $_doanhthu,
                                '7' => $_chiphitang,
                                '8' => ($_doanhthu-$_chiphitang),
                                '9' => \HelpFunction::revertDate($row->ngayThu),
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
                    // ->where([
                    //     ['isDone','=',true],
                    //     ['isCancel','=',false],
                    //     ['isBaoHiem','=', false]
                    // ])
                    ->orderBy('isPKD','desc')->get();
                    foreach($bg as $row) {
                        // if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                        // &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                        if ((strtotime($row->ngayThu) >= strtotime($_from)) 
                        &&  (strtotime($row->ngayThu) <= strtotime($_to))) {
                            if ($row->saler && $row->saler == $nv) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $_doanhthu = 0;
                                $_chiphitang = 0;
                                $_sale = "";
                                foreach($ct as $item) {
                                    $_doanhthu += $item->thanhTien;
                                    $_tongdoanhthu += $item->thanhTien;
                                    if ($item->isTang) {
                                        $_chiphitang += $item->thanhTien;
                                        $_tongdoanhthu -= $item->thanhTien;
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
                                    '4' => "Báo giá kinh doanh",
                                    '5' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                    '6' => $_doanhthu,
                                    '7' => $_chiphitang,
                                    '8' => ($_doanhthu-$_chiphitang),
                                    '9' => \HelpFunction::revertDate($row->ngayThu)
                                );
                            } 
                            
                            if (!$row->saler && $row->id_user_create == $nv) {
                                $ct = ChiTietBHPK::where('id_baogia',$row->id)->get();
                                $_doanhthu = 0;
                                $_chiphitang = 0;
                                $_sale = "";
                                foreach($ct as $item) {
                                    $_doanhthu += $item->thanhTien;
                                    $_tongdoanhthu += $item->thanhTien;
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
                                    '4' => "Báo giá khai thác",
                                    '5' => "BG0".$row->id."-".\HelpFunction::getDateCreatedAtRevert($row->created_at),
                                    '6' => $_doanhthu,
                                    '7' => $_chiphitang,
                                    '8' => ($_doanhthu-$_chiphitang),
                                    '9' => \HelpFunction::revertDate($row->ngayThu)
                                );
                            }                                                         
                            
                        }
                    }
                }
            } break;  
            case 3: {
                echo "
                <table class='table table-striped table-bordered'>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>KTV 1</th>
                    <th>KTV 2</th>
                    <th>Tỉ lệ</th>                    
                    <th>Người tạo BG</th>
                    <th>Loại BG</th>
                    <th>Số BG</th>
                    <th>Công</th>
                    <th>Chiết khấu</th>
                </tr>
                <tbody>";   
                if ($nv == 0) {
                    $user = User::all();
                    $i = 1;
                    foreach($user as $row) {
                        if ($row->hasRole("to_phu_kien")) {
                            $ct = ChiTietBHPK::where('id_user_work','=',$row->id)
                            ->orWhere('id_user_work_two','=',$row->id)
                            ->get();
                            foreach($ct as $item) {
                                $tiLe = 1;
                                $cong = 0;
                                $chietKhau = 0;
                                $ktv1 = "";
                                $ktv2 = "";
                                $nguoiTao = "";
                                $loaiBaoGia = false;
                                $soBaoGia = "";
                                if ($item->baoGia->isDone && !$item->baoGia->isCancel 
                                    && ((strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) >= strtotime($tu)) 
                                    &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) <= strtotime($den)))) {
                                    if ($item->id_user_work != $row->id) {
                                        $tiLe = 10 - $item->tiLe;
                                        $ktv2 = $item->userWorkTwo->userDetail->surname;
                                    } elseif ($item->id_user_work_two != $row->id) {
                                        $tiLe = $item->tiLe;
                                        $ktv1 = $item->userWork->userDetail->surname;
                                    } else {
                                        
                                    }
                                    $cong = $item->thanhTien * $tiLe/10;
                                    $chietKhau = $item->chietKhau * $tiLe/10;
                                    $nguoiTao = $item->baoGia->user->userDetail->surname;
                                    $loaiBaoGia = $item->baoGia->isPKD;
                                    if ($loaiBaoGia) {
                                        $toTongKD += ($item->thanhTien * $tiLe/10);
                                        $toTongCKKD +=  $item->chietKhau * $tiLe/10;
                                    } else {
                                        $toTongKT += ($item->thanhTien * $tiLe/10);
                                        $toTongCKKT +=  $item->chietKhau * $tiLe/10;
                                    }
                                    $toTong += ($item->thanhTien * $tiLe/10);
                                    $soBaoGia = "BG0".$item->baoGia->id."-".\HelpFunction::getDateCreatedAtRevert($item->baoGia->created_at);
                                    echo "<tr>
                                        <td>".($i++)."</td>
                                        <td>".\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)."</td>
                                        <td>".$ktv1."</td>
                                        <td>".$ktv2."</td>
                                        <td class='text-bold text-pink'>".$tiLe."/".(10-$tiLe)."</td>                                        
                                        <td>".$nguoiTao."</td>
                                        <td>".($loaiBaoGia == true ? "Báo giá KD" : "Báo giá khai thác")."</td>
                                        <td>".$soBaoGia."</td>
                                        <td>".number_format($cong)."</td>
                                        <td>".number_format($chietKhau)."</td>
                                        </tr>";
                                }
                            }
                        }
                    }                    
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($toTong)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($toTongKD)."</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKD)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($toTongKD)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($toTongKT)."</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKT)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($toTongKT)."</span>
                                </p>
                            </div>
                    </div>";
                } else {    
                    $i = 1;
                    $ct = ChiTietBHPK::where('id_user_work','=',$nv)
                    ->orWhere('id_user_work_two','=',$nv)
                    ->get();
                    foreach($ct as $item) {
                        $tiLe = 1;
                        $cong = 0;
                        $chietKhau = 0;
                        $ktv1 = "";
                        $ktv2 = "";
                        $nguoiTao = "";
                        $loaiBaoGia = false;
                        $soBaoGia = "";
                        if ($item->baoGia->isDone && !$item->baoGia->isCancel 
                            && ((strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)) <= strtotime($den)))) {
                            if ($item->id_user_work != $nv) {
                                $tiLe = 10 - $item->tiLe;
                                if ($item->id_user_work_two == $nv)
                                    $ktv2 = $item->userWorkTwo->userDetail->surname;
                            } elseif ($item->id_user_work_two != $nv)  {
                                $tiLe = $item->tiLe;
                                if ($item->id_user_work == $nv)
                                    $ktv1 = $item->userWork->userDetail->surname;
                            } else {

                            }

                            $cong = $item->thanhTien * $tiLe/10;
                            $chietKhau = $item->chietKhau * $tiLe/10;
                            $nguoiTao = $item->baoGia->user->userDetail->surname;
                            $loaiBaoGia = $item->baoGia->isPKD;
                            if ($loaiBaoGia) {
                                $toTongKD += ($item->thanhTien * $tiLe/10);
                                $toTongCKKD +=  $item->chietKhau * $tiLe/10;
                            } else {
                                $toTongKT += ($item->thanhTien * $tiLe/10);
                                $toTongCKKT +=  $item->chietKhau * $tiLe/10;
                            }
                            $toTong += ($item->thanhTien * $tiLe/10);
                            $soBaoGia = "BG0".$item->baoGia->id."-".\HelpFunction::getDateCreatedAtRevert($item->baoGia->created_at);
                            echo "<tr>
                                <td>".($i++)."</td>
                                <td>".\HelpFunction::getDateRevertCreatedAt($item->baoGia->created_at)."</td>
                                <td>".$ktv1."</td>
                                <td>".$ktv2."</td>
                                <td class='text-bold text-pink'>".$tiLe."/".(10-$tiLe)."</td>                                        
                                <td>".$nguoiTao."</td>
                                <td>".($loaiBaoGia == true ? "Báo giá KD" : "Báo giá khai thác")."</td>
                                <td>".$soBaoGia."</td>
                                <td>".number_format($cong)."</td>
                                <td>".number_format($chietKhau)."</td>
                                </tr>";
                        }
                    }              
                    echo "</tbody>
                        </table>        
                        <h5>Tổng doanh thu: <span class='text-bold text-info'>".number_format($toTong)."</span> trong đó:</h5>
                    <div class='row'>
                            <div class='col-md-3'>
                                <h6>Báo giá kinh doanh:</h6>
                                <p>
                                    - Công: <span class='text-bold text-success'>".number_format($toTongKD)."</span><br/>
                                    - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKD)."</span><br/>
                                    => Tổng: <span class='text-bold text-primary'>".number_format($toTongKD)."</span>
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <h6>Báo giá khai thác:</h6>
                                <p>
                                - Công: <span class='text-bold text-success'>".number_format($toTongKT)."</span><br/>
                                - Chiết khấu: <span class='text-bold text-secondary'>".number_format($toTongCKKT)."</span><br/>
                                => Tổng: <span class='text-bold text-primary'>".number_format($toTongKT)."</span>
                                </p>
                            </div>
                    </div>";
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
            'Loại báo giá',
            'Số báo giá',
            'Doanh thu',
            'Chi phí tặng',
            'Thực tế',
            'KT xác nhận',
        ];
    }
    
    public function export(){
        return Excel::download(new ExportBaoCaoDoanhThuController(), 'baocaodoanhthu.xlsx');
    }
}
