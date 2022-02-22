<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiPhep;
use App\User;
use App\XinPhep;
use App\TangCa;
use App\QuanLyTangCa;
use App\XacNhanCong;
use App\NhatKy;
use App\ChamCongChiTiet;
use Illuminate\Support\Facades\Auth;
use Excel;

class NhanSuController extends Controller
{
    // Quản lý chấm công
    public function quanLyChamCong() {
        $user = User::select("*")->where('active', true)->get();
        return view("nhansu.quanlychamcong", ['user' => $user]);
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
                <td colspan='2'><strong>Tổng công</strong></td>
                <td class='text-success'><strong>".round(($tongCong/8),2)." (ngày)</strong></td>
                <td colspan='2'><strong>Tổng Trể/Sớm</strong></td>
                <td class='text-danger'><strong>".$tongTre." (phút)</strong></td>
                <td><strong>Có phép</strong></td>
                <td class='text-success'><strong>".$coPhep."</strong></td>
                <td><strong>Không phép (cả ngày)</strong></td>
                <td class='text-danger'><strong>".$khongPhepCaNgay."</strong></td>
                <td><strong>Tăng ca</strong></td>
                <td class='text-info'><strong>".$lamThem." (giờ)</strong><br/> <strong>".round($lamThem/8,2)." (ngày)</strong></td>
            </tr>
            ";
            if (!$xacNhan)
                echo "<tr>
                        <td colspan='12'><button type='button' data-thang='".$thang."' data-nam='".$nam."' data-ngaycong='".(round(($tongCong/8),2) - $phepNam)."' data-tangca='".round(($lamThem/8),2)."' data-tongtre='".$tongTre."' data-khongphep='".($khongPhep - $khongPhepCaNgay)."' data-khongphepcangay='".$khongPhepCaNgay."' data-phepnam='".$phepNam."' id='xacNhan' class='btn btn-info'>Xác nhận giờ công</button></td>
                    </tr>
                    ";
    }

    public function chiTietThemPhep(Request $request) {
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
        $month = (int)Date('m');
        $year = (int)Date('Y');
        if ($year > $request->namXin) {
            $month = 12;
        }
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

        if ($request->loaiPhep == $getIdPhepNam && ($daSuDung + $suDung > $month)) {
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
                $nhatKy->chucNang = "Nhân sự - chấm công chi tiết";
                $nhatKy->noiDung = "Thêm phép<br/>Lý do: ".$request->lyDo." Loại phép: ".$loai->tenPhep." Buổi: "
                .$request->buoi." Ngày xin: "
                .$request->ngayXin."/".$request->thangXin."/".$request->namXin;
                $nhatKy->save();
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
    }


    // Xin tăng ca
    public function chiTietThemTangCa(Request $request) {
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
            $nhatKy->noiDung = "Xin phép tăng ca";
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
        $xinPhep->delete();
        if ($xinPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý xin phép";
            $nhatKy->noiDung = "Xóa xin phép";
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
            $xinPhep->delete();
            if ($xinPhep) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép";
                $nhatKy->noiDung = "Xóa xin phép";
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
        if (!Auth::user()->hasRole('system') && !Auth::user()->hasRole('hcns'))
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
        $month = (int)Date('m');
        $year = (int)Date('Y');
        if ($year > $check->nam) {
            $month = 12;
        }
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

        if (Auth::user()->hasRole('system')) {   
            if ($check->id_phep == $getIdPhep && $checkQCC->count() == 1) {
                return response()->json([
                    "type" => "error",
                    "code" => 500,
                    "message" => "Không thể duyệt! Đã quá số lần duyệt Quên chấm công"
                ]);
            }

            if ($check->id_phep == $getIdPhepNam && ($daSuDung + $suDung > $month)) {
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
                $nhatKy->noiDung = "Phê duyệt phép";
                $nhatKy->save();
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

                if ($check->id_phep == $getIdPhepNam && ($daSuDung + $suDung > $month)) {
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
                    $nhatKy->noiDung = "Phê duyệt phép";
                    $nhatKy->save();
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
        if (Auth::user()->hasRole('system')) {
            $tangCa = TangCa::where('id',$request->id)->update([
                'user_duyet' => true
            ]);
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Phê duyệt tăng ca";
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
                    $nhatKy->noiDung = "Phê duyệt tăng ca";
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
                $nhatKy->noiDung = "Cập nhật giờ công và hệ số tăng ca";
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $tangCa = TangCa::find($request->id);
            $tangCa->delete();
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Xóa tăng ca";
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
                $tangCa->delete();
                if ($tangCa) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                    $nhatKy->noiDung = "Xóa tăng ca";
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

    public function getPhepNam($id, $nam){
        $month = (int) Date('m');
        $year = (int) Date('Y');
        if ($year > $nam) {
            $month = 12;
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
            'conlai' => $month,
            'dasudung' => $daSuDung
        ]);
    }

    // Xác nhận công
    public function getChotCong(){
        return view('nhansu.xacnhancong');
    }

    public function chotCong(Request $request) {
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
            $nhatKy->noiDung = "Chốt chấm công";
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
            $nhatKy->noiDung = "Hủy chốt chấm công";
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
                        if($chiTiet !== null && $chiTiet->ngay == $i && $chiTiet->thang == $thang && $chiTiet->nam == $nam) {
                            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                                $tongTre += ($xinPhep->treSang + $xinPhep->treChieu);
                                $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                            } else {
                                $tongTre += ($chiTiet->treSang + $chiTiet->treChieu);
                                $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
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
            $nhatKy->noiDung = "Admin chốt tất cả chấm công";
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
            $nhatKy->noiDung = "Admin hủy chốt tất cả chấm công";
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
        $xinPhep->duyet = true;
        $xinPhep->save();
        if ($xinPhep) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý phê duyệt phép trưởng bộ phận";
            $nhatKy->noiDung = "Admin đã xem và kiểm tra";
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
        $tangCa->duyet = true;
        $tangCa->save();
        if ($tangCa) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Nhân sự - Quản lý phê duyệt tăng ca trưởng bộ phận";
            $nhatKy->noiDung = "Admin đã xem và kiểm tra";
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

                //-------------
                $xinPhep = XinPhep::where([
                    ['id_user','=',$row->id_user],
                    ['ngay','=',$request->ngay],
                    ['thang','=',$request->thang],
                    ['nam','=',$request->nam],
                ])->first();

                if ($xinPhep !== null)
                    $idXinPhep = $xinPhep->id;     
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
                $nhatKy->noiDung = "Thêm tăng ca và hệ số tăng ca";
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
        $nhatKy->noiDung = "Thêm phép hàng loạt";
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
            $nhatKy->noiDung = "Hủy phép hàng loạt";
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
}
