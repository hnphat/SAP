<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiPhep;
use App\User;
use App\Luong;
use App\XinPhep;
use App\TangCa;
use App\Nhom;
use App\NhomUser;
use App\QuanLyTangCa;
use App\XacNhanCong;
use App\NhatKy;
use App\BienBanKhenThuong;
use App\ChamCongChiTiet;
use App\Mail\EmailXinPhep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Excel;

class NhanSuController extends Controller
{
    // Quản lý chấm công
    public function quanLyChamCong() {
        $user = User::select("*")->where('active', true)->get();
        return view("nhansu.quanlychamcong", ['user' => $user]);
    }

    public function chiTietGetNhanVienRoom() {
        $room = NhomUser::select("*")
        ->where('id_user', Auth::user()->id)
        ->first();
        if ($room->leader == true) {
            $nhom = Nhom::find($room->id_nhom)->name;
            $user = User::select("*")->where('active', true)->get();
            foreach($user as $row) {
                if ($row->hasRole('chamcong') && $row->hasNhom($nhom) && $row->id != Auth::user()->id) {
                    echo "<option value='".$row->id."'>".$row->userDetail->surname."</option>";
                }
            }
        }
    }

    public function quanLyChamCongGetNhanVien(Request $request) {
        $user = User::find($request->id);
        $chiTiet = ChamCongChiTiet::select("*")
        ->where([
            ['id_user','=',$request->id],
            ['ngay','=',$request->ngay],
            ['thang','=',$request->thang],
            ['nam','=',$request->nam]
        ])
        ->first();
        if ($user)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã tải nhân viên",
                "data" => $user,
                "chiTiet" => $chiTiet
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);
    }

    public function quanLyChamCongPostNhanVien(Request $request) {

        $time_1 = "07:30";
        $time_2 = "11:30";
        $time_3 = "13:00";
        $time_4 = "17:00";

        $gioSang = 0;
        $gioChieu = 0;
        $treSang = 0;
        $treChieu = 0;

        // Xử lý ca sáng
        if ($request->vaoSang != null && $request->raSang != null) {
            $to_time = strtotime($request->vaoSang);
            $from_time = strtotime($time_1);
            $test = round(($to_time - $from_time)/60,2);
            if ($test > 0)
                $treSang += $test;
            
            $to_time = strtotime($request->raSang);
            $from_time = strtotime($time_2);
            $test = round(($to_time - $from_time)/60,2);
            if ($test < 0)
                $treSang += abs($test);
            
            if ($treSang == 0) {
                $to_time = strtotime($time_2);
                $from_time = strtotime($time_1);
                $gioSang = round(round(($to_time - $from_time)/60,2)/60,2);
            } else {
                $to_time = strtotime($time_2);
                $from_time = strtotime($time_1);
                $gioSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
            }    
        }

        // Xử lý ca chiều
        if ($request->vaoChieu != null && $request->raChieu != null) {
            $to_time = strtotime($request->vaoChieu);
            $from_time = strtotime($time_3);
            $test = round(($to_time - $from_time)/60,2);
            if ($test > 0)
                $treChieu += $test;
            
            $to_time = strtotime($request->raChieu);
            $from_time = strtotime($time_4);
            $test = round(($to_time - $from_time)/60,2);
            if ($test < 0)
                $treChieu += abs($test);

            if ($treChieu == 0) {
                $to_time = strtotime($time_4);
                $from_time = strtotime($time_3);
                $gioChieu = round(round(($to_time - $from_time)/60,2)/60,2);
            } else {
                $to_time = strtotime($time_4);
                $from_time = strtotime($time_3);
                $gioChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
            }    
        }
        
        if ($request->idChamCong != 0) {
            $chiTiet = ChamCongChiTiet::where([
                ['id','=',$request->idChamCong]
            ])
            ->update([
                'ngay' => $request->ngay,
                'thang' => $request->thang,
                'nam' => $request->nam,
                'vaoSang' => $request->vaoSang,
                'raSang' => $request->raSang,
                'vaoChieu' => $request->vaoChieu,
                'raChieu' => $request->raChieu,
                'gioSang' => $gioSang,
                'gioChieu' => $gioChieu,
                'treSang' => $treSang,
                'treChieu' => $treChieu,
            ]);
            if ($chiTiet)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã cập nhật chấm công",
                    "chiTiet" => $chiTiet
                ]);
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi tải dữ liệu"
                ]);
        } else {
            $chiTiet = ChamCongChiTiet::insert([
                'id_user' => $request->id,
                'ngay' => $request->ngay,
                'thang' => $request->thang,
                'nam' => $request->nam,
                'vaoSang' => $request->vaoSang,
                'raSang' => $request->raSang,
                'vaoChieu' => $request->vaoChieu,
                'raChieu' => $request->raChieu,
                'gioSang' => $gioSang,
                'gioChieu' => $gioChieu,
                'treSang' => $treSang,
                'treChieu' => $treChieu,
            ]);
            if ($chiTiet)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã thêm chấm công",
                    "chiTiet" => $chiTiet
                ]);
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi tải dữ liệu"
                ]);
        }       
    }

    // Quản lý loại phép
    public function quanLyPhep() {
        return view("nhansu.quanlyphep");
    }

    public function quanLyPhepGetList() {
        $loaiPhep = LoaiPhep::all();
        if ($loaiPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã tải dữ liệu",
                "data" => $loaiPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);
    }

    public function quanLyPhepPost(Request $request) {
        $loaiPhep = new LoaiPhep();
        $loaiPhep->tenPhep = $request->tenPhep;
        $loaiPhep->maPhep = $request->maPhep;
        $loaiPhep->loaiPhep = $request->loaiPhep;
        $loaiPhep->moTa = $request->moTa;
        $loaiPhep->save();
        if ($loaiPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - quản lý loại phép";
            $nhatKy->noiDung = "Thêm loại phép<br/>Tên phép: ".$request->tenPhep." Mã phép: "
            .$request->maPhep." Loại phép: ". $request->loaiPhep." Mô tả: ".$request->moTa;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã thêm dữ liệu",
                "data" => $loaiPhep
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thêm dữ liệu"
            ]);
    }

    public function quanLyPhepDelete(Request $request) {
        $loaiPhep = LoaiPhep::find($request->id);
        $temp = $loaiPhep;
        $loaiPhep->delete();
        if ($loaiPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - quản lý loại phép";
            $nhatKy->noiDung = "Xóa loại phép<br/>Tên phép: ".$temp->tenPhep." Mã phép: "
            .$temp->maPhep." Loại phép: ". $temp->loaiPhep." Mô tả: ".$temp->moTa;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa dữ liệu",
                "data" => $loaiPhep
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi xóa dữ liệu"
            ]);
    }

    // Chi tiết chấm công
    public function chiTietChamCong(Request $request){
        $user = User::select("*")->where('active', true)->get();
        $phep = LoaiPhep::all();
        return view("nhansu.chamcongchitiet", ['user' => $user, 'phep' => $phep]);

    }

    public function chiTietGetNhanVien(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $day = \HelpFunction::countDayInMonth($request->thang,$request->nam);
        $tongCong = 0;
        $tongTre = 0;
        $coPhep = 0;
        $khongPhep = $day;
        $lamThem = 0;
        $phepNam = 0;  
        $khongPhepCaNgay = 0;    
        // ----
        $chuaXinPhep = 0;
        $tongPhep = 0;
        // ----
        for($i = 1; $i <= $day; $i++) {            
            $tangCaGioHanhChinh = 0; 
            $thuMay = "";
            $specDate = $nam."-".$thang."-".$i;
            $ngayThu = array('Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4','Thứ 5','Thứ 6', 'Thứ 7');
            $numThu =  date('w', strtotime($specDate));
            switch((int)$numThu){
                case 0: $thuMay = "Chủ nhật"; break;
                case 1: $thuMay = "Thứ 2"; break;
                case 2: $thuMay = "Thứ 3"; break;
                case 3: $thuMay = "Thứ 4"; break;
                case 4: $thuMay = "Thứ 5"; break;
                case 5: $thuMay = "Thứ 6"; break;
                case 6: $thuMay = "Thứ 7"; break;
            }

            $chiTiet = ChamCongChiTiet::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['ngay','=',$i],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->first();

            $bienBan = BienBanKhenThuong::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->count();
            
            $xinPhep = XinPhep::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['ngay','=',$i],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->first();

            $tangCa = TangCa::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['ngay','=',$i],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->first();

            $quanLy = QuanLyTangCa::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['ngay','=',$i],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->first();

            $xacNhan = XacNhanCong::where([
                ['id_user','=',$request->id],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->exists();
           
            //Xử lý chưa xin phép
            if ($chiTiet !== null && $xinPhep === null && (($chiTiet->gioSang + $chiTiet->gioChieu) != 8)) {
                $chuaXinPhep += 1;        
            }

            //Xử lý tổng phép
            if ($xinPhep !== null)
                $tongPhep += 1;

            //Xử lý không phép
            if ($chiTiet !== null && (($chiTiet->gioSang + $chiTiet->gioChieu) == 8)) {
                $khongPhep -= 1;
            } elseif ($xinPhep != null && ($xinPhep->treSang == 0 && $xinPhep->treChieu == 0)) {
                $khongPhep -= 1;
            }

            if ($chiTiet === null && !\HelpFunction::isSunday($i,$thang,$nam)) {
                // strtotime($i."/".$thang."/".$nam) <= strtotime(Date('d-m-Y'))
                // dd(strtotime(Date('d-m-Y')));
                if ($xinPhep === null && ( strtotime($i."-".$thang."-".$nam) <= strtotime(Date('d-m-Y')) ))
                    $khongPhepCaNgay += 1;
                elseif ($xinPhep !== null && $xinPhep->user_duyet == false  && ( strtotime($i."-".$thang."-".$nam) <= strtotime(Date('d-m-Y')) ))
                    $khongPhepCaNgay += 1;
            }

            $stt = "";
            $flag = true;
            $phepSang = "";
            $phepChieu = "";
            $gioSang = "";
            $gioChieu = "";
            $treSang = "";
            $treChieu = "";
            $theDay = "";
            if (\HelpFunction::isSunday($i,$thang,$nam)) {
                $theDay = "<span class='text-danger'>".$i."/".$thang."/".$nam."</span>";
            } else {
                $theDay = "<span>".$i."/".$thang."/".$nam."</span>";
            }

            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                $stt = "<span class='text-info'>Đã duyệt phép</span>";
                $coPhep+=1;
                $flag = false;
                $phepSang = "<strong class='text-success'>".$xinPhep->vaoSang."</strong>";
                $phepChieu = "<strong class='text-success'>".$xinPhep->vaoChieu."</strong>";
                $gioSang = "<strong class='text-success'>".$xinPhep->gioSang."</strong>";
                $gioChieu = "<strong class='text-success'>".$xinPhep->gioChieu."</strong>";
                $treSang = "<strong class='text-success'>".$xinPhep->treSang."</strong>";
                $treChieu = "<strong class='text-success'>".$xinPhep->treChieu."</strong>";

                if ($xinPhep->loaiPhep->loaiPhep == "PHEPNAM") {
                    switch($xinPhep->buoi) {
                        case 'SANG': $phepNam += 0.5; break;
                        case 'CHIEU': $phepNam += 0.5; break;
                        case 'CANGAY': $phepNam += 1; break;
                    }
                }
            }               
            elseif ($xinPhep !== null && $xinPhep->user_duyet == false) {
                $stt = "<span class='text-secondary'>Đã gửi phép</span>";
                $flag = false;
            }             
                
                $btnXinPhep = "";
                $btnTangCa = "";

            if ($tangCa !== null) {
                if ($tangCa->user_duyet == true) {
                    $to_time = strtotime($tangCa->time2);
                    $from_time = strtotime($tangCa->time1);
                    $gioTangCa = round(round(($to_time - $from_time)/60,2)/60,2) * $tangCa->heSo;
                    $btnTangCa = "<span class='text-info'><strong>".$gioTangCa."h</strong> (Đêm)</span>";
                    $lamThem += $gioTangCa;
                }                   
                else 
                    $btnTangCa = "<span class='text-secondary'>Đợi duyệt</span>";
            } else {
                if (!$xacNhan)
                    $btnTangCa = "<button id='tangCa' data-toggle='modal' data-target='#addModalTangCa' data-ngay='".$i."' data-thang='".$thang."' data-nam='".$nam."' class='btn btn-sm btn-info'>Tăng ca</button>";
            }

            if($chiTiet !== null && $chiTiet->ngay == $i && $chiTiet->thang == $thang && $chiTiet->nam == $nam) {
                
                if ($quanLy !== null) {
                    if ($xinPhep !== null && $xinPhep->user_duyet == true)
                        $tangCaGioHanhChinh = (($xinPhep->gioSang + $xinPhep->gioChieu) * $quanLy->heSo);
                    else {
                        $tangCaGioHanhChinh = (($chiTiet->gioSang + $chiTiet->gioChieu)* $quanLy->heSo);
                    }             
                    $lamThem += $tangCaGioHanhChinh;   
                } 

                if ($chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4) {
                    if ($flag && !$xacNhan) 
                        $btnXinPhep = "<button id='xinPhep' data-toggle='modal' data-target='#addModal' data-ngay='".$chiTiet->ngay."' data-thang='".$chiTiet->thang."' data-nam='".$chiTiet->nam."' class='btn btn-sm btn-success'>Xin phép</button>";
                    else
                        $btnXinPhep = "";
                }        
                echo "
                <tr>
                    <td>".$theDay."<br/> ".$thuMay."</td>
                    <td class='text-success'>".$chiTiet->vaoSang." $phepSang</td>
                    <td class='text-success'>".$chiTiet->raSang." $phepSang</td>
                    <td class='text-success'>".$chiTiet->vaoChieu." $phepChieu</td>
                    <td class='text-success'>".$chiTiet->raChieu." $phepChieu</td>
                    <td class='text-info'>".$chiTiet->gioSang." $gioSang (giờ)</td>
                    <td class='text-info'>".$chiTiet->gioChieu." $gioChieu (giờ)</td>
                    <td class='text-danger'>".$chiTiet->treSang." $treSang (phút)</td>
                    <td class='text-danger'>".$chiTiet->treChieu." $treChieu (phút)</td>
                    <td>".$stt."</td>
                    <td>".$btnXinPhep."</td>
                    <td>".$btnTangCa."<br/><span style='color:blue;'>".($tangCaGioHanhChinh == 0 ? "" : $tangCaGioHanhChinh . "h (Ngày)")."</span></td>
                </tr>
                ";

                if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                    $tongTre += ($xinPhep->treSang + $xinPhep->treChieu);
                    if ($quanLy !== null) {
                    } else
                        $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                } else {
                    $tongTre += ($chiTiet->treSang + $chiTiet->treChieu);
                    if ($quanLy !== null) {
                    } else 
                        $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
                }         
            }
            else {
                $btn = "";
                if (!\HelpFunction::isSunday($i,$thang,$nam)) {
                    if ($flag && !$xacNhan)
                        $btn = "<button id='xinPhep' data-toggle='modal' data-target='#addModal' data-ngay='".$i."' data-thang='".$thang."' data-nam='".$nam."' class='btn btn-sm btn-success'>Xin phép</button>";
                    else
                        $btn = "";
                } else {
                    $khongPhep -= 1;
                }   

                if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                    $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                }  

                echo "
                <tr>
                    <td>".$theDay."<br/> ".$thuMay."</td>
                    <td class='text-success'>".$phepSang."</td>
                    <td class='text-success'>".$phepSang."</td>
                    <td class='text-success'>".$phepSang."</td>
                    <td class='text-success'>".$phepSang."</td>
                    <td class='text-info'>".$gioSang."</td>
                    <td class='text-info'>".$gioChieu."</td>
                    <td class='text-danger'></td>
                    <td class='text-danger'></td>
                    <td class='text-danger'>$stt</td>
                    <td class='text-danger'>$btn</td>
                    <td>$btnTangCa</td>
                </tr>
                ";
            }                
        }
        echo "
            <tr>
                <td colspan='2'><strong>TỔNG HỢP</strong></td>
                <td style='text-align:left;' colspan='10'>
                <strong style='font-size:80%;'>Công ngày làm việc: <span class='text-success'>".round((($tongCong/8) - $phepNam),2)." (ngày)</span>
                <br/><span>Phép năm: <span class='text-primary'>".round($phepNam,2)." (ngày)</span></span>
                <br/><span>Tăng ca: <span class='text-info'><strong>".$lamThem." (giờ) => ".round($lamThem/8,2)." (ngày)</strong></span>
                <br/><span>Trể/Sớm: <span class='text-danger'>".$tongTre."  (phút)</span></span>
                <br/><span>Phép được duyệt: <span class='text-success'>".$coPhep."</span></span>
                <br/><span>Phép chưa duyệt: <span class='text-danger'>".($tongPhep - $coPhep)."</span></span>
                <br/><span>Chưa xin phép (Vào trể; Về sớm; QCC; Vắng nữa buổi; Lý do khác): <span class='text-danger'>".$chuaXinPhep."</span></span>
                <br/><span>Vắng mặt không phép (cả ngày): <span class='text-danger'>".$khongPhepCaNgay."</span></span>
                <hr/>
                </strong>
                <strong>TỔNG CÔNG TÍNH LƯƠNG: <span class='text-primary'>".round((($tongCong/8) + ($lamThem/8)),2)." (ngày)</span></strong>
                </td>
            </tr>
            ";
            if (!$xacNhan) {
                session(['engaycong' => (round(($tongCong/8),2) - $phepNam), 'etangca' => round(($lamThem/8),2), 'ephepnam' => $phepNam]);
                echo "<tr>
                        <td colspan='2' style='text-align:left;'><button type='button' 
                        data-thang='".$thang."' 
                        data-nam='".$nam."' 
                        data-ngaycong='".(round(($tongCong/8),2) - $phepNam)."' 
                        data-tangca='".round(($lamThem/8),2)."' 
                        data-tongtre='".$tongTre."' 
                        data-khongphep='".($khongPhep - $khongPhepCaNgay)."' 
                        data-khongphepcangay='".$khongPhepCaNgay."' 
                        data-phepnam='".$phepNam."' 
                        id='xacNhan' class='btn btn-info'>Xác nhận giờ công</button></td>
                        <td colspan='4'>
                        <strong>Biên bản vi phạm:</strong> 
                        <strong class='text-danger'>".$bienBan."</strong>
                        <br/>
                        <button id='xemBienBan' class='btn btn-warning btn-sm' 
                        data-toggle='modal' 
                        data-target='#showModal' 
                        data-id='".$request->id."' 
                        data-thang='".$thang."' 
                        data-nam='".$nam."'>
                        XEM CHI TIẾT</button>
                        </td>
                    </tr>
                    ";
            }
    }

    public function chiTietThemPhep(Request $request) {
        $userDuyetEmail = User::find($request->nguoiDuyet);
        $emailDuyet = $userDuyetEmail->email;
        $loaiPhepEmail = LoaiPhep::find($request->loaiPhep)->tenPhep;
        $nguoiDuyetEmail = $userDuyetEmail->userDetail->surname;
        $nhanVien = User::find($request->idUserXin)->userDetail->surname;
        $ngayEmail = $request->ngayXin."-".$request->thangXin."-".$request->namXin;
        $lyDoEmail = $request->lyDo; 
        $check = XinPhep::where([
            ['ngay', '=', $request->ngayXin],
            ['thang', '=', $request->thangXin],
            ['nam', '=', $request->namXin],
            ['id_user', '=',$request->idUserXin]
        ])->exists();

        if (!$check) {
            $xinPhep = new XinPhep();
            $xinPhep->id_user = $request->idUserXin;
            $xinPhep->id_phep = $request->loaiPhep;
            $xinPhep->ngay = $request->ngayXin;
            $xinPhep->thang = $request->thangXin;
            $xinPhep->nam = $request->namXin;
            $xinPhep->buoi = $request->buoi;
            $xinPhep->id_user_duyet = $request->nguoiDuyet;
            $xinPhep->lyDo = $request->lyDo;
            // -----------
            // $month = (int)Date('m');
            // $year = (int)Date('Y');
            // if ($year > $request->namXin) {
            //     $month = 12;
            // }
    
            // Xử lý phép năm đang có của nhân viên
            $user = User::find($request->idUserXin);
            $current = "1-1-".Date('Y');            
            $phepNamThucTe = 0;
            if ($user->allowPhepNam == true && $user->ngay !== null) {
                $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
                if (strtotime($thangPhepNam) >= strtotime($current))
                    $phepNamThucTe = (int)Date('m') - (int)$user->thang + 1; 
                else 
                    $phepNamThucTe = (int)Date('m');                     
            }                   

            // --------------
    
            $suDung = 0;
            switch($request->buoi) {
                case 'SANG': $suDung = 0.5; break;
                case 'CHIEU': $suDung = 0.5; break;
                case 'CANGAY': $suDung = 1; break;
            }
            //Xử lý quên chấm công
            $getIdPhep = LoaiPhep::where('loaiPhep','QCC')->first()->id;
            $checkQCC = XinPhep::where([
                ['id_user','=',$request->idUserXin],
                ['id_phep','=',$getIdPhep],
                ['user_duyet','=', true],
                ['thang','=',$request->thangXin],
                ['nam','=',$request->namXin]
            ])->get();
            //--------------
    
            // Xử lý Phép năm
            $getIdPhepNam = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
            $checkPN = XinPhep::where([
                ['id_user','=',$request->idUserXin],
                ['id_phep','=',$getIdPhepNam],
                ['user_duyet','=', true],
                ['nam','=',$request->namXin]
            ])->get();
            $daSuDung = 0;
            foreach($checkPN as $row) {
                if ($row->buoi == "SANG") {
                    $daSuDung += 0.5;
                }
                if ($row->buoi == "CHIEU") {
                    $daSuDung += 0.5;
                }
                if ($row->buoi == "CANGAY") {
                    $daSuDung += 1;
                }
            }
    
            if ($request->loaiPhep == $getIdPhep && $checkQCC->count() == 1) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Đã quá số lần Quên chấm công trong tháng"
                ]);
            }
    
            if ($request->loaiPhep == $getIdPhepNam && ($daSuDung + $suDung > $phepNamThucTe)) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Phép năm đã dùng hết hoặc không đủ phép năm"
                ]);
            }
            //--------------
    
            $chiTiet = ChamCongChiTiet::select("*")
            ->where([
                ['id_user','=',$request->idUserXin],
                ['ngay','=',$request->ngayXin],
                ['thang','=',$request->thangXin],
                ['nam','=',$request->namXin]
            ])
            ->first();
    
            if ($chiTiet !== null) {
                $gioSang = $chiTiet->gioSang;
                $gioChieu = $chiTiet->gioChieu;
                $treSang = $chiTiet->treSang;
                $treChieu = $chiTiet->treChieu;
                $vaoSang = "";
                $raSang = "";
                $vaoChieu = "";
                $raChieu = ""; 
    
                $loaiPhep = LoaiPhep::find($request->loaiPhep);
                switch ($loaiPhep->loaiPhep) {
                    case 'PHEPNAM':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 4;
                                    $treSang = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 4;
                                    $treChieu = 0;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                case 'CANGAY': {
                                    $gioSang = 4;
                                    $gioChieu = 4;
                                    $treSang = 0;
                                    $treChieu = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                default: break;
                            }
                        }
                        break;
                    case 'QCC':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 4;
                                    $treSang = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 4;
                                    $treChieu = 0;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                default: break;
                            }
                        }
                        break;
                    case 'COLUONG':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 4;
                                    $treSang = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 4;
                                    $treChieu = 0;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                case 'CANGAY': {
                                    $gioSang = 4;
                                    $gioChieu = 4;
                                    $treSang = 0;
                                    $treChieu = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                default: break;
                            }
                        }
                        break;
                    case 'KHONGLUONG':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $treSang = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                } break;
                                case 'CHIEU': {
                                    $treChieu = 0;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                case 'CANGAY': {
                                    $treSang = 0;
                                    $treChieu = 0;
                                    $vaoSang = $loaiPhep->maPhep;
                                    $vaoChieu = $loaiPhep->maPhep;
                                } break;
                                default: break;
                            }
                        }
                        break;
                    default: break;
                }
    
                $xinPhep->gioSang = $gioSang;
                $xinPhep->gioChieu = $gioChieu;
                $xinPhep->treSang = $treSang;
                $xinPhep->treChieu= $treChieu;
                $xinPhep->vaoSang = $vaoSang;
                $xinPhep->raSang = $raSang;
                $xinPhep->vaoChieu = $vaoChieu;
                $xinPhep->raChieu= $raChieu;
                $xinPhep->save();
                if ($xinPhep) {
                    $loai = LoaiPhep::find($request->loaiPhep);
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Nhân sự - chấm công chi tiết";
                    $nhatKy->noiDung = "Thêm phép<br/>Lý do: ".$request->lyDo." Loại phép: ".$loai->tenPhep." Buổi: "
                    .$request->buoi." Ngày xin: "
                    .$request->ngayXin."/".$request->thangXin."/".$request->namXin;
                    $nhatKy->save();          
                    // -------
                    Mail::to($emailDuyet)->send(new EmailXinPhep([$nhanVien,$ngayEmail,$loaiPhepEmail,$lyDoEmail,$nguoiDuyetEmail,$request->buoi]));
                    // -------
                    return response()->json([
                        "type" => "info",
                        "code" => 200,
                        "message" => "Đã tạo phép"
                    ]);
                }
                else
                    return response()->json([
                        "type" => "info",
                        "code" => 500,
                        "message" => "Lỗi không thể tạo phép"
                    ]);
            } else {
                $gioSang = 0;
                $gioChieu = 0;
                $vaoSang = "";
                $vaoChieu = "";
                $loaiPhep = LoaiPhep::find($request->loaiPhep);
                switch ($loaiPhep->loaiPhep) {
                    case 'PHEPNAM':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 4;    
                                    $vaoSang = $loaiPhep->maPhep;                           
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 4;    
                                    $vaoChieu = $loaiPhep->maPhep;                            
                                } break;
                                case 'CANGAY': {
                                    $gioSang = 4;
                                    $gioChieu = 4;  
                                    $vaoSang = $loaiPhep->maPhep;   
                                    $vaoChieu = $loaiPhep->maPhep;                              
                                } break;
                                default: break;
                            }
                        }
                        break;
                    case 'COLUONG':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 4;     
                                    $vaoSang = $loaiPhep->maPhep;                                  
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 4;       
                                    $vaoChieu = $loaiPhep->maPhep;                                  
                                } break;
                                case 'CANGAY': {
                                    $gioSang = 4;
                                    $gioChieu = 4;     
                                    $vaoSang = $loaiPhep->maPhep;   
                                    $vaoChieu = $loaiPhep->maPhep;                                
                                } break;
                                default: break;
                            }
                        }
                        break;
                    case 'KHONGLUONG':
                        {
                            switch($request->buoi){
                                case 'SANG': {
                                    $gioSang = 0;   
                                    $vaoSang = $loaiPhep->maPhep;                                   
                                } break;
                                case 'CHIEU': {
                                    $gioChieu = 0;     
                                    $vaoChieu = $loaiPhep->maPhep;                                   
                                } break;
                                case 'CANGAY': {
                                    $gioSang = 0;
                                    $gioChieu = 0;    
                                    $vaoSang = $loaiPhep->maPhep;   
                                    $vaoChieu = $loaiPhep->maPhep;                                   
                                } break;
                                default: break;
                            }
                        }
                        break;
                    default: break;
                }
                $xinPhep->gioSang = $gioSang;
                $xinPhep->gioChieu = $gioChieu;
                $xinPhep->treSang = 0;
                $xinPhep->treChieu= 0;
                $xinPhep->vaoSang = $vaoSang;
                $xinPhep->vaoChieu = $vaoChieu;
                $xinPhep->save();
                if ($xinPhep) {
                    $loai = LoaiPhep::find($request->loaiPhep);
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Nhân sự - chấm công chi tiết";
                    $nhatKy->noiDung = "Thêm phép<br/>Lý do: ".$request->lyDo." Loại phép: ".$loai->tenPhep." Buổi: "
                    .$request->buoi." Ngày xin: "
                    .$request->ngayXin."/".$request->thangXin."/".$request->namXin;
                    $nhatKy->save();
                     // -------
                     Mail::to($emailDuyet)->send(new EmailXinPhep([$nhanVien,$ngayEmail,$loaiPhepEmail,$lyDoEmail,$nguoiDuyetEmail,$request->buoi]));
                     // -------
                    return response()->json([
                        "type" => "info",
                        "code" => 200,
                        "message" => "Đã tạo phép"
                    ]);
                }
                else
                    return response()->json([
                        "type" => "info",
                        "code" => 500,
                        "message" => "Lỗi không thể tạo phép"
                    ]);
            }
        } else {
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi phép tạo trùng"
            ]);
        }
    }


    // Xin tăng ca
    public function chiTietThemTangCa(Request $request) {
        $check = TangCa::where([
            ['ngay', '=', $request->ngayXinTangCa],
            ['thang', '=', $request->thangXinTangCa],
            ['nam', '=', $request->namXinTangCa],
            ['id_user', '=',$request->idUserXinTangCa]
        ])->exists();

        $ngay = $request->ngayXinTangCa."/".$request->thangXinTangCa."/".$request->namXinTangCa;
        
        if (!$check) {
            $tangCa = new TangCa();
            $tangCa->id_user = $request->idUserXinTangCa;
            $tangCa->ngay = $request->ngayXinTangCa;
            $tangCa->thang = $request->thangXinTangCa;
            $tangCa->nam = $request->namXinTangCa;
            $tangCa->id_user_duyet = $request->nguoiDuyetTangCa;
            $tangCa->lyDo = $request->lyDoTangCa;
            $tangCa->save();
            
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - chấm công chi tiết";
                $nhatKy->noiDung = "Xin phép tăng ca<br/>Ngày xin: ".$ngay."<br/>Lý do: ". $request->lyDoTangCa;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã xin phép tăng ca"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi không thể xin tăng ca"
                ]);
        } else {
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi trùng lắp"
            ]);
        }
    }

    // Xin phép
    public function xinPhepGetList() {
        $user = User::select("*")->where('active', true)->get();
        return view("nhansu.xinphep", ['user' => $user]);
    }

    public function xinPhepGetNhanVien(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $day = \HelpFunction::countDayInMonth($request->thang,$request->nam);
        for($i = 1; $i <= $day; $i++) {
            $xinPhep = XinPhep::select("*")
            ->where([
                ['id_user','=',$request->id],
                ['ngay','=',$i],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->first();
            
            $stt = "";
            $btn = "";
            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                $stt = "<span class='text-info'>Đã duyệt phép</span>";
                $btn = "";
            }               
            elseif ($xinPhep !== null && $xinPhep->user_duyet == false) {
                $stt = "<span class='text-secondary'>Chưa duyệt</span>";
                $btn = "<button id='delete' data-id='".$xinPhep->id."' class='btn btn-sm btn-danger'>Xóa</button>";
            } else {
                $btn = "";
            }   
       
            if ($xinPhep !== null) {
                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
                    <td>".$xinPhep->loaiPhep->tenPhep."</td>
                    <td>".$xinPhep->lyDo."</td>
                    <td>".$xinPhep->buoi."</td>
                    <td>".$xinPhep->userDuyet->userDetail->surname."</td>
                    <td>".$stt."</td>
                    <td>$btn</td>
                </tr>
                ";
            } else {
                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                ";
            }
        }   
    }

    public function xinPhepDelete(Request $request) {
        $xinPhep = XinPhep::find($request->id);
        $ngay = $xinPhep->ngay . "/" . $xinPhep->thang . "/" . $xinPhep->nam;
        $xinPhep->delete();
        if ($xinPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý xin phép";
            $nhatKy->noiDung = "Xóa xin phép ngày " . $ngay;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa phép",
                "data" => $xinPhep
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi xóa dữ liệu"
            ]);
    }

    public function xinPhepDeleteAdmin(Request $request) {
        if (Auth::user()->hasRole('system')) {
            $xinPhep = XinPhep::find($request->id);
            $ngay = $xinPhep->ngay . "/" . $xinPhep->thang . "/" . $xinPhep->nam;
            $xinPhep->delete();
            if ($xinPhep) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép";
                $nhatKy->noiDung = "Admin xóa xin phép " . $ngay;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã xóa phép",
                    "data" => $xinPhep
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi xóa dữ liệu"
                ]);
        } else {
            return response()->json([
                "type" => "error",
                "code" => 500,
                "message" => "Bạn không có quyền xóa phiếu đã duyệt!"
            ]);
        }        
    }
    // Phê duyệt phép
    public function pheDuyetGetList() {
        return view("nhansu.pheduyet");
    }

    public function pheDuyetPhepGetList() {
        if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('hcns') && !Auth::user()->hasRole('lead_chamcong'))
            $xinPhep = XinPhep::select("xin_phep.buoi","xin_phep.created_at","xin_phep.updated_at","xin_phep.id_user_duyet","xin_phep.id","xin_phep.ngay","xin_phep.thang","xin_phep.nam","xin_phep.lyDo","xin_phep.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin","p.tenPhep as loaiphep")
            ->join('users as u','u.id','=','xin_phep.id_user')
            ->join('users_detail as d','d.id_user','=','u.id')
            ->join('users as un','un.id','=','xin_phep.id_user_duyet')
            ->join('users_detail as dn','dn.id_user','=','un.id')
            ->join('loai_phep as p','p.id','=','xin_phep.id_phep')
            ->where([
                ['xin_phep.id_user_duyet', '=', Auth::user()->id]
            ])
            ->orderby('user_duyet','asc')
            ->get();
        else 
            $xinPhep = XinPhep::select("xin_phep.buoi","xin_phep.created_at","xin_phep.updated_at","xin_phep.id_user_duyet","xin_phep.id","xin_phep.ngay","xin_phep.thang","xin_phep.nam","xin_phep.lyDo","xin_phep.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin","p.tenPhep as loaiphep")
            ->join('users as u','u.id','=','xin_phep.id_user')
            ->join('users_detail as d','d.id_user','=','u.id')
            ->join('users as un','un.id','=','xin_phep.id_user_duyet')
            ->join('users_detail as dn','dn.id_user','=','un.id')
            ->join('loai_phep as p','p.id','=','xin_phep.id_phep')
            ->orderby('user_duyet','asc')
            ->get();    

        if ($xinPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã tải dữ liệu",
                "data" => $xinPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);
    }

    public function pheDuyetPhep(Request $request) {
        $check = XinPhep::where('id',$request->id)->first();
        $ngays = $check->ngay . "/" . $check->thang . "/" . $check->nam;
        $nhanvien = $check->user->userDetail->surname;
        $flag = false;
       
        $date1 = $check->nam."-".$check->thang."-".$check->ngay;
        $date2 = Date('d-m-Y');
        // $date2 = '2022-03-17';

        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        $dateDiff = round($diff / 86400);
        // dd($dateDiff);
        if ($dateDiff <= 2) {
            $flag = true;
        }
        // Xử lý phép năm đang có của nhân viên
        $user = User::find($check->id_user);
        $current = "1-1-".Date('Y');            
        $phepNamThucTe = 0;
        if ($user->allowPhepNam == true && $user->ngay !== null) {
            $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
            if (strtotime($thangPhepNam) >= strtotime($current))
                $phepNamThucTe = (int)Date('m') - (int)$user->thang + 1; 
            else 
                $phepNamThucTe = (int)Date('m');                     
        }    
        // ----------------
        $suDung = 0;
        switch($check->buoi) {
            case 'SANG': $suDung = 0.5; break;
            case 'CHIEU': $suDung = 0.5; break;
            case 'CANGAY': $suDung = 1; break;
        }
        //Xử lý quên chấm công
        $getIdPhep = LoaiPhep::where('loaiPhep','QCC')->first()->id;
        $checkQCC = XinPhep::where([
            ['id_user','=',$check->id_user],
            ['id_phep','=',$getIdPhep],
            ['user_duyet','=', true],
            ['thang','=',$check->thang],
            ['nam','=',$check->nam]
        ])->get();
        //--------------

        // Xử lý Phép năm
        $getIdPhepNam = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
        $checkPN = XinPhep::where([
            ['id_user','=',$check->id_user],
            ['id_phep','=',$getIdPhepNam],
            ['user_duyet','=', true],
            ['nam','=',$check->nam]
        ])->get();
        $daSuDung = 0;
        foreach($checkPN as $row) {
            if ($row->buoi == "SANG") {
                $daSuDung += 0.5;
            }
            if ($row->buoi == "CHIEU") {
                $daSuDung += 0.5;
            }
            if ($row->buoi == "CANGAY") {
                $daSuDung += 1;
            }
        }
        //--------------
        $userDuyetEmail = User::find($check->id_user_duyet);
        $loaiPhepEmail = LoaiPhep::find($check->id_phep)->tenPhep;
        $nguoiDuyetEmail = $userDuyetEmail->userDetail->surname;
        $nhanVien = User::find($check->id_user)->userDetail->surname;
        $ngayEmail = $check->ngay."-".$check->thang."-".$check->nam;
        $lyDoEmail = $check->lyDo; 


        if (Auth::user()->hasRole('system')) {   
            if ($check->id_phep == $getIdPhep && $checkQCC->count() == 1) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Không thể duyệt! Đã quá số lần duyệt Quên chấm công"
                ]);
            }

            if ($check->id_phep == $getIdPhepNam && ($daSuDung + $suDung > $phepNamThucTe)) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Không thể duyệt! Phép năm không đủ hoặc nhân viên đã dùng hết"
                ]);
            }

            $xinPhep = XinPhep::where('id',$request->id)->update([
                'user_duyet' => true
            ]);
            if ($xinPhep) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt phép";
                $nhatKy->noiDung = "Phê duyệt phép ngày " . $ngays . "<br/>Nhân viên yêu cầu: " . $nhanvien;
                $nhatKy->save();
                // -------
                $jsonString = file_get_contents('upload/cauhinh/app.json');
                $data = json_decode($jsonString, true); 
                Mail::to($data['emailPhep'])->send(new EmailXinPhep([$nhanVien . " [".$nguoiDuyetEmail." đã duyệt phép]",$ngayEmail,$loaiPhepEmail,$lyDoEmail,"Phòng nhân sự",$check->buoi]));
                // -------
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã phê duyệt phép"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Không thể phê duyệt phép"
                ]);
        } else {
            if ($check->id_user_duyet != Auth::user()->id) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Bạn không có quyền phê duyệt cho phép này"
                ]);
            } else {

                if ($check->id_phep == $getIdPhep && $checkQCC->count() == 1) {
                    return response()->json([
                        "type" => "error",
                        "code" => 500,
                        "message" => "Không thể duyệt! Đã quá số lần duyệt Quên chấm công"
                    ]);
                }

                if ($check->id_phep == $getIdPhepNam && ($daSuDung + $suDung > $phepNamThucTe)) {
                    return response()->json([
                        "type" => "error",
                        "code" => 500,
                        "message" => "Không thể duyệt! Phép năm không đủ hoặc nhân viên đã dùng hết"
                    ]);
                }
                // dd($flag);
                if ($flag) {
                    $xinPhep = XinPhep::where('id',$request->id)->update([
                        'user_duyet' => true
                    ]);
                    if ($xinPhep) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt phép";
                        $nhatKy->noiDung = "Trưởng bộ phận Phê duyệt phép ngày " . $ngays . "<br/>Nhân viên yêu cầu: " . $nhanvien;
                        $nhatKy->save();
                        // -------
                        $jsonString = file_get_contents('upload/cauhinh/app.json');
                        $data = json_decode($jsonString, true); 
                        Mail::to($data['emailPhep'])->send(new EmailXinPhep([$nhanVien . " [".$nguoiDuyetEmail." đã duyệt phép]",$ngayEmail,$loaiPhepEmail,$lyDoEmail,"Phòng nhân sự",$check->buoi]));
                        // -------
                        return response()->json([
                            "type" => "info",
                            "code" => 200,
                            "message" => "Đã phê duyệt phép"
                        ]);
                    }
                    else
                        return response()->json([
                            "type" => "info",
                            "code" => 500,
                            "message" => "Không thể phê duyệt phép"
                        ]);
                } else {
                    return response()->json([
                        "type" => "info",
                        "code" => 500,
                        "message" => "Không thể phê duyệt. Phép phải duyệt trước ngày xin phép tối thiểu 02 ngày!"
                    ]);
                }             
            }    
        }      
    }

    // Tổng hợp
    public function getTongHop() {
        return view("nhansu.tonghopcong");
    }

    public function tongHopXemNgay(Request $request) {
        $ngay = $request->ngay;
        $thang = $request->thang;
        $nam = $request->nam;
        $user = User::select("*")->where('active', true)->get();     
        $tt = 1;  
        foreach($user as $row) {
            $tangCaGioHanhChinh = 0;
            if ($row->hasRole('chamcong')) {
                $chiTiet = ChamCongChiTiet::where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$ngay],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                // Xử lý phép
                $xinPhep = XinPhep::where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$ngay],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                // Xử lý tăng ca
                $btnTangCa = "";
                $tangCa = TangCa::where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$ngay],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                $quanLy = QuanLyTangCa::where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$ngay],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                if ($tangCa !== null) {
                    if ($tangCa->user_duyet == true) {
                        $to_time = strtotime($tangCa->time2);
                        $from_time = strtotime($tangCa->time1);
                        $gioTangCa = round(round(($to_time - $from_time)/60,2)/60,2) * $tangCa->heSo;
                        $btnTangCa = "<span class='text-info'><strong>".$gioTangCa."</strong>h (Đêm)</span>";
                    }                   
                    else 
                        $btnTangCa = "<span class='text-secondary'>Đợi duyệt</span>";
                } 

                $stt = "";
                $gioSang = "";
                $gioChieu = "";
                $treSang = "";
                $treChieu = "";
                $phepSang = "";
                $phepChieu = "";
                if ($xinPhep !== null) {
                    if ($xinPhep->user_duyet == true) {
                        $stt = "<strong class='text-success'>Đã duyệt phép</strong>";
                        $gioSang = "<strong class='text-success'>".$xinPhep->gioSang."</strong>";
                        $gioChieu = "<strong class='text-success'>".$xinPhep->gioChieu."</strong>";
                        $treSang = "<strong class='text-success'>".$xinPhep->treSang."</strong>";
                        $treChieu = "<strong class='text-success'>".$xinPhep->treChieu."</strong>";
                        $phepSang = "<strong class='text-success'>".$xinPhep->vaoSang."</strong>";
                        $phepChieu = "<strong class='text-success'>".$xinPhep->vaoChieu."</strong>";
                    }

                    if ($xinPhep->user_duyet == false) {
                        $stt = "<strong class='text-secondary'>Phép chưa duyệt</strong>";
                    }                    
                } 
                // --------------------------

                if ($chiTiet !== null) {
                    if ($xinPhep === null && ($chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4)) {
                        // if (!\HelpFunction::isSunday($ngay,$thang,$nam)) {
                            $stt = "<strong class='text-danger'>K</strong>";
                        // }                   
                    }

                    if ($quanLy !== null) {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true)
                            $tangCaGioHanhChinh = (($xinPhep->gioSang + $xinPhep->gioChieu) * $quanLy->heSo);
                        else {
                            $tangCaGioHanhChinh = (($chiTiet->gioSang + $chiTiet->gioChieu)* $quanLy->heSo);
                        } 
                    } 

                    echo "
                    <tr>
                        <td>".$tt."</td>
                        <td>".$row->userDetail->surname."</td>
                        <td><span class='text-success'>".$chiTiet->vaoSang."</span> $phepSang</td>
                        <td><span class='text-success'>".$chiTiet->raSang."</span> $phepSang</td>
                        <td><span class='text-success'>".$chiTiet->vaoChieu."</span> $phepChieu</td>
                        <td><span class='text-success'>".$chiTiet->raChieu."</span> $phepChieu</td>
                        <td><span class='text-info'>".$chiTiet->gioSang."</span> $gioSang</td>
                        <td><span class='text-info'>".$chiTiet->gioChieu."</span> $gioChieu</td>
                        <td><span class='text-danger'>".$chiTiet->treSang."</span> $treSang</td>
                        <td><span class='text-danger'>".$chiTiet->treChieu."</span> $treChieu</td>
                        <td>".$stt."</td>
                        <td>".$btnTangCa."<br/><span style='color:blue;'>".($tangCaGioHanhChinh == 0 ? "" : $tangCaGioHanhChinh . "h (Ngày)")."</span></td>
                    </tr>
                    ";
                } else {
                    if ($xinPhep !== null) {
                        if ($xinPhep->user_duyet == true) {
                            $stt = "<strong class='text-success'>Đã duyệt phép</strong>";
                            $gioSang = "<strong class='text-success'>".$xinPhep->gioSang."</strong>";
                            $gioChieu = "<strong class='text-success'>".$xinPhep->gioChieu."</strong>";
                            $treSang = "<strong class='text-success'>".$xinPhep->treSang."</strong>";
                            $treChieu = "<strong class='text-success'>".$xinPhep->treChieu."</strong>";
                            $phepSang = "<strong class='text-success'>".$xinPhep->vaoSang."</strong>";
                            $phepChieu = "<strong class='text-success'>".$xinPhep->vaoChieu."</strong>";
                        }
    
                        if ($xinPhep->user_duyet == false) {
                            $stt = "<strong class='text-secondary'>Phép chưa duyệt</strong>";
                        }                  
                    } else {
                       if (!\HelpFunction::isSunday($ngay,$thang,$nam)) {
                            $stt = "<strong class='text-danger'>K</strong>";
                       }   
                    }
                    echo "
                    <tr>
                        <td>".$tt."</td>
                        <td>".$row->userDetail->surname."</td>
                        <td class='text-success'>$phepSang</td>
                        <td class='text-success'>$phepSang</td>
                        <td class='text-success'>$phepChieu</td>
                        <td class='text-success'>$phepChieu</td>
                        <td class='text-info'>$gioSang</td>
                        <td class='text-info'>$gioChieu</td>
                        <td class='text-danger'>$treSang</td>
                        <td class='text-danger'>$treChieu</td>
                        <td>$stt</td>
                        <td>$btnTangCa</td>
                    </tr>
                    ";
                }                
                $tt++;
            } else {
                continue;
            }
        }
    }

    // Xem tháng
    public function xemThang($ngay, $thang, $nam){
        $data = "<h4>h4 đây bà con ơi</h4>";
        return view('nhansu.xemthang', ['data' => $data, 'thang' => $thang, 'nam' => $nam]);
    }

    public function tongHopXemThang(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $day = \HelpFunction::countDayInMonth($thang,$nam);
        $user = User::select("*")->where('active', true)->get();     
        $tt = 1;  
        echo "
            <table class='table table-bordered'>
            <tr class='text-center'>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Chấm</th>";

                for ($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                    if(\HelpFunction::isSunday($i,$thang,$nam))
                        echo "<th class='bg-warning'>".$i."</th>";
                    else
                        echo "<th>".$i."</th>";
                }
                echo "<th>TỔNG</th>
                <th>GHI CHÚ</th>
            </tr>";
        echo "<tbody class='text-center'>";
        foreach($user as $row) {
            if ($row->hasRole('chamcong')) {
                $hasXacNhan = "";
                $xacNhan = XacNhanCong::where([
                    ['id_user','=',$row->id],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->exists();

                if ($xacNhan)
                    $hasXacNhan = "<span>ĐXN</span>";

                // Xử lý nhân viên
                echo "
                <tr>
                    <td rowspan='5'>".$tt."</td>
                    <td rowspan='5'>".$row->userDetail->surname."</td>
                </tr>
                <tr>
                    <td>Sáng</td>
                ";                
                $tongCong = 0;
                $tongTre = 0;
                $tongTangCa = 0;
                for($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                    $chiTietCong = ChamCongChiTiet::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    $xinPhep = XinPhep::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    if ($chiTietCong !== null) {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            $tongCong += $xinPhep->gioSang;
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                if ($xinPhep->buoi === "SANG" || $xinPhep->buoi === "CANGAY") 
                                    echo "<td class='bg-warning'>".$xinPhep->loaiPhep->maPhep."</td>";
                                else
                                    echo "<td class='bg-warning'>".$chiTietCong->gioSang."</td>";
                            } else {
                                if ($xinPhep->buoi === "SANG" || $xinPhep->buoi === "CANGAY") {
                                    if ($xinPhep->loaiPhep->maPhep == "H" || $xinPhep->loaiPhep->maPhep == "PN" || $xinPhep->loaiPhep->maPhep == "QCC")
                                        echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
                                    else                                        
                                        echo "<td>".$xinPhep->gioSang."</td>";
                                }
                                else
                                    echo "<td>".$chiTietCong->gioSang."</td>";
                            }
                        } else {
                            $tongCong += $chiTietCong->gioSang;
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'>".$chiTietCong->gioSang."</td>";
                            } else {
                                echo "<td>".$chiTietCong->gioSang."</td>";
                            }
                        }                        
                    } else {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            $tongCong += $xinPhep->gioSang;
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'>".$xinPhep->loaiPhep->maPhep."</td>";
                            } else {
                                echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
                            }
                        } else {
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'></td>";
                            } else {
                                echo "<td></td>";
                            }
                        }
                    }
                }
                for($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                    $chiTietCong = ChamCongChiTiet::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    $xinPhep = XinPhep::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    if ($chiTietCong !== null) {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            $tongCong += $xinPhep->gioChieu;
                        } else {
                            $tongCong += $chiTietCong->gioChieu;
                        }                        
                    } else {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            $tongCong += $xinPhep->gioChieu;
                        }
                    }
                }
            echo "<td rowspan='2'>".round(($tongCong/8),2)."</td>
                <td rowspan='4'>".$hasXacNhan."</td>
                </tr>
                <tr>
                    <td>Chiều</td>
                ";
                
                for($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                    $chiTietCong = ChamCongChiTiet::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    $xinPhep = XinPhep::select("*")
                    ->where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();

                    if ($chiTietCong !== null) {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                if ($xinPhep->buoi === "CHIEU" || $xinPhep->buoi === "CANGAY")
                                    echo "<td class='bg-warning'>".$xinPhep->loaiPhep->maPhep."</td>";
                                else
                                    echo "<td class='bg-warning'>".$chiTietCong->gioChieu."</td>";
                            } else {
                                if ($xinPhep->buoi === "CHIEU" || $xinPhep->buoi === "CANGAY") {
                                    if ($xinPhep->loaiPhep->maPhep == "H" || $xinPhep->loaiPhep->maPhep == "PN" || $xinPhep->loaiPhep->maPhep == "QCC")
                                        echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
                                    else                                        
                                        echo "<td>".$xinPhep->gioSang."</td>";
                                }                                    
                                else
                                    echo "<td>".$chiTietCong->gioChieu."</td>";
                            }
                        } else {
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'>".$chiTietCong->gioChieu."</td>";
                            } else {
                                echo "<td>".$chiTietCong->gioChieu."</td>";
                            }
                        }                        
                    } else {
                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'>".$xinPhep->loaiPhep->maPhep."</td>";
                            } else {
                                echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
                            }
                        } else {
                            if(\HelpFunction::isSunday($i,$thang,$nam)) {
                                echo "<td class='bg-warning'></td>";
                            } else {
                                echo "<td></td>";
                            }
                        }
                    }
                }

            echo "
            </tr>
                <tr>
                <td>Trể</td>
            ";
            for($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                $chiTietCong = ChamCongChiTiet::select("*")
                ->where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$i],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                $xinPhep = XinPhep::select("*")
                ->where([
                    ['id_user','=',$row->id],
                    ['ngay','=',$i],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->first();

                if ($chiTietCong !== null) {
                    if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                        $tongTre += ($xinPhep->treSang + $xinPhep->treChieu);
                        if(\HelpFunction::isSunday($i,$thang,$nam)) {
                            echo "<td class='bg-warning'>".($xinPhep->treSang + $xinPhep->treChieu)."</td>";
                        } else {
                            echo "<td>".($xinPhep->treSang + $xinPhep->treChieu)."</td>";
                        }
                    } else {
                        $tongTre += ($chiTietCong->treSang + $chiTietCong->treChieu);
                        if(\HelpFunction::isSunday($i,$thang,$nam)) {
                            echo "<td class='bg-warning'>".($chiTietCong->treSang + $chiTietCong->treChieu)."</td>";
                        } else {
                            echo "<td>".($chiTietCong->treSang + $chiTietCong->treChieu)."</td>";
                        }
                    }                        
                } else {
                    if(\HelpFunction::isSunday($i,$thang,$nam)) {
                        echo "<td class='bg-warning'></td>";
                    } else {
                        echo "<td></td>";
                    }
                }
            }
            echo "            
                <td>".$tongTre."</td>
            </tr>
            <tr>
                <td>Tăng ca</td>";
                for($i = 1; $i <= \HelpFunction::countDayInMonth($thang,$nam); $i++) {
                    $gioTangCa = null;
                    $tangCa = TangCa::where([
                        ['id_user','=',$row->id],
                        ['ngay','=',$i],
                        ['thang','=',$thang],
                        ['nam','=',$nam]
                    ])->first();
    
                    if ($tangCa !== null) {
                        if ($tangCa->user_duyet == true) {
                            $to_time = strtotime($tangCa->time2);
                            $from_time = strtotime($tangCa->time1);
                            $gioTangCa = round(round(($to_time - $from_time)/60,2)/60,2) * $tangCa->heSo;
                            $tongTangCa += $gioTangCa;
                        }  
                    } 

                    if(\HelpFunction::isSunday($i,$thang,$nam)) {                        
                        echo "<td class='bg-warning'>".$gioTangCa."</td>";
                    } else {
                        echo "<td>".$gioTangCa."</td>";
                    }
                }
             echo "<td>".round(($tongTangCa/8),2)."</td>
             </tr>";
            } else {
                continue;
            }
            $tt++;
        }

        echo "</tbody></table>";
    }

    // Phê duyệt phép
    public function getImport() {
        return view("nhansu.importexcel");
    }

    public function importExcel(Request $request) {      
        $ngay = $request->ngay;
        $thang = $request->thang;
        $nam = $request->nam; 
        // Thời gian mặc định ---------------
        $time_1 = "07:30";
        $time_2 = "11:30";
        $time_3 = "13:00";
        $time_4 = "17:00";
        if($request->hasFile('excel')){
            $theArray = Excel::toArray([], request()->file('excel'));  
            if (strval($theArray[0][0][0]) == "CODE" && strval($theArray[0][0][1]) == "NAME" && 
            strval($theArray[0][0][2]) == "CV" && strval($theArray[0][0][3]) == "GV" && 
            strval($theArray[0][0][4]) == "GR" && strval($theArray[0][0][5]) == "NOTE") {
                $tempcode = "";
                $arrmain = [];
                $arrsub = [];
                $numlen = count($theArray[0]);
                for($i = 1; $i < $numlen; $i++) {
                    if ($theArray[0][$i][5] == "BH") {
                    if ($tempcode != "") {
                        array_push($arrsub,null,null);
                        array_push($arrmain, $arrsub);
                        $arrsub = [];
                        $tempcode = "";
                    }
                    array_push($arrsub, $theArray[0][$i][0], $theArray[0][$i][3], $time_2, $time_3, $theArray[0][$i][4]);
                    array_push($arrmain, $arrsub);
                    $arrsub = [];
                    } else {
                        if ($tempcode != "") {
                            if ($tempcode == $theArray[0][$i][0]) {
                                array_push($arrsub,$theArray[0][$i][3],$theArray[0][$i][4]);
                                array_push($arrmain, $arrsub);
                                $arrsub = [];
                                $tempcode = "";
                            } else {
                                array_push($arrsub,null,null);
                                array_push($arrmain, $arrsub);
                                $arrsub = [];
                                $tempcode = $theArray[0][$i][0];
                                array_push($arrsub,$tempcode,$theArray[0][$i][3],$theArray[0][$i][4]);
                            }
                        } else {
                            $tempcode = $theArray[0][$i][0];
                            array_push($arrsub,$tempcode,$theArray[0][$i][3],$theArray[0][$i][4]);
                        }
                    }
                    
                }               
                // Xử lý chấm công ---------------
                foreach($arrmain as $row) {
                    $gioSang = 0;
                    $gioChieu = 0;
                    $treSang = 0;
                    $treChieu = 0;
                    $user = User::where('name',strtolower($row[0]))->first();
                    if ($user != null) {
                        // Xử lý ca sáng
                        if ($row[1] != null && $row[2] != null) {
                            $to_time = strtotime($row[1]);
                            $from_time = strtotime($time_1);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test > 0)
                                $treSang += $test;
            
                            $to_time = strtotime($row[2]);
                            $from_time = strtotime($time_2);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test < 0)
                                $treSang += abs($test);
            
                            if ($treSang == 0) {
                                $to_time = strtotime($time_2);
                                $from_time = strtotime($time_1);
                                $gioSang = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else {
                                $to_time = strtotime($time_2);
                                $from_time = strtotime($time_1);
                                $gioSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
                            }    
                        }
                        // Xử lý ca chiều
                        if ($row[3] != null && $row[4] != null) {
                            $to_time = strtotime($row[3]);
                            $from_time = strtotime($time_3);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test > 0)
                                $treChieu += $test;
            
                            $to_time = strtotime($row[4]);
                            $from_time = strtotime($time_4);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test < 0)
                                $treChieu += abs($test);
            
                            if ($treChieu == 0) {
                                $to_time = strtotime($time_4);
                                $from_time = strtotime($time_3);
                                $gioChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else {
                                $to_time = strtotime($time_4);
                                $from_time = strtotime($time_3);
                                $gioChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                            }    
                        }

                        $checkChamCong = ChamCongChiTiet::where([
                            ['ngay','=',$ngay],
                            ['thang','=',$thang],
                            ['nam','=',$nam],
                            ['id_user','=',$user->id]
                        ])->exists();

                        if ($checkChamCong) {
                            $chiTiet = ChamCongChiTiet::where([
                                ['ngay','=',$ngay],
                                ['thang','=',$thang],
                                ['nam','=',$nam],
                                ['id_user','=',$user->id]
                            ])
                            ->update([
                                'vaoSang' => $row[1],
                                'raSang' => $row[2],
                                'vaoChieu' => $row[3],
                                'raChieu' => $row[4],
                                'gioSang' => $gioSang,
                                'gioChieu' => $gioChieu,
                                'treSang' => $treSang,
                                'treChieu' => $treChieu,
                            ]);
                        } else {
                            $chiTiet = ChamCongChiTiet::insert([
                                'id_user' => $user->id,
                                'ngay' => $ngay,
                                'thang' => $thang,
                                'nam' => $nam,
                                'vaoSang' => $row[1],
                                'raSang' => $row[2],
                                'vaoChieu' => $row[3],
                                'raChieu' => $row[4],
                                'gioSang' => $gioSang,
                                'gioChieu' => $gioChieu,
                                'treSang' => $treSang,
                                'treChieu' => $treChieu,
                            ]);
                        }  
                    }
                }   
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý chấm công - Import excel";
                $nhatKy->noiDung = "Import excel file chấm công vào hệ thống";
                $nhatKy->save();
                return back()->with('success','Đã cập nhật chấm công! Vào chấm công chi tiết để kiểm tra!');
            }
		}
		return back()->with('error','Không tìm thấy file theo yêu cầu!!!');
    }


    // Phê duyệt tăng ca
    public function getTangCaPanel() {        
        return view("nhansu.tangca");
    }

    public function pheDuyetTangCaGetList() {
        if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('hcns'))
            $tangCa = TangCa::select("tang_ca.heSo","tang_ca.time2","tang_ca.time1","tang_ca.created_at","tang_ca.updated_at","tang_ca.id_user_duyet","tang_ca.id","tang_ca.ngay","tang_ca.thang","tang_ca.nam","tang_ca.lyDo","tang_ca.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin")
            ->join('users as u','u.id','=','tang_ca.id_user')
            ->join('users_detail as d','d.id_user','=','u.id')
            ->join('users as un','un.id','=','tang_ca.id_user_duyet')
            ->join('users_detail as dn','dn.id_user','=','un.id')
            ->where([
                ['tang_ca.id_user_duyet', '=', Auth::user()->id]
            ])
            ->orderby('user_duyet','asc')
            ->get();
        else 
            $tangCa = TangCa::select("tang_ca.heSo","tang_ca.time2","tang_ca.time1","tang_ca.created_at","tang_ca.updated_at","tang_ca.id_user_duyet","tang_ca.id","tang_ca.ngay","tang_ca.thang","tang_ca.nam","tang_ca.lyDo","tang_ca.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin")
            ->join('users as u','u.id','=','tang_ca.id_user')
            ->join('users_detail as d','d.id_user','=','u.id')
            ->join('users as un','un.id','=','tang_ca.id_user_duyet')
            ->join('users_detail as dn','dn.id_user','=','un.id')
            ->orderby('user_duyet','desc')
            ->get();    

        if ($tangCa)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã tải dữ liệu",
                "data" => $tangCa
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);
    }

    public function pheDuyetTangCa(Request $request) {
        $check = TangCa::where('id',$request->id)->first();
        $ngay = $check->ngay . "/" . $check->thang . "/" . $check->nam;
        $nhanvien = $check->user->userDetail->surname;
        if (Auth::user()->hasRole('system')) {
            $tangCa = TangCa::where('id',$request->id)->update([
                'user_duyet' => true
            ]);
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Phê duyệt tăng ca ngày " . $ngay . "<br/>Nhân viên yêu cầu: " . $nhanvien;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã phê duyệt tăng ca"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Không thể phê duyệt tăng ca"
                ]);
        } else {
            if ($check->id_user_duyet != Auth::user()->id) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Bạn không có quyền phê duyệt tăng ca này"
                ]);
            } else {
                $tangCa = TangCa::where('id',$request->id)->update([
                    'user_duyet' => true
                ]);
                if ($tangCa) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                    $nhatKy->noiDung = "Phê duyệt tăng ca ngày " . $ngay . "<br/>Nhân viên yêu cầu: " . $nhanvien;
                    $nhatKy->save();
                    return response()->json([
                        "type" => "info",
                        "code" => 200,
                        "message" => "Đã phê duyệt tăng ca"
                    ]);
                }
                else
                    return response()->json([
                        "type" => "info",
                        "code" => 500,
                        "message" => "Không thể phê duyệt tăng ca"
                    ]);
            }    
        }      
    }

    public function capNhatTangCa(Request $request) {
        if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('hcns')) {
            return response()->json([
                "type" => "errpr",
                "code" => 500,
                "message" => "Bạn không có quyền thao tác trên chức năng này"
            ]);
        } else {
            $check = TangCa::where('id',$request->idTangCa)->first();
            $ngay = $check->ngay . "/" . $check->thang . "/" . $check->nam;
            $nhanvien = $check->user->userDetail->surname;
            $tangCa = TangCa::where('id',$request->idTangCa)->update([
                'time1' => $request->gioVao,
                'time2' => $request->gioRa,
                'heSo' => $request->heSo
            ]);
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Cập nhật giờ công ".$request->gioVao." ".$request->gioRa." và hệ số tăng ca ".$request->heSo." ngày " . $ngay . " của nhân viên " . $nhanvien;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã cập nhật giờ công tăng ca"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Không thể cập nhật giờ công"
                ]);
        }        
    }

    public function tangCaDelete(Request $request) {
        $check = TangCa::where('id',$request->id)->first();
        $ngay = $check->ngay . "/" . $check->thang . "/" . $check->nam;
        $nhanvien = $check->user->userDetail->surname;
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $tangCa = TangCa::find($request->id);
            $tangCa->delete();
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Không duyệt và xóa tăng ca ngày " . $ngay . " của nhân viên " . $nhanvien;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã xóa tăng ca"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi xóa dữ liệu"
                ]);
        } else {
            if ($check->id_user_duyet != Auth::user()->id) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Bạn không có quyền xóa phiếu này"
                ]);
            } else {
                $tangCa = TangCa::find($request->id);
                $ngay = $tangCa->ngay . "/" . $tangCa->thang . "/" . $tangCa->nam;
                $nhanvien = $tangCa->user->userDetail->surname;
                $tangCa->delete();
                if ($tangCa) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                    $nhatKy->noiDung = "Không duyệt và xóa tăng ca ngày " . $ngay . " của nhân viên " . $nhanvien;
                    $nhatKy->save();
                    return response()->json([
                        "type" => "info",
                        "code" => 200,
                        "message" => "Đã xóa tăng ca"
                    ]);
                }
                else
                    return response()->json([
                        "type" => "info",
                        "code" => 500,
                        "message" => "Lỗi xóa dữ liệu"
                    ]);
            }
        }        
    }

    public function tangCaDeleteAdmin(Request $request) {
        $check = TangCa::where('id',$request->id)->first();
        $ngay = $check->ngay . "/" . $check->thang . "/" . $check->nam;
        $nhanvien = $check->user->userDetail->surname;
        if (Auth::user()->hasRole('system')) {
            $tangCa = TangCa::find($request->id);
            $tangCa->delete();
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Admin đã xóa tăng ca (đã duyệt) " . $ngay . " của nhân viên " . $nhanvien;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã xóa tăng ca"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi xóa dữ liệu"
                ]);
        } else {
            return response()->json([
                "type" => "error",
                "code" => 500,
                "message" => "Phiếu đã duyệt. Bạn không có quyền xóa phiếu này"
            ]);
        }        
    }

    public function getPhepNam($id, $nam){
        $user = User::find($id);
        //--- phep thuc te
        $current = "1-1-".Date('Y');            
        $phepNamThucTe = 0;
        if ($user->allowPhepNam == true && $user->ngay !== null) {
            $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
            if (strtotime($thangPhepNam) >= strtotime($current))
                $phepNamThucTe = (int)Date('m') - (int)$user->thang + 1; 
            else 
                $phepNamThucTe = (int)Date('m');                     
        }    
        
        $getIdPhep = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
        $check = XinPhep::where([
            ['id_user','=',$id],
            ['id_phep','=',$getIdPhep],
            ['user_duyet','=', true],
            ['nam','=',$nam]
        ])->get();

        $daSuDung = 0;

        foreach($check as $row) {
            if ($row->buoi == "SANG") {
                $daSuDung += 0.5;
            }
            if ($row->buoi == "CHIEU") {
                $daSuDung += 0.5;
            }
            if ($row->buoi == "CANGAY") {
                $daSuDung += 1;
            }
        }

        return response()->json([
            'type' => 'info',
            'code' => 200,
            'message' => 'success',
            'conlai' => $phepNamThucTe,
            'dasudung' => $daSuDung
        ]);
    }

    // Xác nhận công
    public function getChotCong(){
        return view('nhansu.xacnhancong');
    }

    public function chotCong(Request $request) {
        $ngaycong = session('engaycong');
        $tangca = session('etangca');
        $phepnam = session('ephepnam');
        if ((int)$ngaycong == (int)$request->ngayCong && (int)$tangca == (int)$request->tangCa && (int)$phepnam == (int)$request->phepNam) {
            $xacNhan = new XacNhanCong();
            $xacNhan->thang = $request->thang;
            $xacNhan->nam = $request->nam;
            $xacNhan->id_user = $request->id;
            $xacNhan->phepNam = $request->phepNam;
            $xacNhan->ngayCong = $request->ngayCong;
            $xacNhan->tangCa = $request->tangCa;
            $xacNhan->tongTre = $request->tongTre;
            $xacNhan->khongPhep = $request->khongPhep;
            $xacNhan->khongPhepNgay = $request->khongPhepCaNgay;
            $xacNhan->save();
            if ($xacNhan) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Chi tiết chấm công";
                $nhatKy->noiDung = "Chốt chấm công tháng ".$request->thang." năm " . $request->nam;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã chốt chấm công"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi chốt chấm công"
                ]);
        } else {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Chi tiết chấm công";
            $nhatKy->noiDung = "[NGUY HIỂM] Thực hiện tác động kỹ thuật vào kết quả chấm công. Hệ thống đã ngăn chặn!!! Ngày công: $ngaycong Tăng ca: $tangca Phép năm: $phepnam Hacking: " . $request->ngayCong . " " . $request->tangCa . " " . $request->phepNam;
            $nhatKy->save();
            return response()->json([
                "type" => "error",
                "code" => 500,
                "message" => "Bạn đã thực hiện kỹ thuật tác động đến dữ liệu chấm công. Báo cáo đã gửi về administrator"
            ]);
        }       
    }

    public function chiTietChotCong(Request $request) {
        $user = User::select("*")->where('active', true)->get();
        $tong = 0;
        $chuaXacNhan = 0;
        foreach($user as $row) {
            if ($row->hasRole('chamcong')) {
                $tong++;
                $xacNhan = XacNhanCong::where([
                    ['id_user','=', $row->id],
                    ['thang','=', $request->thang],
                    ['nam','=', $request->nam],
                ])->first();
                $stt = "";
                if ($xacNhan !== null && $xacNhan->count() > 0) 
                    $stt = "<span class='text-info'><strong>Đã xác nhận</strong></span>";
                else {
                    $chuaXacNhan++;
                    $stt = "<span class='text-danger'><strong>Chưa xác nhận</strong></span>";
                }     
                if ($xacNhan !== null && $xacNhan->count() > 0) {
                    echo "
                        <tr>
                            <td>".$row->userDetail->surname."</td>
                            <td class='text-success'>".$xacNhan->ngayCong." (ngày)</td>
                            <td class='text-success'>".$xacNhan->phepNam." (ngày)</td>
                            <td class='text-info'>".$xacNhan->tangCa." (ngày)</td>
                            <td><strong style='color: pink;'>".$xacNhan->tongTre."</strong> (phút)</td>
                            <td class='text-danger'>".($xacNhan->khongPhep >= 0 ? $xacNhan->khongPhep : 0)."</td>
                            <td class='text-danger'><strong>".$xacNhan->khongPhepNgay."</strong></td>
                            <td>$stt</td>
                            <td>
                                <button id='huy' data-id='".$xacNhan->id_user."' data-thang='".$xacNhan->thang."' data-nam='".$xacNhan->nam."' class='btn btn-danger btn-sm'>Hủy</button>
                            </td>
                        </tr>
                    ";
                } else {
                    echo "
                        <tr>
                            <td>".$row->userDetail->surname."</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>$stt</td>
                            <td></td>
                        </tr>
                    ";
                }    
            }
        }
        echo "
            <tr>
                <td><strong>Tổng nhân viên</strong></td>
                <td class='text-success'><strong>".$tong."</strong></td>
                <td><strong>Chưa xác nhận</strong></td>
                <td class='text-danger'><strong>".$chuaXacNhan."</strong></td>
                <td><strong>Đã xác nhận</strong></td>
                <td class='text-info'><strong>".($tong - $chuaXacNhan)."</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        ";
    }

    public function huyChotCong(Request $request) {
        $xacNhan = XacNhanCong::where([
            ['id_user','=', $request->id],
            ['thang','=', $request->thang],
            ['nam','=', $request->nam],
        ])->delete();
        if ($xacNhan) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Hủy chốt chấm công " . $request->thang . "/" . $request->nam;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã hủy chốt chấm công"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi hủy chốt chấm công"
            ]);
    }

    public function xacNhanAll(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $day = \HelpFunction::countDayInMonth($thang,$nam);
        $user = User::all();
        $flag = false;
        foreach($user as $row) {
            if ($row->hasRole('chamcong') && $row->active == true) {
                $check = XacNhanCong::where([
                    ['id_user','=',$row->id],
                    ['thang','=',$thang],
                    ['nam','=',$nam],
                ])->first();
                if ($check !== null && $check->count() > 0) {
                    $flag = true;
                } else {                    
                    $tongCong = 0;
                    $tongTre = 0;
                    $khongPhep = $day;
                    $khongPhepCaNgay = 0;
                    $lamThem = 0;
                    $phepNam = 0;  
                    for($i = 1; $i <= $day; $i++) {
                        $tangCaGioHanhChinh = 0; 

                        $chiTiet = ChamCongChiTiet::select("*")
                        ->where([
                            ['id_user','=',$row->id],
                            ['ngay','=',$i],
                            ['thang','=',$thang],
                            ['nam','=',$nam]
                        ])->first();
                        
                        $xinPhep = XinPhep::select("*")
                        ->where([
                            ['id_user','=',$row->id],
                            ['ngay','=',$i],
                            ['thang','=',$thang],
                            ['nam','=',$nam]
                        ])->first();
            
                        $tangCa = TangCa::select("*")
                        ->where([
                            ['id_user','=',$row->id],
                            ['ngay','=',$i],
                            ['thang','=',$thang],
                            ['nam','=',$nam]
                        ])->first();

                        $quanLy = QuanLyTangCa::select("*")
                        ->where([
                            ['id_user','=',$row->id],
                            ['ngay','=',$i],
                            ['thang','=',$thang],
                            ['nam','=',$nam]
                        ])->first();
            
                        $xacNhan = XacNhanCong::where([
                            ['id_user','=',$row->id],
                            ['thang','=',$thang],
                            ['nam','=',$nam]
                        ])->exists();
                       
                        //Xử lý không phép
                        if ($chiTiet !== null && (($chiTiet->gioSang + $chiTiet->gioChieu) == 8)) {
                            $khongPhep -= 1;
                        } elseif ($xinPhep != null && ($xinPhep->treSang == 0 && $xinPhep->treChieu == 0)) {
                            $khongPhep -= 1;
                        }

                        if ($chiTiet === null && !\HelpFunction::isSunday($i,$thang,$nam)) {
                            if ($xinPhep === null)
                                $khongPhepCaNgay += 1;
                            elseif ($xinPhep->user_duyet == false)
                                $khongPhepCaNgay += 1;
                        }
            

                        if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                            if ($xinPhep->loaiPhep->loaiPhep == "PHEPNAM") {
                                switch($xinPhep->buoi) {
                                    case 'SANG': $phepNam += 0.5; break;
                                    case 'CHIEU': $phepNam += 0.5; break;
                                    case 'CANGAY': $phepNam += 1; break;
                                }
                            }
                        }  

                        if ($tangCa !== null) {
                            if ($tangCa->user_duyet == true) {
                                $to_time = strtotime($tangCa->time2);
                                $from_time = strtotime($tangCa->time1);
                                $gioTangCa = round(round(($to_time - $from_time)/60,2)/60,2) * $tangCa->heSo;
                                $lamThem += $gioTangCa;
                            } 
                        }             

                        if($chiTiet !== null && $chiTiet->ngay == $i 
                        && $chiTiet->thang == $thang && $chiTiet->nam == $nam) {
                            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                                $tongTre += ($xinPhep->treSang + $xinPhep->treChieu);
                                // $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                                if ($quanLy !== null) {
                                } else
                                    $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                            } else {
                                $tongTre += ($chiTiet->treSang + $chiTiet->treChieu);
                                // $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
                                if ($quanLy !== null) {
                                } else 
                                    $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
                            }        
                            
                            if ($quanLy !== null) {
                                if ($xinPhep !== null && $xinPhep->user_duyet == true)
                                    $tangCaGioHanhChinh = (($xinPhep->gioSang + $xinPhep->gioChieu) * $quanLy->heSo);
                                else {
                                    $tangCaGioHanhChinh = (($chiTiet->gioSang + $chiTiet->gioChieu)* $quanLy->heSo);
                                }             
                                $lamThem += $tangCaGioHanhChinh;   
                            } 
                        }
                        else {
                            if (!\HelpFunction::isSunday($i,$thang,$nam)) {                              
                            } else {
                                $khongPhep -= 1;
                            }               
                            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                                $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                            }  
                        }                
                    }

                    // Chốt công                    
                    $xacNhan = new XacNhanCong();
                    $xacNhan->thang = $thang;
                    $xacNhan->nam = $nam;
                    $xacNhan->id_user = $row->id;
                    $xacNhan->phepNam = $phepNam;
                    $xacNhan->ngayCong = round(($tongCong/8),2) - $phepNam;
                    $xacNhan->tangCa = round(($lamThem/8),2);
                    $xacNhan->tongTre = $tongTre;
                    $xacNhan->khongPhep = $khongPhep - $khongPhepCaNgay;
                    $xacNhan->khongPhepNgay = $khongPhepCaNgay;
                    $xacNhan->save();
                    $flag = true;
                }
            }
        }

        if ($flag) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Admin chốt tất cả chấm công tháng " . $thang . "/" . $nam;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã chốt tất cả chấm công"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi chốt tất cả chấm công"
            ]);
    }

    public function huyAll(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $user = User::all();  
        $flag = false;      
        foreach($user as $row) {
            if ($row->hasRole('chamcong') && $row->active == true) {
                $xacNhan = XacNhanCong::where([
                    ['id_user','=', $row->id],
                    ['thang','=', $thang],
                    ['nam','=', $nam],
                ])->delete();
                $flag = true;
            }
        }

        if ($flag) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Admin hủy chốt tất cả chấm công tháng " . $thang . "/" . $nam;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã hủy chốt tất cả chấm công"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi hủy chốt tất cả chấm công"
            ]);
    }

    public function getQuanLyPheDuyet() {
        return view('nhansu.viewphep');
    }

    public function quanLyPheDuyetGetList() {
            $xinPhep = XinPhep::select("xin_phep.buoi","xin_phep.created_at","xin_phep.updated_at","xin_phep.id_user_duyet","xin_phep.id","xin_phep.ngay","xin_phep.thang","xin_phep.nam","xin_phep.lyDo","xin_phep.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin","p.tenPhep as loaiphep")
            ->join('users as u','u.id','=','xin_phep.id_user')
            ->join('users_detail as d','d.id_user','=','u.id')
            ->join('users as un','un.id','=','xin_phep.id_user_duyet')
            ->join('users_detail as dn','dn.id_user','=','un.id')
            ->join('loai_phep as p','p.id','=','xin_phep.id_phep')
            ->where([
                ['duyet','=', false],
                ['user_duyet','=', true]
            ])
            ->orderby('user_duyet','asc')
            ->get();    
    
            if ($xinPhep)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã tải dữ liệu",
                    "data" => $xinPhep
                ]);
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi tải dữ liệu"
                ]);        
    }

    public function quanLyPheDuyetSeen(Request $request) {
        $xinPhep = XinPhep::find($request->id);
        $ngay = $xinPhep->ngay . "/" . $xinPhep->thang . "/" . $xinPhep->nam;
        $nhanvien = $xinPhep->user->userDetail->surname;
        $xinPhep->duyet = true;
        $xinPhep->save();
        if ($xinPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý phê duyệt phép trưởng bộ phận";
            $nhatKy->noiDung = "Admin đã xem và kiểm tra duyệt phép của trưởng bộ phận<br/>Duyệt ngày: ".$ngay."<br/>Cho nhân viên: " . $nhanvien;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xem",
                "data" => $xinPhep
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thao tác"
            ]);    
    }

    public function getQuanLyPheDuyetTangCa() {
        return view('nhansu.viewtangca');
    }

    public function quanLyPheDuyetTangCaGetList() {
        $tangCa = TangCa::select("tang_ca.heSo","tang_ca.time2","tang_ca.time1","tang_ca.created_at","tang_ca.updated_at","tang_ca.id_user_duyet","tang_ca.id","tang_ca.ngay","tang_ca.thang","tang_ca.nam","tang_ca.lyDo","tang_ca.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin")
        ->join('users as u','u.id','=','tang_ca.id_user')
        ->join('users_detail as d','d.id_user','=','u.id')
        ->join('users as un','un.id','=','tang_ca.id_user_duyet')
        ->join('users_detail as dn','dn.id_user','=','un.id')
        ->where([
            ['duyet','=', false],
            ['user_duyet','=', true]
        ])
        ->orderby('user_duyet','desc')
        ->orderBy('id', 'desc')
        ->get();   

        if ($tangCa)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã tải dữ liệu",
                "data" => $tangCa
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);        
    }

    public function quanLyPheDuyetTangCaSeen(Request $request) {
        $tangCa = TangCa::find($request->id);
        $ngay = $tangCa->ngay . "/" . $tangCa->thang . "/" . $tangCa->nam;
        $nhanvien = $tangCa->user->userDetail->surname;
        $tangCa->duyet = true;
        $tangCa->save();
        if ($tangCa) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý phê duyệt tăng ca trưởng bộ phận";
            $nhatKy->noiDung = "Admin đã xem và kiểm tra phê duyệt tăng ca của trưởng bộ phận<br/>Ngày phê duyệt: ".$ngay."<br/>Cho nhân viên: " . $nhanvien;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xem"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thao tác"
            ]);    
    }

    // Quản lý tăng ca/ngày lễ
    public function getQuanLyTangCaLe() {
        $user = User::select("*")->where('active', true)->get();
        return view('nhansu.quanlytangcale', ['user' => $user]);
    }

    public function getQuanLyTangCa(Request $request) {
        $quanLy = QuanLyTangCa::where([
            ['ngay','=',$request->ngay],
            ['thang','=',$request->thang],
            ['nam','=',$request->nam],
        ])->get();        
        $ngay = $request->ngay;
        $thang = $request->thang;
        $nam = $request->nam;
        $thuMay = "";
        $specDate = $nam."-".$thang."-".$ngay;
        $ngayThu = array('Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4','Thứ 5','Thứ 6', 'Thứ 7');
        $numThu =  date('w', strtotime($specDate));
        switch((int)$numThu){
            case 0: $thuMay = "Chủ nhật"; break;
            case 1: $thuMay = "Thứ 2"; break;
            case 2: $thuMay = "Thứ 3"; break;
            case 3: $thuMay = "Thứ 4"; break;
            case 4: $thuMay = "Thứ 5"; break;
            case 5: $thuMay = "Thứ 6"; break;
            case 6: $thuMay = "Thứ 7"; break;
        }
        if ($quanLy !== null) {
            foreach($quanLy as $row) {
                $vaoSang = null;
                $raSang = null;
                $vaoChieu = null;
                $raChieu = null;
                $congSang = 0;
                $congChieu = 0;
                $idXinPhep = 0;
                $loaiGioCong = "Giờ quy định";

                //-------------
                $xinPhep = XinPhep::where([
                    ['id_user','=',$row->id_user],
                    ['ngay','=',$request->ngay],
                    ['thang','=',$request->thang],
                    ['nam','=',$request->nam],
                ])->first();

                if ($xinPhep !== null) {
                    $idXinPhep = $xinPhep->id;    
                    if ($xinPhep->loaiPhep->maPhep == 'LT')
                        $loaiGioCong = "<strong>Giờ thực tế</strong>";
                }
                //-------------  

                $chiTiet = ChamCongChiTiet::where([
                    ['ngay','=',$ngay],
                    ['thang','=',$thang],
                    ['nam','=',$nam],
                    ['id_user','=',$row->id_user],
                ])->first();
                if ($chiTiet !== null) {
                    $vaoSang = $chiTiet->vaoSang;
                    $raSang = $chiTiet->raSang;
                    $vaoChieu = $chiTiet->vaoChieu;
                    $raChieu = $chiTiet->raChieu;
                    $congSang = $chiTiet->gioSang;
                    $congChieu = $chiTiet->gioChieu;
                }
                $tongCong = round((($congChieu + $congSang) * $row->heSo)/8,2);
                echo "<tr>
                    <td>".($ngay . "/" . $thang . "/".$nam)." <br/> ".$thuMay."</td>
                    <td>".$row->user->userDetail->surname."</td>
                    <td style='color:blue;'><strong>".$row->heSo."</strong></td>                    
                    <td>".$loaiGioCong."</td>
                    <td class='text-success'>".$vaoSang."</td>
                    <td class='text-success'>".$raSang."</td>
                    <td class='text-success'>".$vaoChieu."</td>
                    <td class='text-success'>".$raChieu."</td>
                    <td class='text-info'>".$congSang." (giờ)</td>
                    <td class='text-info'>".$congChieu." (giờ)</td>
                    <td><strong><span class='text-secondary'>".round(($congSang + $congChieu)/8,2)." (ngày)</span><br/><span class='text-primary'>".$tongCong." (ngày)</span></strong></td>
                    <td><button id='del' data-idphep='".$idXinPhep."' data-idquanly='".$row->id."' class='btn btn-danger btn-sm'>Xóa</button></td>
                </tr>";
            }            
        } 
    }

    public function quanLyTangCaPost(Request $request) {
        $check = QuanLyTangCa::where([
            ['ngay','=',$request->ngay],
            ['thang','=',$request->thang],
            ['nam','=',$request->nam],
            ['id_user','=',$request->nhanVien]
        ])->exists();
        $ngay = $request->ngay . "/" . $request->thang . "/" . $request->nam;
        $user = User::find($request->nhanVien);
        $ten = $user->userDetail->surname;
        if ($check) {
            return response()->json([
                "type" => "warning",
                "code" => 200,
                "message" => "Đã thêm nhân viên này rồi!"
            ]);
        } else {
            $quanLy = new QuanLyTangCa();
            $quanLy->ngay = $request->ngay;
            $quanLy->thang = $request->thang;
            $quanLy->nam = $request->nam;
            $quanLy->id_user = $request->nhanVien;
            $quanLy->heSo = $request->heSo;
            $quanLy->save();

            if ($request->loaiGioCong == 1) {
                $getIdPhep = LoaiPhep::where('maPhep','LT')->first()->id;
                $xinPhep = new XinPhep();
                $xinPhep->id_user = $request->nhanVien;
                $xinPhep->id_phep = $getIdPhep;
                $xinPhep->ngay = $request->ngay;
                $xinPhep->thang = $request->thang;
                $xinPhep->nam = $request->nam;

                $ngay = $request->ngay . "/" . $request->thang. "/" . $request->nam;
                $user = User::find($request->nhanVien);
                $ten = $user->userDetail->surname;

                $xinPhep->buoi = "CANGAY";
                $xinPhep->id_user_duyet = Auth::user()->id;
                $xinPhep->user_duyet = true;
                $xinPhep->lyDo = "Hệ thống tự động bổ sung theo nghiệp vụ quản lý tăng ca, làm thêm";                
                $xinPhep->gioSang = 0;
                $xinPhep->gioChieu = 0;
                $xinPhep->treSang = 0;
                $xinPhep->treChieu= 0;
                $xinPhep->vaoSang = "LT";
                $xinPhep->raSang = "LT";
                $xinPhep->save();
                if ($xinPhep)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã thêm"
                ]);
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi thao tác"
                ]);    
            }

            if ($quanLy) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - Quản lý tăng ca";
                $nhatKy->noiDung = "Thêm tăng ca và hệ số ".$request->heSo." (ban ngày) ngày tăng ca ".$ngay." cho nhân viên " . $ten;
                $nhatKy->save();
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã thêm"
                ]);
            }
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi thao tác"
                ]);    
        }
    }

    public function xoaNhanVienTangCa(Request $request) {
        $quanLy = QuanLyTangCa::find($request->id);
        $quanLy->delete();

        if ($request->idPhep != 0) {
            $xinPhep = XinPhep::find($request->idPhep);
            $xinPhep->delete();
        }    

        if ($quanLy) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - quản lý tăng ca";
            $nhatKy->noiDung = "Xóa nhân viên tăng ca";
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thao tác"
            ]);    
    }

    public function themAllNhanVienTangCa(Request $request) {
        $idPhepCoLuong = LoaiPhep::where('maPhep','L')->first();      
        $idPhepKhongLuong = LoaiPhep::where('maPhep','LKL')->first();         
        $user = User::select("*")->where('active', true)->get();
        $type = $request->loaiPhep;
        $ngay = $request->ngay;
        $thang = $request->thang;
        $nam = $request->nam;
        foreach($user as $row) { 
            $chiTiet = ChamCongChiTiet::select("*")
            ->where([
                ['id_user','=',$row->id],
                ['ngay','=',$ngay],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->exists();

            $check = XinPhep::select("*")
            ->where([
                ['id_user','=',$row->id],
                ['ngay','=',$ngay],
                ['thang','=',$thang],
                ['nam','=',$nam]
            ])->exists();

            if ($row->hasRole('chamcong') && !$chiTiet && !$check) {
                if ($type == 0) {
                    $xinPhep = new XinPhep();
                    $xinPhep->id_user = $row->id;
                    $xinPhep->id_phep = $idPhepKhongLuong->id;
                    $xinPhep->ngay = $ngay;
                    $xinPhep->thang = $thang;
                    $xinPhep->nam = $nam;
                    $xinPhep->buoi = "CANGAY";
                    $xinPhep->id_user_duyet = Auth::user()->id;
                    $xinPhep->lyDo = "Tự động cập nhật từ hệ thống";
                    $xinPhep->user_duyet = true;
                    $xinPhep->duyet = true;
                    $xinPhep->gioSang =0;
                    $xinPhep->gioChieu = 0;
                    $xinPhep->treSang = 0;
                    $xinPhep->treChieu= 0;
                    $xinPhep->vaoSang = $idPhepKhongLuong->maPhep;   
                    $xinPhep->vaoChieu = $idPhepKhongLuong->maPhep;  
                    $xinPhep->save();                    
                } else {
                    $xinPhep = new XinPhep();
                    $xinPhep->id_user = $row->id;
                    $xinPhep->id_phep = $idPhepCoLuong->id;
                    $xinPhep->ngay = $ngay;
                    $xinPhep->thang = $thang;
                    $xinPhep->nam = $nam;
                    $xinPhep->buoi = "CANGAY";
                    $xinPhep->id_user_duyet = Auth::user()->id;
                    $xinPhep->lyDo = "Tự động cập nhật từ hệ thống";
                    $xinPhep->user_duyet = true;
                    $xinPhep->duyet = true;
                    $xinPhep->gioSang = 4;
                    $xinPhep->gioChieu = 4;
                    $xinPhep->treSang = 0;
                    $xinPhep->treChieu= 0;
                    $xinPhep->vaoSang = $idPhepCoLuong->maPhep;   
                    $xinPhep->vaoChieu = $idPhepCoLuong->maPhep;  
                    $xinPhep->save();                
                }    
            }
        } 
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Nhân sự - Quản lý phép - Quản lý tăng ca";
        $nhatKy->noiDung = "Thêm phép hàng loạt ngày " . $ngay . "/" . $thang . "/" . $nam;
        $nhatKy->save();
        return response()->json([
            "type" => "info",
            "code" => 200,
            "message" => "Đã cập nhật hàng loạt. Vào mục tổng hợp công để kiểm tra!"
        ]);            
    }

    public function huyAllNhanVienTangCa(Request $request) {
        $xinPhep = XinPhep::where([
            ['ngay','=',$request->ngay],
            ['thang','=',$request->thang],
            ['nam','=',$request->nam],
        ])->delete();
        if ($xinPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý phép - Quản lý tăng ca";
            $nhatKy->noiDung = "Hủy phép hàng loạt ngày " . $request->ngay . "/" . $request->thang . "/" . $request->nam;
            $nhatKy->save();
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa tất cả phép của nhân viên trong ngày được chọn. Vào mục tổng hợp công để kiểm tra!"
            ]);
        }
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thao tác"
            ]);    
    }

    public function dongBoPhep(Request $request) {
        $thang = $request->thang;
        $nam = $request->nam;
        $user = User::all();
        foreach($user as $row) {
            if ($row->hasRole('chamcong') && $row->active == true) {
                $tongPhep = XinPhep::where([
                    ['id_user','=',$row->id],
                    ['thang','=',$thang],
                    ['nam','=',$nam]
                ])->get();
                foreach($tongPhep as $phep) {
                    $chiTiet = ChamCongChiTiet::select("*")
                    ->where([
                        ['id_user','=',$phep->id_user],
                        ['ngay','=',$phep->ngay],
                        ['thang','=',$phep->thang],
                        ['nam','=',$phep->nam]
                    ])
                    ->first();

                    if ($chiTiet !== null) {
                        $gioSang = $chiTiet->gioSang;
                        $gioChieu = $chiTiet->gioChieu;
                        $treSang = $chiTiet->treSang;
                        $treChieu = $chiTiet->treChieu;
                        $vaoSang = "";
                        $raSang = "";
                        $vaoChieu = "";
                        $raChieu = ""; 
            
                        $loaiPhep = LoaiPhep::find($phep->id_phep);
                        switch ($loaiPhep->loaiPhep) {
                            case 'PHEPNAM':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 4;
                                            $treSang = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 4;
                                            $treChieu = 0;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        case 'CANGAY': {
                                            $gioSang = 4;
                                            $gioChieu = 4;
                                            $treSang = 0;
                                            $treChieu = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'QCC':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 4;
                                            $treSang = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 4;
                                            $treChieu = 0;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'COLUONG':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 4;
                                            $treSang = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 4;
                                            $treChieu = 0;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        case 'CANGAY': {
                                            $gioSang = 4;
                                            $gioChieu = 4;
                                            $treSang = 0;
                                            $treChieu = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'KHONGLUONG':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $treSang = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                        } break;
                                        case 'CHIEU': {
                                            $treChieu = 0;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        case 'CANGAY': {
                                            $treSang = 0;
                                            $treChieu = 0;
                                            $vaoSang = $loaiPhep->maPhep;
                                            $vaoChieu = $loaiPhep->maPhep;
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            default: break;
                        }
                        
                        $xinPhep = XinPhep::find($phep->id);
                        $xinPhep->gioSang = $gioSang;
                        $xinPhep->gioChieu = $gioChieu;
                        $xinPhep->treSang = $treSang;
                        $xinPhep->treChieu= $treChieu;
                        $xinPhep->vaoSang = $vaoSang;
                        $xinPhep->raSang = $raSang;
                        $xinPhep->vaoChieu = $vaoChieu;
                        $xinPhep->raChieu= $raChieu;
                        $xinPhep->save();
                    } else {
                        $gioSang = 0;
                        $gioChieu = 0;
                        $vaoSang = "";
                        $vaoChieu = "";
                        $loaiPhep = LoaiPhep::find($phep->id_phep);
                        switch ($loaiPhep->loaiPhep) {
                            case 'PHEPNAM':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 4;    
                                            $vaoSang = $loaiPhep->maPhep;                           
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 4;    
                                            $vaoChieu = $loaiPhep->maPhep;                            
                                        } break;
                                        case 'CANGAY': {
                                            $gioSang = 4;
                                            $gioChieu = 4;  
                                            $vaoSang = $loaiPhep->maPhep;   
                                            $vaoChieu = $loaiPhep->maPhep;                              
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'COLUONG':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 4;     
                                            $vaoSang = $loaiPhep->maPhep;                                  
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 4;       
                                            $vaoChieu = $loaiPhep->maPhep;                                  
                                        } break;
                                        case 'CANGAY': {
                                            $gioSang = 4;
                                            $gioChieu = 4;     
                                            $vaoSang = $loaiPhep->maPhep;   
                                            $vaoChieu = $loaiPhep->maPhep;                                
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            case 'KHONGLUONG':
                                {
                                    switch($phep->buoi){
                                        case 'SANG': {
                                            $gioSang = 0;   
                                            $vaoSang = $loaiPhep->maPhep;                                   
                                        } break;
                                        case 'CHIEU': {
                                            $gioChieu = 0;     
                                            $vaoChieu = $loaiPhep->maPhep;                                   
                                        } break;
                                        case 'CANGAY': {
                                            $gioSang = 0;
                                            $gioChieu = 0;    
                                            $vaoSang = $loaiPhep->maPhep;   
                                            $vaoChieu = $loaiPhep->maPhep;                                   
                                        } break;
                                        default: break;
                                    }
                                }
                                break;
                            default: break;
                        }

                        $xinPhep = XinPhep::find($phep->id);
                        $xinPhep->gioSang = $gioSang;
                        $xinPhep->gioChieu = $gioChieu;
                        $xinPhep->treSang = 0;
                        $xinPhep->treChieu= 0;
                        $xinPhep->vaoSang = $vaoSang;
                        $xinPhep->vaoChieu = $vaoChieu;
                        $xinPhep->save();
                    }
                }
            }
        }    
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Nhân sự - Quản lý phép";
        $nhatKy->noiDung = "Thực thiện đồng bộ phép";
        $nhatKy->save();
        return response()->json([
            "type" => "info",
            "code" => 200,
            "message" => "Đã đồng bộ hóa phép. Vào tổng hợp công hoặc chấm công chi tiết để kiểm tra"
        ]);
    }

    public function getBaoCaoPhepNam() {
        return view('nhansu.baocaophepnam');
    }

    // public function loadBaoCaoPhepNam() {
    //     $user = User::select("*")->where('active', true)->get();
    //     $i = 1;
    //     foreach($user as $row) {            
    //        if ($row->hasRole('chamcong')) {
    //             $flag = false;
    //             $phepNamThucTe = 0;
    //             $phepNamDaSuDung = 0;
    //             $ngayVao = \HelpFunction::getDateRevertCreatedAt($row->created_at);   
    //             $ngay = (strtotime($ngayVao) < strtotime("01-01-" . Date('Y')) ? "01-01-" . Date('Y') : $ngayVao);
    //             //--- phep thuc te
    //             $now = Date('d-m-Y');     
    //             $datediff = strtotime($now) - strtotime($ngay);       
    //             $ngayLam = $datediff / (60 * 60 * 24);   
    //             $chuan = floor($ngayLam / 30);
    //             //-------
    //             //-------
    //             if ($chuan >= 1) {
    //                 $flag = true;
    //                 if ($ngayLam >= 330) $chuan++;
    //                 $phepNamThucTe = ($chuan > 12) ? 12 : $chuan;
    //                 // xử lý phép năm
    //                 $getIdPhepNam = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
    //                 $checkPN = XinPhep::where([
    //                     ['id_user','=',$row->id],
    //                     ['id_phep','=',$getIdPhepNam],
    //                     ['user_duyet','=', true],
    //                     ['nam','=',Date('Y')]
    //                 ])->get();
    //                 foreach($checkPN as $rowp) {
    //                     if ($rowp->buoi == "SANG") {
    //                         $phepNamDaSuDung += 0.5;
    //                     }
    //                     if ($rowp->buoi == "CHIEU") {
    //                         $phepNamDaSuDung += 0.5;
    //                     }
    //                     if ($rowp->buoi == "CANGAY") {
    //                         $phepNamDaSuDung += 1;
    //                     }
    //                 }
    //                 // ---------
    //             }                            
    //             $phepNamConLai = $phepNamThucTe - $phepNamDaSuDung;
    //             $stt = "";

    //             if ($flag) 
    //                 $stt = "<strong class='text-info'>Được sử dụng phép năm</strong>";                    
    //             else
    //                 $stt = "<strong class='text-danger'>Chưa thể sử dụng phép năm</strong>";                    
    //             echo "<tr>
    //                     <td>".$i++."</td>
    //                     <td>".$row->userDetail->surname."</td>
    //                     <td>".$ngayVao. "</td>
    //                     <td class='text-primary text-bold'>".$phepNamThucTe."</td>
    //                     <td class='text-danger text-bold'>".$phepNamDaSuDung."</td>
    //                     <td class='text-success text-bold'>".$phepNamConLai."</td>
    //                     <td>
    //                         $stt
    //                     </td>
    //                 </tr>";
    //        }
    //     }
    // }

    public function loadBaoCaoPhepNam() {
        $user = User::select("*")->where('active', true)->get();
        $i = 1;
        foreach($user as $row) {            
           if ($row->hasRole('chamcong')) {
                $flag = false;
                $current = "1-1-".Date('Y');
                //-------
                $phepNamThucTe = 0;
                $phepNamDaSuDung = 0;  
                if ($row->allowPhepNam == true && $row->ngay !== null) {
                    $flag = true;
                    $thangPhepNam = $row->ngay."-". $row->thang . "-" . $row->nam; 
                    if (strtotime($thangPhepNam) >= strtotime($current))
                        $phepNamThucTe = (int)Date('m') - (int)$row->thang + 1; 
                    else 
                        $phepNamThucTe = (int)Date('m');                     
                }                   
                $ngayVao = \HelpFunction::getDateRevertCreatedAt($row->created_at);

                // xử lý phép năm
                $getIdPhepNam = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
                $checkPN = XinPhep::where([
                    ['id_user','=',$row->id],
                    ['id_phep','=',$getIdPhepNam],
                    ['user_duyet','=', true],
                    ['nam','=',Date('Y')]
                ])->get();
                foreach($checkPN as $rowp) {
                    if ($rowp->buoi == "SANG") {
                        $phepNamDaSuDung += 0.5;
                    }
                    if ($rowp->buoi == "CHIEU") {
                        $phepNamDaSuDung += 0.5;
                    }
                    if ($rowp->buoi == "CANGAY") {
                        $phepNamDaSuDung += 1;
                    }
                }
                // ---------                                     
                $phepNamConLai = $phepNamThucTe - $phepNamDaSuDung;
                $stt = "";

                if ($flag) 
                    $stt = "<strong class='text-info'>Được sử dụng phép năm</strong>";                    
                else
                    $stt = "<strong class='text-danger'>Chưa thể sử dụng phép năm</strong>";   
                    
                if (!$flag) {
                    $phepNamThucTe = "";
                } 
                echo "<tr>
                        <td>".$i++."</td>
                        <td>".$row->userDetail->surname."</td>
                        <td>".$ngayVao."</td>
                        <td>".($row->ngay . "-".$row->thang . "-" . $row->nam). "</td>
                        <td class='text-primary text-bold'>".$phepNamThucTe."</td>
                        <td class='text-danger text-bold'>".$phepNamDaSuDung."</td>
                        <td class='text-success text-bold'>".$phepNamConLai."</td>
                        <td>
                            $stt
                        </td>
                    </tr>";
           }
        }
    }

    // Quản lý biên bản
    public function getPanelBB() {
        $nhom = Nhom::all();
        return view('nhansu.bienban',['phongban' => $nhom]);
    }

    public function loadNhanVien(Request $request){
        $nhom = NhomUser::select("u.id","d.surname")
        ->join('users as u','u.id','=','nhom_user.id_user')
        ->join('nhom as n','n.id','=','nhom_user.id_nhom')
        ->join('users_detail as d','d.id_user','=','u.id')
        ->where("nhom_user.id_nhom",$request->eid)
        ->get();
        if($nhom)
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã load danh sách nhân viên từ phòng ban",
                "data" => $nhom
            ]);
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải danh sách"
            ]);
    }

    public function loadBienBan() {
        $xl = BienBanKhenThuong::select("bienban_khenthuong.*","n.name","d.surname")
        ->join('users as u','u.id','=','bienban_khenthuong.id_user')
        ->join('nhom_user as nu','nu.id_user','=','u.id')
        ->join('users_detail as d','d.id_user','=','u.id')
        ->join('nhom as n','n.id','=','nu.id_nhom')
        ->where('bienban_khenthuong.loai','BIENBAN')
        ->orderBy('bienban_khenthuong.id','desc')
        ->get();
        if($xl)
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã tải danh sách",
                "data" => $xl
            ]);
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải danh sách"
            ]);
    }

    public function postBienBan(Request $request) {
        $xl = new BienBanKhenThuong();
        $this->validate($request,[
            'fileTaiLieu'  => 'required|mimes:jpg,JPG,PNG,png,doc,docx,pdf|max:20480',
        ]);
    
        if ($files = $request->file('fileTaiLieu')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/bienbankhenthuong/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }            
            $xl->ngay = $request->ngay;
            $xl->thang = $request->thang;
            $xl->nam = $request->nam;
            $xl->id_user = $request->nhanVien;
            $xl->url = $name;           
            $xl->noiDung = $request->noiDung;
            $xl->hinhThuc = $request->hinhThuc;
            $xl->loai = "BIENBAN";
            $xl->save();
            $files->move('upload/bienbankhenthuong/', $name);
            
            if ($xl) {
                $user = User::find($request->nhanVien);
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý biên bản";
                $nhatKy->noiDung = "Bổ sung biên bản vi phạm cho nhân viên: " 
                . $user->userDetail->surname. "<br/>Ngày: "
                .$request->ngay."/".$request->thang."/".$request->nam."<br/>Nội dung: "
                .$request->noiDung."<br/>Hình thức xử lý: " . $request->hinhThuc;
                $nhatKy->save(); 

                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload file',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload',
                    "code" => 500
                ]);
            }
           
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function deleteBBKT(Request $request) {
        $xl = BienBanKhenThuong::find($request->id);
        $temp = $xl;
        $name = $temp->url;
        if (file_exists('upload/bienbankhenthuong/'.$name))
            unlink('upload/bienbankhenthuong/'.$name);
        $xl->delete();
        if ($xl){
            $user = User::find($temp->id_user);
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý biên bản";
            $nhatKy->noiDung = "Xóa thông tin biên bản, khen thưởng của <br/>Nhân viên: "
            .$user->userDetail->surname."<br/>Ngày: ".$temp->ngay."/".$temp->thang."/".$temp->nam."<br/>Nội dung: "
            .$temp->noiDung. "<br/>Hình thức: ".$temp->hinhThuc;
            $nhatKy->save(); 
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa file!',
                'code' => 200,
                'data' => $xl
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi xóa file từ máy chủ!',
                'code' => 500
            ]);
    }


    // Quản lý khen thưởng
    public function getPanelKT() {
        $nhom = Nhom::all();
        return view('nhansu.khenthuong',['phongban' => $nhom]);
    }

    public function loadKhenThuong() {
        $xl = BienBanKhenThuong::select("bienban_khenthuong.*","n.name","d.surname")
        ->join('users as u','u.id','=','bienban_khenthuong.id_user')
        ->join('nhom_user as nu','nu.id_user','=','u.id')
        ->join('users_detail as d','d.id_user','=','u.id')
        ->join('nhom as n','n.id','=','nu.id_nhom')
        ->where('bienban_khenthuong.loai','KHEN')
        ->orderBy('bienban_khenthuong.id','desc')
        ->get();
        if($xl)
            return response()->json([
                "code" => 200,
                "type" => "info",
                "message" => "Đã tải danh sách",
                "data" => $xl
            ]);
        else
            return response()->json([
                "code" => 500,
                "type" => "info",
                "message" => "Lỗi tải danh sách"
            ]);
    }

    public function postKhenThuong(Request $request) {
        $xl = new BienBanKhenThuong();
        $this->validate($request,[
            'fileTaiLieu'  => 'required|mimes:jpg,JPG,PNG,png,doc,docx,pdf|max:20480',
        ]);
    
        if ($files = $request->file('fileTaiLieu')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/bienbankhenthuong/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }            
            $xl->ngay = $request->ngay;
            $xl->thang = $request->thang;
            $xl->nam = $request->nam;
            $xl->id_user = $request->nhanVien;
            $xl->url = $name;           
            $xl->noiDung = $request->noiDung;
            $xl->hinhThuc = $request->hinhThuc;
            $xl->loai = "KHEN";
            $xl->save();
            $files->move('upload/bienbankhenthuong/', $name);
            
            if ($xl) {
                $user = User::find($request->nhanVien);
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý biên bản";
                $nhatKy->noiDung = "Bổ sung khen thưởng cho nhân viên: " 
                . $user->userDetail->surname. "<br/>Ngày: "
                .$request->ngay."/".$request->thang."/".$request->nam."<br/>Nội dung: "
                .$request->noiDung."<br/>Hình thức: " . $request->hinhThuc;
                $nhatKy->save(); 

                return response()->json([
                    "type" => 'success',
                    "message" => 'File: Đã upload file',
                    "code" => 200,
                    "file" => $files
                ]);
            } else {
                return response()->json([
                    "type" => 'error',
                    "message" => 'File: lỗi upload',
                    "code" => 500
                ]);
            }
           
        }
        return response()->json([
            "type" => 'danger',
            "message" => 'File: Không thể upload file và nội dung',
            "code" => 500
        ]);
    }

    public function xemBienBan(Request $request) {
        $bb = BienBanKhenThuong::where([
            ['id_user','=',$request->id],
            ['thang','=',$request->thang],
            ['nam','=',$request->nam]
        ])->get();
        if ($bb) 
            return response()->json([
                "type" => 'info',
                "message" => 'Đã load thông tin biên bản',
                "code" => 200,
                "data" => $bb
            ]);
        else 
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi load thông tin biên bản',
                "code" => 500
            ]);        
    }

    public function loadBaoCaoLuong(){
        $user = User::all();
        return view('nhansu.luong', ['user' => $user]);
    }

    public function quanLyLuong(){
        return view('nhansu.quanlyluong');
    }

    public function importLuong(Request $request) {
        $this->validate($request,[
            'fileBase'  => 'required|mimes:xls,xlsx|max:20480',
        ]);
        if($request->hasFile('fileBase')){
            $theArray = Excel::toArray([], request()->file('fileBase')); 
            // dd($theArray[1][1][0]);
            if (strval($theArray[1][0][0]) == "CODE" && strval($theArray[1][0][1]) == "DOT1" && 
            strval($theArray[1][0][2]) == "DOT2" && strval($theArray[1][0][3]) == "TNCN" && 
            strval($theArray[1][0][4]) == "TOTAL") {
                $luong = Luong::where([
                    ['thang','=',$request->thang],
                    ['nam','=',$request->nam],
                ])->delete();
                $numlen = count($theArray[1]);                    
                for($i = 1; $i < $numlen; $i++) {
                    if ($theArray[1][$i][0]) {
                        $luongImport = new Luong();
                        $luongImport->manv = strtolower($theArray[1][$i][0]);
                        $luongImport->thang = $request->thang;
                        $luongImport->nam = $request->nam;
                        $luongImport->dot1 = ($theArray[1][$i][1] == null) ? 0 : $theArray[1][$i][1];
                        $luongImport->dot2 = ($theArray[1][$i][2] == null) ? 0 : $theArray[1][$i][2];
                        $luongImport->thue = ($theArray[1][$i][3] == null) ? 0 : $theArray[1][$i][3];
                        $luongImport->thucLanh = ($theArray[1][$i][4] == null) ? 0 : $theArray[1][$i][4];
                        $luongImport->save();        
                    }            
                }    
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Lương - Quản lý";
                $nhatKy->noiDung = "Import excel file lương tháng " . $request->thang . " năm " . $request->nam;
                $nhatKy->save();                
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã Import, kiểm tra lại tại Nhân sự -> Lương',
                    'code' => 200
                ]);                  
            }
		} else {
            return response()->json([
                'type' => 'info',
                'message' => 'Không tìm thấy file import lương',
                'code' => 200
            ]);    
        }           
    } 

    public function loadLuong(Request $request) {
        $nv = $request->nhanVien;
        $thang = $request->thang;
        $nam = $request->nam;

        if ($nv = 0) {
            // $luong = Luong::select("*")
            // ->where([
            //     ['thang','=',$thang],
            //     ['nam','=',$nam],
            // ])->get();
            echo "<h4>PHÒNG KINH DOANH</h4>
            <table class='table table-striped table-bordered'>
                <tr>
                    <th>NHÂN VIÊN</th>
                    <th>THÁNG</th>
                    <th>LƯƠNG ĐỢT 1</th>
                    <th>LƯƠNG ĐỢT 2</th>
                    <th>THUẾ CNCN</th>
                    <th>TỔNG THỰC LÃNH</th>
                </tr>
                <tr>
                    <td>Nguyễn Văn An</td>
                    <td>Tháng 09</td>
                    <td>1.567.223</td>
                    <td>15.221.312</td>
                    <td>2.560.000</td>
                    <td>12.033.222</td>
                </tr>
                <tr>
                    <td>Nguyễn Văn An</td>
                    <td>Tháng 09</td>
                    <td>1.567.223</td>
                    <td>15.221.312</td>
                    <td>2.560.000</td>
                    <td>12.033.222</td>
                </tr>
            </table>";
        } else {

        }
        
    }
}
