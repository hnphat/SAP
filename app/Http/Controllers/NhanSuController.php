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
use App\ChamCongOnline;
use Carbon\Carbon;
use App\Mail\EmailXinPhep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Excel;
use DataTables;
use Illuminate\Support\Facades\Storage;

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
        if ($room && $room->leader == true) {
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

        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('lead_chamcong')) {
            
        } else {
            return response()->json([
                "type" => "error",
                "code" => 400,
                "message" => "Bạn không có quyền thực hiện chức năng này"
            ]);
        }
        // $time_1 = "07:30";
        // $time_2 = "11:30";
        // $time_3 = "13:00";
        // $time_4 = "17:00";
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);   
        // Import từ app.json
        $time_1 = $data['vaoSang'];
        $time_2 = $data['raSang'];
        $time_3 = $data['vaoChieu'];
        $time_4 = $data['raChieu'];

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
            $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->chucNang = "NHÂN SỰ - CHẤM CÔNG CHI TIẾT";
        $nhatKy->noiDung = "Vào xem chấm công";
        $nhatKy->save();

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
        // add tangca
        $tangCa100 = 0;
        $tangCa150 = 0;
        $tangCa200 = 0;
        $tangCa300 = 0;
        // ----------
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

                    // add tang ca
                    $diff_in_minutes = ($to_time - $from_time) / 60;  
                    switch($tangCa->heSo) {
                        case 1: $tangCa100 += $diff_in_minutes; break;
                        case 1.5: $tangCa150 += $diff_in_minutes; break;
                        case 2: $tangCa200 += $diff_in_minutes; break;
                        case 3: $tangCa300 += $diff_in_minutes; break;
                    }
                    //-----------


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
                    if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                        $tangCaGioHanhChinh = (($xinPhep->gioSang + $xinPhep->gioChieu) * $quanLy->heSo);
                        $gioCongTangCa = ($xinPhep->gioSang + $xinPhep->gioChieu);
                        // add tang ca gio hanh chinh
                        switch($quanLy->heSo) {
                            case 1: $tangCa100 += ($gioCongTangCa * 60); break;
                            case 1.5: $tangCa150 += ($gioCongTangCa * 60); break;
                            case 2: $tangCa200 += ($gioCongTangCa * 60); break;
                            case 3: $tangCa300 += ($gioCongTangCa * 60); break;
                        }
                        // ------
                    }
                    else {
                        $tangCaGioHanhChinh = (($chiTiet->gioSang + $chiTiet->gioChieu)* $quanLy->heSo);
                        $gioCongTangCa = ($chiTiet->gioSang + $chiTiet->gioChieu);
                        // add tang ca gio hanh chinh
                        switch($quanLy->heSo) {
                            case 1: $tangCa100 += ($gioCongTangCa * 60); break;
                            case 1.5: $tangCa150 += ($gioCongTangCa * 60); break;
                            case 2: $tangCa200 += ($gioCongTangCa * 60); break;
                            case 3: $tangCa300 += ($gioCongTangCa * 60); break;
                        }
                        // ------
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
                <br/>------------------------------------------
                <br/><span>Tăng ca x1: <span class='text-primary'>".round($tangCa100/60,2)." (giờ)</span></span>
                <br/><span>Tăng ca x1.5: <span class='text-primary'>".round($tangCa150/60,2)." (giờ)</span></span>
                <br/><span>Tăng ca x2: <span class='text-primary'>".round($tangCa200/60,2)." (giờ)</span></span>
                <br/><span>Tăng ca x3: <span class='text-primary'>".round($tangCa300/60,2)." (giờ)</span></span>
                <br/><span>Tổng tăng ca: <span class='text-info'><strong>".$lamThem." (giờ) => ".round($lamThem/8,2)." (ngày)</strong></span>
                <br/>------------------------------------------
                <br/><span>Trể/Sớm: <span class='text-danger'>".$tongTre."  (phút)</span></span>
                <br/><span>Phép được duyệt: <span class='text-success'>".$coPhep."</span></span>
                <br/><span>Phép chưa duyệt: <span class='text-danger'>".($tongPhep - $coPhep)."</span></span>
                <br/><span>Chưa xin phép: <span class='text-danger'>".$chuaXinPhep."</span><br/>(Vào trể; Về sớm; QCC; Vắng nữa buổi; Lý do khác)</span>
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

    public function chiTietGetNhanVienInfo(Request $request) {
        $user = User::find($request->id);
        return response()->json([
            "type" => "info",
            "code" => 200,
            "ten" => $user->userDetail->surname
        ]);
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
                    $nhatKy->ghiChu = Carbon::now();
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
                    $nhatKy->ghiChu = Carbon::now();
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
                $nhatKy->ghiChu = Carbon::now();
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

    // public function xinPhepGetNhanVien(Request $request) {
    //     $thang = $request->thang;
    //     $nam = $request->nam;
    //     $day = \HelpFunction::countDayInMonth($request->thang,$request->nam);
    //     for($i = 1; $i <= $day; $i++) {
    //         $xinPhep = XinPhep::select("*")
    //         ->where([
    //             ['id_user','=',$request->id],
    //             ['ngay','=',$i],
    //             ['thang','=',$thang],
    //             ['nam','=',$nam]
    //         ])->first();
            
    //         $stt = "";
    //         $btn = "";
    //         if ($xinPhep !== null && $xinPhep->user_duyet == true) {
    //             $stt = "<span class='text-info'>Đã duyệt phép</span>";
    //             $btn = "";
    //         }               
    //         elseif ($xinPhep !== null && $xinPhep->user_duyet == false) {
    //             $stt = "<span class='text-secondary'>Chưa duyệt</span>";
    //             $btn = "<button id='delete' data-id='".$xinPhep->id."' class='btn btn-sm btn-danger'>Xóa</button>";
    //         } else {
    //             $btn = "";
    //         }   
       
    //         if ($xinPhep !== null) {
    //             echo "
    //             <tr>
    //                 <td>".$i."/".$thang."/".$nam."</td>
    //                 <td>".$xinPhep->loaiPhep->tenPhep."</td>
    //                 <td>".$xinPhep->lyDo."</td>
    //                 <td>".$xinPhep->buoi."</td>
    //                 <td>".$xinPhep->userDuyet->userDetail->surname."</td>
    //                 <td>".$stt."</td>
    //                 <td>$btn</td>
    //             </tr>
    //             ";
    //         } else {
    //             echo "
    //             <tr>
    //                 <td>".$i."/".$thang."/".$nam."</td>
    //                 <td></td>
    //                 <td></td>
    //                 <td></td>
    //                 <td></td>
    //                 <td></td>
    //                 <td></td>
    //             </tr>
    //             ";
    //         }
    //     }   
    // }

    public function xinPhepGetNhanVien(Request $request) {
        $nam = $request->nam;
        $xinPhep = XinPhep::select("*")
        ->where([
            ['id_user','=',$request->id],
            ['nam','=',$nam]
        ])->orderBy('id','asc')->get();
        foreach ($xinPhep as $row) {
            echo "
            <tr>
                <td>".$row->ngay."/".$row->thang."/".$row->nam."</td>
                <td>".$row->loaiPhep->tenPhep."</td>
                <td>".$row->lyDo."</td>
                <td>".$row->buoi."</td>
                <td>".$row->userDuyet->userDetail->surname."</td>
                <td>".($row->user_duyet == true ? "<span class='text-info'>Đã duyệt phép</span>" : "<span class='text-secondary'>Chưa duyệt</span>")."</td>
                <td>".($row->user_duyet == false ? "<button id='delete' data-id='".$row->id."' class='btn btn-sm btn-danger'>Xóa</button>" : "")."</td>
            </tr>
            ";
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
            $nhatKy->ghiChu = Carbon::now();
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $xinPhep = XinPhep::find($request->id);
            $user = User::find($xinPhep->id_user);
            $ngay = $xinPhep->ngay . "/" . $xinPhep->thang . "/" . $xinPhep->nam;
            $xinPhep->delete();
            if ($xinPhep) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép";
                $nhatKy->noiDung = "Xóa xin phép Quyền HCNS ngày " . $ngay . " của nhân viên " . $user->userDetail->surname;
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
        // return view("nhansu.pheduyet");
        return view("nhansu.pheduyetdemo");
        // return view("nhansu.demo");
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
                "draw" => 1,
                "recordsTotal" => 57,
                "recordsFiltered" => 57,
                "data" => $xinPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi tải dữ liệu"
            ]);
    }

    public function pheDuyetPhepDataTable(Request $request) {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);
        if ($request->ajax()) {
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
                ->take($data["maxRecord"])
                ->get();
            else 
                $xinPhep = XinPhep::select("xin_phep.buoi","xin_phep.created_at","xin_phep.updated_at","xin_phep.id_user_duyet","xin_phep.id","xin_phep.ngay","xin_phep.thang","xin_phep.nam","xin_phep.lyDo","xin_phep.user_duyet","dn.surname as nguoiduyet","d.surname as nguoixin","p.tenPhep as loaiphep")
                ->join('users as u','u.id','=','xin_phep.id_user')
                ->join('users_detail as d','d.id_user','=','u.id')
                ->join('users as un','un.id','=','xin_phep.id_user_duyet')
                ->join('users_detail as dn','dn.id_user','=','un.id')
                ->join('loai_phep as p','p.id','=','xin_phep.id_phep')
                ->orderby('user_duyet','asc')
                ->take($data["maxRecord"])
                ->get();    

                return Datatables::of($xinPhep)
                ->make(true);
        }
            
        return view('nhansu.demo');
    }

    public function pheDuyetPhep(Request $request) {
        $check = XinPhep::where('id',$request->id)->first();
        $ngays = $check->ngay . "/" . $check->thang . "/" . $check->nam;
        $nhanvien = $check->user->userDetail->surname;
        $flag = false;

        // Nếu đã xác nhận công rồi thì không được duyệt phép
        $checkXacNhanCong = XacNhanCong::where([
            ['id_user','=',$check->user->id],
            ['thang','=',$check->thang],
            ['nam','=',$check->nam]
        ])->exists();
        if ($checkXacNhanCong) {
            return response()->json([
                "type" => "error",
                "code" => 500,
                "message" => "Không thể duyệt! Nhân viên này đã xác nhận chấm công!"
            ]);
        } else {
                // ----------------------------                
                $date1 = $check->nam."-".$check->thang."-".$check->ngay;
                $date2 = Date('d-m-Y');
                // $date2 = '2022-03-17';

                $date1_ts = strtotime($date1);
                $date2_ts = strtotime($date2);
                $diff = $date2_ts - $date1_ts;
                $dateDiff = round($diff / 86400);
                // dd($dateDiff);
                if ($dateDiff <= 10) {
                    $flag = true;
                }
                // Xử lý phép năm đang có của nhân viên
                $user = User::find($check->id_user);
                $current = "1-1-".Date('Y');
                $phepNamThucTe = 0;
                if ($user->allowPhepNam == true && $user->ngay !== null) {
                    $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
                    if ($check->nam == (Date('Y') - 1)) {
                        // Xử lý phép cho năm trước liền kề năm hiện tại
                        $current = "31-12-".(Date('Y') - 1);
                        $months = (strtotime($current) - strtotime($thangPhepNam)) / (60 * 60 * 24 * 30);                        
                        if ($months >= 12) {
                            // Nhân viên công tác đủ 1 năm
                            $phepNamThucTe = 12;
                        } else {
                            // Nhân viên công tác chưa đủ năm
                            if ($months > 0) 
                                $phepNamThucTe = round($months);
                            else
                                $phepNamThucTe = 0;
                        }
                    } else if ($check->nam == (Date('Y'))) {
                        // Xử lý phép đúng năm
                        $months = (strtotime("now") - strtotime($thangPhepNam)) / (60 * 60 * 24 * 30);                        
                        if ($months >= 12) {
                            $phepNamThucTe = (int)Date('m');
                        } else {               
                            if ($months > 0) 
                                $phepNamThucTe = round($months);
                            else
                                $phepNamThucTe = 0;
                        }
                    } else {
                        $phepNamThucTe = 0;
                    }
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
                // Xử lý tồn phép năm của năm trước unclock nếu cần
                // $dateUsePNTo = "31-03-".Date('Y');
                // $dateUsePNTo_ts = strtotime($dateUsePNTo);
                // $dataCurrentYear = "31-12-".(Date('Y') - 1);
                // $dataCurrentYear_tes = strtotime($dataCurrentYear);
                // $phepNamConLai = 0;                
                // if ($date2_ts <= $dateUsePNTo_ts && $date2_ts >= $dataCurrentYear_tes)  {
                //     $phepNamConLai = 12;
                // }    
                // ----------------------------------------------
                $userDuyetEmail = User::find($check->id_user_duyet);
                $loaiPhepEmail = LoaiPhep::find($check->id_phep)->tenPhep;
                $nguoiDuyetEmail = $userDuyetEmail->userDetail->surname;
                $nhanVien = User::find($check->id_user)->userDetail->surname;
                $ngayEmail = $check->ngay."-".$check->thang."-".$check->nam;
                $lyDoEmail = $check->lyDo; 


                if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {   
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
                    // Sử dụng duyệt phép năm dùng trong năm cũ unclock nếu cần
                    // if ($check->id_phep == $getIdPhepNam && ($daSuDung + $suDung > ($phepNamThucTe + $phepNamConLai))) {
                    //     return response()->json([
                    //         "type" => "error",
                    //         "code" => 500,
                    //         "message" => "Không thể duyệt! Phép năm không đủ hoặc nhân viên đã dùng hết"
                    //     ]);
                    // }
                    // ----------------------------

                    $xinPhep = XinPhep::where('id',$request->id)->update([
                        'user_duyet' => true
                    ]);
                    if ($xinPhep) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->ghiChu = Carbon::now();
                        $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt phép";
                        $nhatKy->noiDung = "Phê duyệt Quyền HCNS - Phép ngày " . $ngays . " nhân viên yêu cầu: " . $nhanvien;
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
                                $nhatKy->ghiChu = Carbon::now();
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
                                "message" => "Không thể phê duyệt. Phép phải duyệt trước ngày xin phép tối thiểu 10 ngày!"
                            ]);
                        }             
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
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true);   
        $ngay = $request->ngay;
        $thang = $request->thang;
        $nam = $request->nam; 
        // Thời gian mặc định ---------------
        // $time_1 = "07:30";
        // $time_2 = "11:30";
        // $time_3 = "13:00";
        // $time_4 = "17:00";
        // Import từ app.json
        $time_1 = $data['vaoSang'];
        $time_2 = $data['raSang'];
        $time_3 = $data['vaoChieu'];
        $time_4 = $data['raChieu'];

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
                $nhatKy->ghiChu = Carbon::now();
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $tangCa = TangCa::where('id',$request->id)->update([
                'user_duyet' => true
            ]);
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Phê duyệt tăng ca Quyền HCNS ngày " . $ngay . " nhân viên yêu cầu: " . $nhanvien;
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
                    $nhatKy->ghiChu = Carbon::now();
                    $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                    $nhatKy->noiDung = "Phê duyệt tăng ca ngày " . $ngay . " nhân viên yêu cầu: " . $nhanvien;
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
                $nhatKy->ghiChu = Carbon::now();
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
                $nhatKy->ghiChu = Carbon::now();
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
                    $nhatKy->ghiChu = Carbon::now();
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
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $tangCa = TangCa::find($request->id);
            $tangCa->delete();
            if ($tangCa) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - phê duyệt tăng ca";
                $nhatKy->noiDung = "Đã xóa tăng ca (đã duyệt) Quyền HCNS ngày " . $ngay . " của nhân viên " . $nhanvien;
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
        $flag = false;
        //--- phep thuc te
        $current = "1-1-".Date('Y');            
        $phepNamThucTe = 0;
        if ($user->allowPhepNam == true && $user->ngay !== null) {
            if ($nam < Date('Y')) {
                // Trường hợp là năm cũ
                // $current = "31-12-".(Date('Y')-1); 
                // $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
                $phepNamThucTe = 0;
                $flag = true;
            } else {
                // Trường hợp là năm hiện tại
                $thangPhepNam = $user->ngay."-". $user->thang . "-" . $user->nam; 
                if (strtotime($thangPhepNam) >= strtotime($current))
                    $phepNamThucTe = (int)Date('m') - (int)$user->thang + 1; 
                else 
                    $phepNamThucTe = (int)Date('m');
            }                               
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
            'dasudung' => $daSuDung,
            'flag' => $flag
        ]);
    }

    // Xác nhận công
    public function getChotCong(){
        $arr = [];
        $user = User::all();
        foreach($user as $row) {
            if ($row->hasRole('chamcong'))
                array_push($arr, $row);
        }
        return view('nhansu.xacnhancong', ['user' => $arr]);
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
                $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
        $arr = [];
        
        // $nv = XacNhanCong::where([
        //     ['thang','=', $request->thang],
        //     ['nam','=', $request->nam],
        // ])->distinct()->get(['id_user']);

        $nv = ChamCongChiTiet::where([
            ['thang','=', $request->thang],
            ['nam','=', $request->nam],
        ])->distinct()->get(['id_user']);    

        foreach($nv as $row){
            $u = User::find($row->id_user);
            $group = NhomUser::where('id_user',$row->id_user)->first();
            $nhom = $group ? Nhom::find($group->id_nhom) : null;
            $tenNhom = $nhom ? $nhom->name : null;
            $tempnv = XacNhanCong::where([
                ['id_user','=', $row->id_user],
                ['thang','=', $request->thang],
                ['nam','=', $request->nam],
            ])->first();
            $temp = $tempnv ? $tempnv : null;
            if($temp) {
                $temp->name = $u->userDetail->surname;
                $temp->phongBan = $tenNhom ? $tenNhom : "Chưa có phòng ban";
            } else {
                $obj = ['temple' => 'temple'];
                $obj = (object) $obj;
                $obj->name = $u->userDetail->surname;
                $obj->id_user = $row->id_user;
                $obj->thang = $request->thang;
                $obj->nam = $request->nam;
                $obj->phepNam = null;
                $obj->ngayCong = null;
                $obj->tangCa = null;
                $obj->tongTre = null;
                $obj->khongPhep = null;
                $obj->phongBan = $tenNhom;
                $obj->khongPhepNgay = null;

                $temp = $obj;
            }
            array_push($arr, $temp);
        }

        return response()->json([
            "type" => "info",
            "code" => 200,
            "message" => "Đã tổng hợp chốt công",
            "data" => $arr
        ]);       
    }

    public function huyChotCong(Request $request) {
        $temp = XacNhanCong::where([
            ['id_user','=', $request->id],
            ['thang','=', $request->thang],
            ['nam','=', $request->nam],
        ])->first();
        $user = User::find($temp->id_user);
        $xacNhan = XacNhanCong::where([
            ['id_user','=', $request->id],
            ['thang','=', $request->thang],
            ['nam','=', $request->nam],
        ])->delete();
        if ($xacNhan) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Hủy chốt chấm công " . $request->thang . "/" . $request->nam . " của nhân viên " . $user->userDetail->surname;
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
                    $tangCa100 = 0;
                    $tangCa150 = 0;
                    $tangCa200 = 0;
                    $tangCa300 = 0;
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
                                $diff_in_minutes = ($to_time - $from_time) / 60;                               
                                $gioTangCa = round($diff_in_minutes / 60, 2) * $tangCa->heSo;
                                // ---- add tangCa150 ngoai gio hanh chinh
                                switch($tangCa->heSo) {
                                    case 1: $tangCa100 += $diff_in_minutes; break;
                                    case 1.5: $tangCa150 += $diff_in_minutes; break;
                                    case 2: $tangCa200 += $diff_in_minutes; break;
                                    case 3: $tangCa300 += $diff_in_minutes; break;
                                }
                                // ----
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
                                if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                                    $tangCaGioHanhChinh = (($xinPhep->gioSang + $xinPhep->gioChieu) * $quanLy->heSo);
                                    $gioCongTangCa = ($xinPhep->gioSang + $xinPhep->gioChieu);
                                    // add tang ca gio hanh chinh
                                    switch($quanLy->heSo) {
                                        case 1: $tangCa100 += ($gioCongTangCa * 60); break;
                                        case 1.5: $tangCa150 += ($gioCongTangCa * 60); break;
                                        case 2: $tangCa200 += ($gioCongTangCa * 60); break;
                                        case 3: $tangCa300 += ($gioCongTangCa * 60); break;
                                    }
                                    // ------
                                }
                                else {
                                    $tangCaGioHanhChinh = (($chiTiet->gioSang + $chiTiet->gioChieu)* $quanLy->heSo);
                                    $gioCongTangCa = ($chiTiet->gioSang + $chiTiet->gioChieu);
                                    // add tang ca gio hanh chinh
                                    switch($quanLy->heSo) {
                                        case 1: $tangCa100 += ($gioCongTangCa * 60); break;
                                        case 1.5: $tangCa150 += ($gioCongTangCa * 60); break;
                                        case 2: $tangCa200 += ($gioCongTangCa * 60); break;
                                        case 3: $tangCa300 += ($gioCongTangCa * 60); break;
                                    }
                                    // ------
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
                    $xacNhan->tangCa100 = round($tangCa100 / 60, 2);
                    $xacNhan->tangCa150 = round($tangCa150 / 60, 2);
                    $xacNhan->tangCa200 = round($tangCa200 / 60, 2);
                    $xacNhan->tangCa300 = round($tangCa300 / 60, 2);
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Chốt tất cả chấm công tháng " . $thang . "/" . $nam;
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
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Nhân sự - Quản lý chốt công";
            $nhatKy->noiDung = "Hủy chốt tất cả chấm công tháng " . $thang . "/" . $nam;
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
            $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
                $nhatKy->ghiChu = Carbon::now();
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
        $ngay = $quanLy->ngay;
        $thang = $quanLy->thang;
        $nam = $quanLy->nam;
        $user = User::find($quanLy->id_user)->userDetail->surname;
        $quanLy->delete();

        if ($request->idPhep != 0) {
            $xinPhep = XinPhep::find($request->idPhep);
            $xinPhep->delete();
        }    

        if ($quanLy) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Nhân sự - Quản lý xin phép - quản lý tăng ca";
            $nhatKy->noiDung = "Xóa tăng ca của nhân viên " . $user . " ngày " . $ngay . "/" . $thang . "/" . $nam;
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
        $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->chucNang = "Nhân sự - Quản lý phép";
        $nhatKy->noiDung = "Thực thiện đồng bộ phép " . $thang . "/" . $nam;
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

    public function getBaoCaoPhepNamKyTruoc() {
        return view('nhansu.baocaophepnamkytruoc');
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

    public function loadBaoCaoPhepNamKyTruoc() {
        $user = User::select("*")->where('active', true)->get();
        $i = 1;
        foreach($user as $row) {            
           if ($row->hasRole('chamcong')) {
                $flag = false;
                $current = "31-12-".(Date('Y')-1);
                //-------
                $phepNamThucTe = 0;
                $phepNamDaSuDung = 0;  
                if ($row->allowPhepNam == true && $row->ngay !== null) {
                    $flag = true;
                    $thangPhepNam = $row->ngay . "-" . $row->thang . "-" . $row->nam;
                    if ($row->nam > (Date('Y')-1)) {
                        $phepNamThucTe = 0;
                    } else {
                        $months = (strtotime($current) - strtotime($thangPhepNam)) / (60 * 60 * 24 * 30);                        
                        if ($months >= 12) {
                            $phepNamThucTe = 12;
                        } else {
                            $phepNamThucTe = abs(floor($months));
                            // if (strtotime($thangPhepNam) >= strtotime($current))
                            //     $phepNamThucTe = (int)Date('m') - (int)$row->thang + 1; 
                            // else 
                            //     $phepNamThucTe = (int)Date('m');
                        }
                    }
                    
                }                   
                $ngayVao = \HelpFunction::getDateRevertCreatedAt($row->created_at);
                // xử lý phép năm
                $getIdPhepNam = LoaiPhep::where('loaiPhep','PHEPNAM')->first()->id;
                $checkPN = XinPhep::where([
                    ['id_user','=',$row->id],
                    ['id_phep','=',$getIdPhepNam],
                    ['user_duyet','=', true],
                    ['nam','=',Date('Y')-1]
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
                $nhatKy->ghiChu = Carbon::now();
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
            $nhatKy->ghiChu = Carbon::now();
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
                $nhatKy->ghiChu = Carbon::now();
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
                $nhatKy->ghiChu = Carbon::now();
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
        if ($nv == 0 && (Auth::user()->hasRole("system") || Auth::user()->hasRole("boss") || Auth::user()->hasRole("lead_chamcong"))) {
            $nhom = Nhom::all();
            foreach($nhom as $row) {
                echo "<h4 class='text-primary'>".$row->name."</h4>";
                echo "<table class='table table-striped table-bordered'>
                    <tr>
                        <th>NHÂN VIÊN</th>
                        <th>LƯƠNG ĐỢT 1</th>
                        <th>LƯƠNG ĐỢT 2</th>
                        <th>THUẾ TNCN</th>
                        <th>TỔNG THỰC LÃNH</th>
                    </tr>";
                $users = $row->user;
                foreach($users as $r) {
                    $luong = Luong::select("*")
                    ->where([
                        ['thang','=',$thang],
                        ['nam','=',$nam],
                        ['manv','=', strtolower($r->name)],
                    ])->first();
                    echo "
                    <tr>
                        <td>".$r->userDetail->surname."</td>
                        <td>".(($luong) ? number_format($luong->dot1) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->dot2) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->thue) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->thucLanh) : 0)."</td>
                    </tr>
                    ";
                }
                echo "</table>";        
            }
        } else {
            echo "<table class='table table-striped table-bordered'>
                    <tr>
                        <th>NHÂN VIÊN</th>
                        <th>LƯƠNG ĐỢT 1</th>
                        <th>LƯƠNG ĐỢT 2</th>
                        <th>THUẾ TNCN</th>
                        <th>TỔNG THỰC LÃNH</th>
                    </tr>";
                    $luong = Luong::select("*")
                    ->where([
                        ['thang','=',$thang],
                        ['nam','=',$nam],
                        ['manv','=', strtolower(Auth::user()->name)],
                    ])->first();
                    echo "
                    <tr>
                        <td>".Auth::user()->userDetail->surname."</td>
                        <td>".(($luong) ? number_format($luong->dot1) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->dot2) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->thue) : 0)."</td>
                        <td>".(($luong) ? number_format($luong->thucLanh) : 0)."</td>
                    </tr>
                    ";
            echo "</table>";
        }
        
    }

    public function onlineChamCong() {
        return view("nhansu.chamcongonline");
    }

    public function kiemTraTrangThaiThietBi(Request $request) {
        $hasDevice = Auth::user()->device_id;
        if ($hasDevice) {
            if ($hasDevice == $request->device_id) {
                return response()->json([
                    'type' => 'success',
                    'ipWan' => request()->ip(),
                    'result' => 1, // Thiết bị đã đăng ký và đúng
                    'message' => 'Thiết bị đã được đăng ký và hợp lệ',
                    'code' => 200
                ]);  
            } else {
                return response()->json([
                    'type' => 'warning',
                    'ipWan' => request()->ip(),
                    'result' => 2, // Thiết bị đã đăng ký nhưng không đúng
                    'message' => 'Thiết bị không hợp lệ. Vui lòng sử dụng thiết bị đã đăng ký',
                    'code' => 200
                ]);  
            }
        } else {
            return response()->json([
                'type' => 'info',
                'ipWan' => request()->ip(),
                'result' => 0, // Thiết bị chưa đăng ký
                'message' => 'Thiết bị chưa được đăng ký',
                'code' => 200
            ]);  
        }
    }

    public function kiemTraTrangThaiViTri(Request $request) {
        $ipClient = $request->ip();
        $ipCheck1 = "115.78.73.52";
        $ipCheck2 = "203.210.232.175";
        if ($ipClient == $ipCheck1 || $ipClient == $ipCheck2) {
            return response()->json([
                'type' => 'success',
                'ipWan' => request()->ip(),
                'result' => 1, // Đang ở công ty
                'code' => 200
            ]); 
        } else {
            return response()->json([
                'type' => 'info',
                'ipWan' => request()->ip(),
                'result' => 0, // Không ở công ty
                'code' => 200
            ]);  
        }
    }

    public function postOnlineChamCong(Request $request) {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true); 
        $_vaoSang = $data['vaoSang'];
        $_raSang= $data['raSang'];
        $_vaoChieu = $data['vaoChieu']; // Lấy giá trị này xác định buổi
        $_raChieu = $data['raChieu'];

        $getStatusDevice = $request->statusDevice;
        $getStatusPos = $request->statusPos;
        $getBuoiChamCong = $request->buoiChamCong;
        $getLoaiChamCong = $request->loaiChamCong;
        $getTimerNow = $request->getNowTimer;
        
        if ($getStatusPos != 1) {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn đang không ở Công ty, hãy truy cập wifi của Công ty và thử lại!',
                'code' => 500,
                'key' => "s2"
            ]);
        }

        // Tự xác định buổi chấm công và loại chấm công
        $buoiXacDinh = 0;
        $loaiXacDinh = 0;
        // Xử lý chấm công thông minh
        // Xác định đang thuộc khoảng nghỉ trưa hay không
        if (\HelpFunction::trongKhoangThoiGian($getTimerNow,$_raSang,$_vaoChieu)) {
            $coChamVaoSangRoi = ChamCongOnline::select("*")->where([
                [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                ['id_user','=',Auth::user()->id],
                ['buoichamcong','=',1],
                ['loaichamcong','=',1]
            ])->exists();
            $coChamRaSangRoi = ChamCongOnline::select("*")->where([
                [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                ['id_user','=',Auth::user()->id],
                ['buoichamcong','=',1],
                ['loaichamcong','=',2]
            ])->exists();
            if ($coChamVaoSangRoi && $coChamRaSangRoi) {
                $buoiXacDinh = 2; // buổi chiều
                $loaiXacDinh = 1; // vào
            } else if ($coChamVaoSangRoi && !$coChamRaSangRoi) {
                $buoiXacDinh = 1; // buổi sáng
                $loaiXacDinh = 2; // ra
            } else if (!$coChamVaoSangRoi && !$coChamRaSangRoi) {
                // Xác định vào trưa
                $coChamVaoTruaRoi = ChamCongOnline::select("*")->where([
                    [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                    ['id_user','=',Auth::user()->id],
                    ['buoichamcong','=',2],
                    ['loaichamcong','=',1]
                ])->exists();
                if (!$coChamVaoTruaRoi) {
                    $buoiXacDinh = 2; // buổi trưa
                    $loaiXacDinh = 1; // vào
                } else {
                    return response()->json([
                        'type' => 'error',               
                        'code' => 500,
                        'message' => 'Bạn đã chấm công vào Ca Chiều rồi! Nếu muốn ra Ca Chiều vui lòng thử lại sau ' . $_vaoChieu . '!',
                        'key' => "s43"
                    ]);  
                }                
            } 
        } else {
           // Xác định trước khoảng nghỉ hay sau khoảng nghỉ
           if (\HelpFunction::lonHonGioDoiChieu($getTimerNow,$_raSang)) {
                // Sau khoảng nghỉ
                // Xác định xem hiện tại đã qua thời gian Ca Chiều 
                // chưa để tiếp tục nếu muốn tăng ca
                if (\HelpFunction::lonHonGioDoiChieu($getTimerNow,$_raChieu)) {
                    // Có thể bắt đầu Ca Tối
                    $coChamVaoChieuRoi = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                        ['id_user','=',Auth::user()->id],
                        ['buoichamcong','=',2],
                        ['loaichamcong','=',1]
                    ])->exists();
                    $coChamRaChieuRoi = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                        ['id_user','=',Auth::user()->id],
                        ['buoichamcong','=',2],
                        ['loaichamcong','=',2]
                    ])->exists();
                    if ($coChamVaoChieuRoi && $coChamRaChieuRoi) { 
                        // Có Ca Chiều có thể bắt đầu Ca Tối                       
                        $coChamVaoToiRoi = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                            ['id_user','=',Auth::user()->id],
                            ['buoichamcong','=',3],
                            ['loaichamcong','=',1]
                        ])->exists();
                        $coChamRaToiRoi = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                            ['id_user','=',Auth::user()->id],
                            ['buoichamcong','=',3],
                            ['loaichamcong','=',2]
                        ])->exists();
                        if ($coChamVaoToiRoi && $coChamRaToiRoi) {
                                return response()->json([
                                'type' => 'error',               
                                'code' => 500,
                                'message' => 'Bạn đã chấm công đủ (vào/ra) cho tăng ca Tối nay rồi! Chúc ngủ ngon. Hẹn gặp lại vào ngày mai!',
                                'key' => "s41"
                            ]); 
                        } else if (!$coChamVaoToiRoi && !$coChamRaToiRoi) {
                            $buoiXacDinh = 3; // buổi tối
                            $loaiXacDinh = 1; // vào 
                        } else if ($coChamVaoToiRoi && !$coChamRaToiRoi) {
                            $buoiXacDinh = 3; // buổi tối
                            $loaiXacDinh = 2; // ra 
                        }        
                    } else if (!$coChamVaoChieuRoi && !$coChamRaChieuRoi) {
                        // Đây là Ca Tối nếu muốn chấm do không làm Ca Chiều
                        $coChamVaoToiRoi = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                            ['id_user','=',Auth::user()->id],
                            ['buoichamcong','=',3],
                            ['loaichamcong','=',1]
                        ])->exists();
                        $coChamRaToiRoi = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                            ['id_user','=',Auth::user()->id],
                            ['buoichamcong','=',3],
                            ['loaichamcong','=',2]
                        ])->exists();
                        if ($coChamVaoToiRoi && $coChamRaToiRoi) {
                                return response()->json([
                                'type' => 'error',               
                                'code' => 500,
                                'message' => 'Bạn đã chấm công đủ (vào/ra) cho tăng ca Tối nay rồi! Chúc ngủ ngon. Hẹn gặp lại vào ngày mai!',
                                'key' => "s41"
                            ]); 
                        } else if (!$coChamVaoToiRoi && !$coChamRaToiRoi) {
                            $buoiXacDinh = 3; // buổi tối
                            $loaiXacDinh = 1; // vào 
                        } else if ($coChamVaoToiRoi && !$coChamRaToiRoi) {
                            $buoiXacDinh = 3; // buổi tối
                            $loaiXacDinh = 2; // ra 
                        }
                    } else if ($coChamVaoChieuRoi && !$coChamRaChieuRoi) {
                        $buoiXacDinh = 2; // buổi chiều
                        $loaiXacDinh = 2; // ra 
                    }
                } else {
                    // Chưa tới buổi tối đây là Ca Chiều có khả năng vô trể và về sớm
                    $coChamVaoChieuRoi = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                        ['id_user','=',Auth::user()->id],
                        ['buoichamcong','=',2],
                        ['loaichamcong','=',1]
                    ])->exists();
                    $coChamRaChieuRoi = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                        ['id_user','=',Auth::user()->id],
                        ['buoichamcong','=',2],
                        ['loaichamcong','=',2]
                    ])->exists();
                    if ($coChamVaoChieuRoi && $coChamRaChieuRoi) {                        
                        // Đủ Ca Chiều báo lỗi nếu tiếp tục chấm
                        return response()->json([
                            'type' => 'error',               
                            'code' => 500,
                            'message' => 'Bạn đã chấm công đủ (vào/ra) cho Ca Chiều! Nếu muốn tăng ca Buổi Tối thử lại sau ' . $_raChieu . '!',
                            'key' => "s42"
                        ]);               
                    } else if (!$coChamVaoChieuRoi && !$coChamRaChieuRoi) {
                        $buoiXacDinh = 2; // buổi chiều
                        $loaiXacDinh = 1; // vào 
                    } else if ($coChamVaoChieuRoi && !$coChamRaChieuRoi) {
                        $buoiXacDinh = 2; // buổi chiều
                        $loaiXacDinh = 2; // ra 
                    }     
                }                  
           } else {
                // Trước khoảng nghỉ
                $coChamVaoSangRoi = ChamCongOnline::select("*")->where([
                    [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                    ['id_user','=',Auth::user()->id],
                    ['buoichamcong','=',1],
                    ['loaichamcong','=',1]
                ])->exists();
                $coChamRaSangRoi = ChamCongOnline::select("*")->where([
                    [\DB::raw('DATE(created_at)'), '=', Date('Y-m-d')],
                    ['id_user','=',Auth::user()->id],
                    ['buoichamcong','=',1],
                    ['loaichamcong','=',2]
                ])->exists();
                if ($coChamVaoSangRoi && $coChamRaSangRoi) {
                    return response()->json([
                        'type' => 'error',               
                        'code' => 500,
                        'message' => 'Bạn đã chấm công đủ (vào/ra) cho Ca Sáng! Nếu muốn vào Ca Chiều thử lại sau ' . $_raSang . '!',
                        'key' => "s44"
                    ]);  
                } else if (!$coChamVaoSangRoi && !$coChamRaSangRoi) {
                    $buoiXacDinh = 1; // buổi sáng
                    $loaiXacDinh = 1; // vào 
                } else if ($coChamVaoSangRoi && !$coChamRaSangRoi) {
                    $buoiXacDinh = 1; // buổi sáng
                    $loaiXacDinh = 2; // ra 
                }
           }
        }
        // ---------------------------
        $chamcong = new ChamCongOnline();
        $chamcong->id_user = Auth::user()->id;
        $chamcong->buoichamcong = $buoiXacDinh;
        $chamcong->loaichamcong = $loaiXacDinh;
        $chamcong->thoigianchamcong = $getTimerNow;
        // Xử lý đã chấm rồi hay chưa và xử lý hack vị trí
        $ipClient = $request->ip();
        $ipCheck1 = "115.78.73.52";
        $ipCheck2 = "203.210.232.175";
        if ($ipClient == $ipCheck1 || $ipClient == $ipCheck2) {
        } else {
            return response()->json([
                'type' => 'error',               
                'code' => 500,
                'message' => 'Bạn đang không ở Công ty, hãy truy cập wifi của Công ty và thử lại!',
                'key' => "s2"
            ]);  
        }
        // Xử lý upload
        $folderPath = public_path('upload/chamcongonline/');        
        $image_parts = explode(";base64,", $request->imageCaptured);              
        $image_type_aux = explode("image/", $image_parts[0]);           
        $image_type = $image_type_aux[1];           
        $image_base64 = base64_decode($image_parts[1]);           
        $name = Auth::user()->name ."_" . uniqid() . '.'.$image_type;
        $file = $folderPath . $name;
        file_put_contents($file, $image_base64);        
        // kết thúc xử lý upload 

        // ---------------------------
        $chamcong->hinhanh = $name;
        $chamcong->save();
        if ($chamcong) {
            return response()->json([
                'type' => 'success',
                'message' => 'Đã ghi nhận giờ công lúc ' . $getTimerNow .  " ngày " . Date('d-m-Y'),
                'code' => 200,
                'key' => "random"
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi chấm công online. Vui lòng thử lại',
                'code' => 500
            ]);
        }
    }

    // public function quanLyChamCongOnline() {
    //     return view("nhansu.quanlychamcongonline");
    // }

    public function quanLyChamCongOnline() {
        return view("nhansu.chamcongonlinequanly");
    }

    public function chiTietChamCongOnline() {
        return view("nhansu.quanlychamcongonline");
    }

    public function getChamCongOnlineList(Request $request) {
        if ($request->from && $request->to) {
            $_from = \HelpFunction::revertDate($request->from);
            $_to = \HelpFunction::revertDate($request->to);
            $result = null;        
            $arr = [];
            $result = ChamCongOnline::select("*")->orderBy('id','desc')->get();
            foreach($result as $row) {
                $row->manv =  $row->user ? $row->user->name : "";
                $row->hoten = $row->user ? $row->user->userDetail->surname : "";
                $row->ngaychamcong = $row->user ? \HelpFunction::getDateRevertCreatedAt($row->created_at) : "";
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                    array_push($arr, $row);
                }
            }
            if($result) {
                return response()->json([
                    'message' => 'Get list successfully!',
                    'code' => 200,
                    'data' => $arr
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            } 
        } else {
            return response()->json([
                'message' => 'Error get Database from server!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    public function getChamCongOnlineListTongQuan(Request $request) {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true); 
        $_vaoSang = $data['vaoSang'];
        $_raSang= $data['raSang'];
        $_vaoChieu = $data['vaoChieu'];
        $_raChieu = $data['raChieu'];
        if ($request->from) {
            $_from = \HelpFunction::revertDate($request->from);
            $result = null;        
            $arr = [];
            $mainResult = [];
            $userChamCong = User::select("*")->where("active",true)->get();
            foreach($userChamCong as $row) {
                if ($row->hasRole('chamcong')) {
                    $obj = "";
                    $obj = (object) $obj;
                    $obj->id = $row->id;
                    $obj->manv = $row->name;
                    $obj->hoten = $row->userDetail ? $row->userDetail->surname : "";
                    $obj->ngaychamcong = $_from;
                    $arrDay = explode('-', $_from);
                    $obj->ngayChamCong = $arrDay[0];
                    $obj->thangChamCong = $arrDay[1];
                    $obj->namChamCong = $arrDay[2]; 
                    $vaoSang = null;
                    $vaoSangTre = null;
                    $raSang = null;
                    $vaoChieu = null;
                    $vaoChieuTre = null;
                    $raChieu = null;
                    $vaoToi = null;
                    $raToi = null;
                    $result = ChamCongOnline::select("*")
                    ->where("id_user",$row->id)->orderBy('id','desc')->get();
                    $chuaDuyet = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                        ['id_user','=',$row->id],
                        ['typeApprove','=',0]
                    ])->exists();
                    $coDuLieuChamCong = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                        ['id_user','=',$row->id]
                    ])->exists();
                    
                    if ($coDuLieuChamCong) {
                        $obj->codulieu = true;
                    } else {
                        $obj->codulieu = false;
                    } 

                    if ($chuaDuyet) {
                        $obj->duyet = false;
                    } else {
                        $obj->duyet = true;
                    } 

                    if ($row->hasRole("chamcong2lan")) {
                        $obj->chamcong2lan = true;
                    } else {
                        $obj->chamcong2lan = false;

                    }
                    if ($row->hasRole("nhanvienvesinh")) {
                        $obj->nhanvienvesinh = true;
                    } else {
                        $obj->nhanvienvesinh = false;
                    }

                    foreach($result as $row2) {
                        if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row2->created_at)) == strtotime($_from))) {                           
                            switch ($row2->buoichamcong) {
                                case 1: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            $vaoSang = $row2->thoigianchamcong;
                                            if (\HelpFunction::lonHonGioDoiChieu($vaoSang,$_vaoSang)) {
                                                $vaoSangTre = \HelpFunction::tinhSoPhutTre($vaoSang,$_vaoSang);
                                            }
                                        } break;
                                        case 2: {
                                            $raSang = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                } break;
                                case 2: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            if ($row->hasRole("chamcong2lan")) {
                                                $daChamItNhatMotLan = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id]
                                                ])->count();
                                                $coChamVaoChieu = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id],
                                                    ['buoichamcong','=',2],
                                                    ['loaichamcong','=',1]
                                                ])->exists();
                                                if ($daChamItNhatMotLan >= 1) {
                                                    if ($coChamVaoChieu) {
                                                        $raChieu = $row2->thoigianchamcong;
                                                    } else {
                                                        $vaoChieu = $row2->thoigianchamcong;
                                                        if (\HelpFunction::lonHonGioDoiChieu($vaoChieu,$_vaoChieu)) {
                                                            $vaoChieuTre = \HelpFunction::tinhSoPhutTre($vaoChieu,$_vaoChieu);
                                                        }
                                                    }
                                                } else {
                                                    $vaoChieu = $row2->thoigianchamcong;
                                                    if (\HelpFunction::lonHonGioDoiChieu($vaoChieu,$_vaoChieu)) {
                                                        $vaoChieuTre = \HelpFunction::tinhSoPhutTre($vaoChieu,$_vaoChieu);
                                                    }
                                                }
                                            } else {
                                                $vaoChieu = $row2->thoigianchamcong;
                                                if (\HelpFunction::lonHonGioDoiChieu($vaoChieu,$_vaoChieu)) {
                                                    $vaoChieuTre = \HelpFunction::tinhSoPhutTre($vaoChieu,$_vaoChieu);
                                                }
                                            }
                                        } break;
                                        case 2: {
                                            $raChieu = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                }                            
                                break;
                                case 3: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            if ($row->hasRole("chamcong2lan")) {
                                                $daChamItNhatMotLan = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id]
                                                ])->count();
                                                $coChamRaChieu = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id],
                                                    ['buoichamcong','=',2],
                                                    ['loaichamcong','=',2]
                                                ])->exists();
                                                if ($daChamItNhatMotLan >= 1) {
                                                    if ($coChamRaChieu) {
                                                        $vaoToi = $row2->thoigianchamcong;
                                                    } else {
                                                        $raChieu = $row2->thoigianchamcong;
                                                    }
                                                } else {
                                                    $vaoToi = $row2->thoigianchamcong;
                                                }                                                
                                            } else {                                              
                                                $vaoToi = $row2->thoigianchamcong;
                                            }
                                        } break;
                                        case 2: {
                                            $raToi = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                }                            
                                break;
                                default:
                                    # code...
                                    break;
                            }          
                        }
                    }
                    $obj->vaoSang = $vaoSang;
                    $obj->raSang = $raSang;
                    $obj->vaoChieu = $vaoChieu;
                    $obj->raChieu = $raChieu;
                    $obj->vaoToi = $vaoToi;
                    $obj->raToi = $raToi;
                    $obj->vaoSangTre = $vaoSangTre;
                    $obj->vaoChieuTre = $vaoChieuTre;
                    array_push($arr, $obj);
                }
            }

            // Xử lý giờ công
            foreach($arr as $row) {
                $caSang = 0;
                $caChieu = 0;
                $treSang = 0;
                $treChieu = 0;
                // Xử lý chấm công 02 lần nếu có
                $user = User::find($row->id);
                if ($user->hasRole("chamcong2lan")) {
                    // Xử lý chấm công 02 lần
                    $soLuong = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date($row->namChamCong."-".$row->thangChamCong."-".$row->ngayChamCong)],
                        ['id_user','=',$user->id]
                    ])->count();
                    $congDau = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date($row->namChamCong."-".$row->thangChamCong."-".$row->ngayChamCong)],
                        ['id_user','=',$user->id]
                    ])->orderBy('id','asc')->first();
                    $congCuoi = ChamCongOnline::select("*")->where([
                        [\DB::raw('DATE(created_at)'), '=', Date($row->namChamCong."-".$row->thangChamCong."-".$row->ngayChamCong)],
                        ['id_user','=',$user->id]
                    ])->orderBy('id','desc')
                    ->first();
                    if ($soLuong >= 2 && $congDau && $congCuoi) {
                        $layCongDau = $congDau->thoigianchamcong;
                        $layCongCuoi = $congCuoi->thoigianchamcong;
                        $congDauTrongKhoangNghi = \HelpFunction::trongKhoangThoiGian($layCongDau,$_raSang,$_vaoChieu);
                        $congCuoiTrongKhoangNghi = \HelpFunction::trongKhoangThoiGian($layCongCuoi,$_raSang,$_vaoChieu);
                        if ($congDauTrongKhoangNghi && $congCuoiTrongKhoangNghi) {
                            // Nhân viên chấm công cả 02 lần đều trong khoảng nghỉ
                            // không tính công
                        } else if ($congDauTrongKhoangNghi && !$congCuoiTrongKhoangNghi) {
                            if (\HelpFunction::lonHonGioDoiChieu($layCongCuoi,$_raChieu)) {
                                // Chấm công đúng
                                $to_time = strtotime($_raChieu);
                                $from_time = strtotime($_vaoChieu);
                                $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);                              
                            } else {
                                // Chấm công có về sớm
                                $hasVeSom = false;
                                $to_time = strtotime($layCongCuoi);
                                $from_time = strtotime($_raChieu);
                                $test = round(($to_time - $from_time)/60,2);
                                if ($test < 0) {
                                    $treChieu += abs($test);
                                    $hasVeSom = true;
                                }

                                if ($treChieu) {
                                    $to_time = strtotime($layCongCuoi);
                                    $from_time = strtotime($_vaoChieu);
                                    $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);                  
                                }
                            }
                        } else if (!$congDauTrongKhoangNghi && $congCuoiTrongKhoangNghi) {
                            if (\HelpFunction::lonHonGioDoiChieu($layCongDau,$_vaoSang)) {
                                // Chấm công có vào trễ
                                $hasVaoTre = false;
                                $to_time = strtotime($layCongDau);
                                $from_time = strtotime($_vaoSang);
                                $test = round(($to_time - $from_time)/60,2);
                                if ($test > 0) {
                                    $treSang += $test;
                                    $hasVaoTre = true;
                                }
                                if ($treSang) {
                                    $to_time = strtotime($_raSang);
                                    $from_time = strtotime($layCongDau);
                                    $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                }
                                $row->raSang = $_raSang;
                            } else {
                                // Chấm công đúng
                                $to_time = strtotime($_raSang);
                                $from_time = strtotime($_vaoSang);
                                $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);                           
                                $row->raSang = $_raSang;
                                $row->vaoChieu = $_vaoChieu;
                            }
                        } else {
                            // Trường hợp cả 02 lần chấm công đều ngoài khoảng nghỉ
                            // Xử lý ca sáng 
                            $hasVaoTre = false;
                            $to_time = strtotime($layCongDau);
                            $from_time = strtotime($_vaoSang);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test > 0) {
                                $treSang += $test;
                                $hasVaoTre = true;
                            }
                            if ($treSang) {
                                $to_time = strtotime($_raSang);
                                $from_time = strtotime($layCongDau);
                                $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else {
                                $to_time = strtotime($_raSang);
                                $from_time = strtotime($_vaoSang);
                                $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
                            }    
                            // Xử lý ca chiều
                            $hasVeSom = false;
                            $to_time = strtotime($layCongCuoi);
                            $from_time = strtotime($_raChieu);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test < 0) {
                                $treChieu += abs($test);
                                $hasVeSom = true;
                            }
                            if ($treChieu) {
                                $to_time = strtotime($layCongCuoi);
                                $from_time = strtotime($_vaoChieu);
                                $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);                    
                            } else {
                                $to_time = strtotime($_raChieu);
                                $from_time = strtotime($_vaoChieu);
                                $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                            }   
                            $row->raSang = $_raSang;
                            $row->vaoChieu = $_vaoChieu;
                        }
                    } else {
                        // Lỗi không lấy được công đầu hoặc cuối
                        // Chấm công thiếu
                    }
                } else if ($user->hasRole("nhanvienvesinh")) {
                    // Xử lý chấm công của nhân viên vệ sinh nếu có
                    // Xử lý ca sáng
                    if ($row->vaoSang != null && $row->raSang != null) {
                        if (\HelpFunction::tinhSoGio($row->vaoSang,$row->raSang)) {
                            $caSang = \HelpFunction::tinhSoGio($row->vaoSang,$row->raSang);
                            if ($caSang >= 240) {
                                $caSang = 4;
                            } else {
                                $treSang = $caSang;
                                $caSang = 240 - $caSang;
                                $caSang = round($caSang/60,2);
                            }
                        } 
                    }
                    // Xử lý ca chiều
                    if ($row->vaoChieu != null && $row->raChieu != null) {
                        if (\HelpFunction::tinhSoGio($row->vaoChieu,$row->raChieu)) {
                            $caChieu = \HelpFunction::tinhSoGio($row->vaoChieu,$row->raChieu);
                            if ($caChieu >= 240) {
                                $caChieu = 4;
                            } else {
                                $treChieu = $caChieu;
                                $caChieu = 240 - $caChieu;
                                $caChieu = round($caChieu/60,2);
                            }
                        } 
                    }
                } else {
                    // Xử lý chấm công cho nhân viên chấm 04 lần
                    // Xử lý ca sáng
                    if ($row->vaoSang != null && $row->raSang != null) {
                        $hasVaoTre = false;
                        $hasVeSom = false;

                        $to_time = strtotime($row->vaoSang);
                        $from_time = strtotime($_vaoSang);
                        $test = round(($to_time - $from_time)/60,2);
                        if ($test > 0) {
                            $treSang += $test;
                            $hasVaoTre = true;
                        }

                        $to_time = strtotime($row->raSang);
                        $from_time = strtotime($_raSang);
                        $test = round(($to_time - $from_time)/60,2);
                        if ($test < 0) {
                            $treSang += abs($test);
                            $hasVeSom = true;
                        }

                        if ($treSang) {
                            if ($hasVaoTre && $hasVeSom) {
                                $to_time = strtotime($row->raSang);
                                $from_time = strtotime($row->vaoSang);
                                $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else if ($hasVaoTre && !$hasVeSom) {
                                $to_time = strtotime($_raSang);
                                $from_time = strtotime($row->vaoSang);
                                $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else if (!$hasVaoTre && $hasVeSom) {
                                $to_time = strtotime($row->raSang);
                                $from_time = strtotime($_vaoSang);
                                $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                            }
                        } else {
                            $to_time = strtotime($_raSang);
                            $from_time = strtotime($_vaoSang);
                            $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
                        }    
                    }
                    // Xử lý ca chiều
                    if ($row->vaoChieu != null && $row->raChieu != null) {
                        $hasVaoTre = false;
                        $hasVeSom = false;
                        $to_time = strtotime($row->vaoChieu);
                        $from_time = strtotime($_vaoChieu);
                        $test = round(($to_time - $from_time)/60,2);
                        if ($test > 0) {
                            $treChieu += $test;
                            $hasVaoTre = true;
                        }

                        $to_time = strtotime($row->raChieu);
                        $from_time = strtotime($_raChieu);
                        $test = round(($to_time - $from_time)/60,2);
                        if ($test < 0) {
                            $treChieu += abs($test);
                            $hasVeSom = true;
                        }

                        if ($treChieu) {
                            if ($hasVaoTre && $hasVeSom) {
                                $to_time = strtotime($row->raChieu);
                                $from_time = strtotime($row->vaoChieu);
                                $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else if ($hasVaoTre && !$hasVeSom) {
                                $to_time = strtotime($_raChieu);
                                $from_time = strtotime($row->vaoChieu);
                                $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                            } else if (!$hasVaoTre && $hasVeSom) {
                                $to_time = strtotime($row->raChieu);
                                $from_time = strtotime($_vaoChieu);
                                $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                            }                       
                        } else {
                            $to_time = strtotime($_raChieu);
                            $from_time = strtotime($_vaoChieu);
                            $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                        }    
                    }
                }

                // Ghi kết quả
                $row->caSang = $caSang >= 0 ? $caSang : 0;
                $row->caChieu = $caChieu >= 0 ? $caChieu : 0;
                $row->treSang = $treSang;
                $row->treChieu = $treChieu;
                array_push($mainResult, $row);
            }

            if($mainResult) {
                return response()->json([
                    'message' => 'Get list successfully!',
                    'code' => 200,
                    'data' => $mainResult
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            } 
        } else {
            return response()->json([
                'message' => 'Error get Database from server!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    public function deleteChamCongOnline(Request $request) {
        if (!Auth::user()->hasRole("system")) {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Bạn không có quyền thực hiện thao tác này!'
            ]);
        }
        $chamcong = ChamCongOnline::find($request->id);
        $hinhanh = $chamcong->hinhanh;
        if ($hinhanh != null && file_exists('upload/chamcongonline/' . $hinhanh))
        unlink('upload/chamcongonline/'.$hinhanh); 
        $chamcong->delete();

        if ($chamcong) {           
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã xoá'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi xoá'
            ]);
    }

    public function dangKyThietBiChamCong(Request $request) {
        $hasDevice = Auth::user()->device_id;
        if ($hasDevice) {
            return response()->json([
                'type' => 'error',
                'message' => 'Thiết bị này đã được đăng ký trước đó, không thể đăng ký thêm',
                'code' => 500
            ]);  
        } else {
            $user = User::find(Auth::user()->id);
            $user->device_id = $request->device_id; 
            $user->save();
            if ($user) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã đăng ký thiết bị chấm công thành công',
                    'code' => 200
                ]);  
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi đăng ký thiết bị chấm công, vui lòng thử lại',
                    'code' => 500
                ]);  
            }
        }
    }

    public function loadLichSuChamCong(Request $request) {
        $result = null;        
        $arr = [];
        $result = ChamCongOnline::select("*")
        ->where("id_user",Auth::user()->id)
        ->get();
        foreach($result as $row) {
            $dateChamCong = \HelpFunction::getDateRevertCreatedAt($row->created_at);
            $nowDate = Date('d-m-Y');
            if (strtotime($dateChamCong) == strtotime($nowDate)) {
                array_push($arr, $row);
            }
        }
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    public function counterAnhDem() {
        $result = ChamCongOnline::select("*")
        ->where("isXoa",false)
        ->orderBy('id','desc')
        ->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'counterAnhDem' => $result->count()
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    public function giaiPhongAnhDem(Request $request) {
        if (Auth::user()->hasRole("system")) {
        } else {
            return response()->json([
                'message' => 'Bạn không có quyền thực hiện chức năng này!',
                'code' => 500,
                'data' => null
            ]);
        }
        if ($request->from && $request->to) {
            $_from = \HelpFunction::revertDate($request->from);
            $_to = \HelpFunction::revertDate($request->to);
            $result = null;        
            $arr = [];
            $result = ChamCongOnline::select("*")
            ->where("isXoa",false)
            ->orderBy('id','desc')->get();
            foreach($result as $row) {
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                    $del = ChamCongOnline::find($row->id);
                    $hinhanh = $del->hinhanh;
                    if ($hinhanh != null && file_exists('upload/chamcongonline/' . $hinhanh))
                        unlink('upload/chamcongonline/'.$hinhanh);
                    $del->isXoa = true;
                    $del->save();
                }
            }
            if($result) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã xóa ảnh đệm!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            } 
        } else {
            return response()->json([
                'message' => 'Error get Database from server!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    // public function getListPicture(Request $request) {
    //     $manv = Auth::user()->name;
    //     $filtered = [];
    //     $files = Storage::files('public/chamcongonline');

    //     foreach ($files as $file) {
    //         if (str_contains($file, $manv)) {
    //             $filtered[] = basename($file);
    //         }
    //     }
    //     return response()->json([
    //             'message' => 'Data list picture!',
    //             'code' => 200,
    //             'data' => $filtered
    //         ]);
    // }

    public function getListPicTure(Request $request) {
        $path = public_path('upload/mauchamcong/');
        $manv = Auth::user()->name;
        $filtered = [];
        if (File::isDirectory($path)) {
            $files = File::allFiles($path);

            foreach ($files as $file) {
                if (str_contains($file->getFilename(), $manv)) {
                    // $filtered[] = $file->getFilename();
                    $filtered[] = [
                        "code" => $manv,
                        "file" => asset('upload/mauchamcong/' . $file->getFilename())
                    ];
                }                
            }
        }

        return response()->json([
            'message' => 'Data list picture!',
            'code' => 200,
            'data' => $filtered
        ]);
    }

    public function approve(Request $request) {
        $chamcong = ChamCongOnline::find($request->id);
        $chamcong->typeApprove = 1;
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
            $chamcong->save();
        } else {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Bạn không có quyền thực hiện thao tác này'
            ]);
        }

        if ($chamcong) {           
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã phê duyệt'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function revert(Request $request) {
        $chamcong = ChamCongOnline::find($request->id);
        $chamcong->typeApprove = 0;
        if (Auth::user()->hasRole("system")) {
            $chamcong->save();
        } else {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Bạn không có quyền thực hiện thao tác này'
            ]);
        }

        if ($chamcong) {           
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Đã hoàn trạng'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function notapprove(Request $request) {
        $chamcong = ChamCongOnline::find($request->id);
        $chamcong->typeApprove = 2;
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
            $chamcong->save();
        } else {
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Bạn không có quyền thực hiện thao tác này'
            ]);
        }

        if ($chamcong) {           
            return response()->json([
                'type' => 'info',
                'code' => 200,
                'message' => 'Không phê duyệt thành công'
            ]);
        }
        else
            return response()->json([
                'type' => 'error',
                'code' => 500,
                'message' => 'Lỗi'
            ]);
    }

    public function approveAll(Request $request) {
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Bạn không có quyền thực hiện chức năng này!',
                'code' => 500
            ]);
        }
        if ($request->from && $request->to) {
            $_from = \HelpFunction::revertDate($request->from);
            $_to = \HelpFunction::revertDate($request->to);
            $result = ChamCongOnline::select("*")
            ->where([
                ["typeApprove","=",0]
            ])->get();
            foreach($result as $row) {
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                    $chamcong = ChamCongOnline::find($row->id);
                    $chamcong->typeApprove = 1;
                    $chamcong->save();
                }
            }
            if($result) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã phê duyệt tất cả!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500,
                    'data' => null
                ]);
            } 
        } else {
            return response()->json([
                'message' => 'Error get Database from server!',
                'code' => 500,
                'data' => null
            ]);
        } 
    }

    public function luuChamCong(Request $request) {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true); 
        $_vaoSang = $data['vaoSang'];
        $_raSang= $data['raSang'];
        $_vaoChieu = $data['vaoChieu'];
        $_raChieu = $data['raChieu'];
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Bạn không có quyền thực hiện chức năng này!',
                'code' => 500
            ]);
        }
        if ($request->from) {
            $_from = \HelpFunction::revertDate($request->from);
            $arrDay = explode('-', $_from);
            $ngay = $arrDay[0];
            $thang = $arrDay[1];
            $nam = $arrDay[2];
            $arr = [];
            $mainResult = [];
            $check = ChamCongOnline::select("*")
            ->where([
                [\DB::raw('DATE(created_at)'), '=', Date($request->from)],
                ["typeApprove","=",0]
            ])->count();
            if ($check) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Tồn tại bản ghi chấm công ngày ' . $_from . ' của nhân viên chưa được phê duyệt!',
                    'code' => 500
                ]);
            } else {                
                $userChamCong = User::select("*")->where("active",true)->get();
                foreach($userChamCong as $row) {
                    if ($row->hasRole('chamcong')) {
                        $vaoSang = null;
                        $raSang = null;
                        $vaoChieu = null;
                        $raChieu = null;
                        $vaoToi = null;
                        $raToi = null;
                        $result = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date($request->from)],
                            ["typeApprove","=",1],
                            ["id_user","=",$row->id]
                        ])->get();
                        // Thêm dữ liệu chấm công vào dữ liệu tạm
                        foreach($result as $row2) {                            
                            switch ($row2->buoichamcong) {
                                case 1: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            $vaoSang = $row2->thoigianchamcong;
                                        } break;
                                        case 2: {
                                            $raSang = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                } break;
                                case 2: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            if ($row->hasRole("chamcong2lan")) {
                                                $daChamItNhatMotLan = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id]
                                                ])->count();
                                                $coChamVaoChieu = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id],
                                                    ['buoichamcong','=',2],
                                                    ['loaichamcong','=',1]
                                                ])->exists();
                                                if ($daChamItNhatMotLan >= 1) {
                                                    if ($coChamVaoChieu) {
                                                        $raChieu = $row2->thoigianchamcong;
                                                    } else {
                                                        $vaoChieu = $row2->thoigianchamcong;                                               
                                                    }
                                                } else {
                                                    $vaoChieu = $row2->thoigianchamcong;                                                
                                                }
                                            } else {
                                                $vaoChieu = $row2->thoigianchamcong;  
                                            }
                                        } break;
                                        case 2: {
                                            $raChieu = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                }                            
                                break;
                                case 3: {
                                    switch ($row2->loaichamcong) {
                                        case 1: {
                                            if ($row->hasRole("chamcong2lan")) {
                                                $daChamItNhatMotLan = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id]
                                                ])->count();
                                                $coChamRaChieu = ChamCongOnline::select("*")->where([
                                                    [\DB::raw('DATE(created_at)'), '=', Date($arrDay[2]."-".$arrDay[1]."-".$arrDay[0])],
                                                    ['id_user','=',$row->id],
                                                    ['buoichamcong','=',2],
                                                    ['loaichamcong','=',2]
                                                ])->exists();
                                                if ($daChamItNhatMotLan >= 1) {
                                                    if ($coChamRaChieu) {
                                                        $vaoToi = $row2->thoigianchamcong;
                                                    } else {
                                                        $raChieu = $row2->thoigianchamcong;
                                                    }
                                                } else {
                                                    $vaoToi = $row2->thoigianchamcong;
                                                }                                                
                                            } else {                                              
                                                $vaoToi = $row2->thoigianchamcong;
                                            }
                                        } break;
                                        case 2: {
                                            $raToi = $row2->thoigianchamcong;
                                        } break;
                                        default:
                                        break;
                                    }
                                }                            
                                break;
                                default:
                                    # code...
                                    break;
                            }     
                        }
                        if ($result) {
                            $row->vaoSang = $vaoSang;
                            $row->raSang = $raSang;
                            $row->vaoChieu = $vaoChieu;
                            $row->raChieu = $raChieu;
                            $row->vaoToi = $vaoToi;
                            $row->raToi = $raToi;
                            array_push($arr, $row);
                        }
                    }
                }
                // Bắt đầu xử lý chấm công
                // Xử lý giờ công
                foreach($arr as $row) {
                    $caSang = 0;
                    $caChieu = 0;
                    $treSang = 0;
                    $treChieu = 0;
                    // Xử lý chấm công 02 lần nếu có
                    $user = User::find($row->id);
                    if ($user->hasRole("chamcong2lan")) {
                        // Xử lý chấm công 02 lần
                        $soLuong = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date($nam."-".$thang."-".$ngay)],
                            ['id_user','=',$user->id]
                        ])->count();
                        $congDau = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date($nam."-".$thang."-".$ngay)],
                            ['id_user','=',$user->id]
                        ])->orderBy('id','asc')->first();
                        $congCuoi = ChamCongOnline::select("*")->where([
                            [\DB::raw('DATE(created_at)'), '=', Date($nam."-".$thang."-".$ngay)],
                            ['id_user','=',$user->id]
                        ])->orderBy('id','desc')
                        ->first();
                        if ($soLuong >= 2 && $congDau && $congCuoi) {
                            $layCongDau = $congDau->thoigianchamcong;
                            $layCongCuoi = $congCuoi->thoigianchamcong;
                            $congDauTrongKhoangNghi = \HelpFunction::trongKhoangThoiGian($layCongDau,$_raSang,$_vaoChieu);
                            $congCuoiTrongKhoangNghi = \HelpFunction::trongKhoangThoiGian($layCongCuoi,$_raSang,$_vaoChieu);
                            if ($congDauTrongKhoangNghi && $congCuoiTrongKhoangNghi) {
                                // Nhân viên chấm công cả 02 lần đều trong khoảng nghỉ
                                // không tính công
                            } else if ($congDauTrongKhoangNghi && !$congCuoiTrongKhoangNghi) {
                                if (\HelpFunction::lonHonGioDoiChieu($layCongCuoi,$_raChieu)) {
                                    // Chấm công đúng
                                    $to_time = strtotime($_raChieu);
                                    $from_time = strtotime($_vaoChieu);
                                    $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                                } else {
                                    // Chấm công có về sớm
                                    $hasVeSom = false;
                                    $to_time = strtotime($layCongCuoi);
                                    $from_time = strtotime($_raChieu);
                                    $test = round(($to_time - $from_time)/60,2);
                                    if ($test < 0) {
                                        $treChieu += abs($test);
                                        $hasVeSom = true;
                                    }

                                    if ($treChieu) {
                                        $to_time = strtotime($layCongCuoi);
                                        $from_time = strtotime($_vaoChieu);
                                        $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);                  
                                    }
                                }
                            } else if (!$congDauTrongKhoangNghi && $congCuoiTrongKhoangNghi) {
                                if (\HelpFunction::lonHonGioDoiChieu($layCongDau,$_vaoSang)) {
                                    // Chấm công có vào trễ
                                    $hasVaoTre = false;
                                    $to_time = strtotime($layCongDau);
                                    $from_time = strtotime($_vaoSang);
                                    $test = round(($to_time - $from_time)/60,2);
                                    if ($test > 0) {
                                        $treSang += $test;
                                        $hasVaoTre = true;
                                    }
                                    if ($treSang) {
                                        $to_time = strtotime($_raSang);
                                        $from_time = strtotime($layCongDau);
                                        $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                    }
                                } else {
                                    // Chấm công đúng
                                    $to_time = strtotime($_raSang);
                                    $from_time = strtotime($_vaoSang);
                                    $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);                           
                                }
                            } else {
                                // Trường hợp cả 02 lần chấm công đều ngoài khoảng nghỉ
                                // Xử lý ca sáng 
                                $hasVaoTre = false;
                                $to_time = strtotime($layCongDau);
                                $from_time = strtotime($_vaoSang);
                                $test = round(($to_time - $from_time)/60,2);
                                if ($test > 0) {
                                    $treSang += $test;
                                    $hasVaoTre = true;
                                }
                                if ($treSang) {
                                    $to_time = strtotime($_raSang);
                                    $from_time = strtotime($layCongDau);
                                    $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                } else {
                                    $to_time = strtotime($_raSang);
                                    $from_time = strtotime($_vaoSang);
                                    $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
                                }    
                                // Xử lý ca chiều
                                $hasVeSom = false;
                                $to_time = strtotime($layCongCuoi);
                                $from_time = strtotime($_raChieu);
                                $test = round(($to_time - $from_time)/60,2);
                                if ($test < 0) {
                                    $treChieu += abs($test);
                                    $hasVeSom = true;
                                }
                                if ($treChieu) {
                                    $to_time = strtotime($layCongCuoi);
                                    $from_time = strtotime($_vaoChieu);
                                    $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);                    
                                } else {
                                    $to_time = strtotime($_raChieu);
                                    $from_time = strtotime($_vaoChieu);
                                    $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                                }   
                                $row->raSang = $_raSang;
                                $row->vaoChieu = $_vaoChieu;
                            }
                        } else {
                            // Lỗi không lấy được công đầu hoặc cuối
                            // Chấm công thiếu
                        }
                    } else if ($user->hasRole("nhanvienvesinh")) {
                        // Xử lý chấm công của nhân viên vệ sinh nếu có
                        // Xử lý ca sáng
                        if ($row->vaoSang != null && $row->raSang != null) {
                            if (\HelpFunction::tinhSoGio($row->vaoSang,$row->raSang)) {
                                $caSang = \HelpFunction::tinhSoGio($row->vaoSang,$row->raSang);
                                if ($caSang >= 240) {
                                    $caSang = 4;
                                } else {
                                    $treSang = $caSang;
                                    $caSang = 240 - $caSang;
                                    $caSang = round($caSang/60,2);
                                }
                            } 
                        }
                        // Xử lý ca chiều
                        if ($row->vaoChieu != null && $row->raChieu != null) {
                            if (\HelpFunction::tinhSoGio($row->vaoChieu,$row->raChieu)) {
                                $caChieu = \HelpFunction::tinhSoGio($row->vaoChieu,$row->raChieu);
                                if ($caChieu >= 240) {
                                    $caChieu = 4;
                                } else {
                                    $treChieu = $caChieu;
                                    $caChieu = 240 - $caChieu;
                                    $caChieu = round($caChieu/60,2);
                                }
                            } 
                        }
                    } else {
                        // Xử lý chấm công cho nhân viên chấm 04 lần
                        // Xử lý ca sáng
                        if ($row->vaoSang != null && $row->raSang != null) {
                            $hasVaoTre = false;
                            $hasVeSom = false;

                            $to_time = strtotime($row->vaoSang);
                            $from_time = strtotime($_vaoSang);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test > 0) {
                                $treSang += $test;
                                $hasVaoTre = true;
                            }

                            $to_time = strtotime($row->raSang);
                            $from_time = strtotime($_raSang);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test < 0) {
                                $treSang += abs($test);
                                $hasVeSom = true;
                            }

                            if ($treSang) {
                                if ($hasVaoTre && $hasVeSom) {
                                    $to_time = strtotime($row->raSang);
                                    $from_time = strtotime($row->vaoSang);
                                    $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                } else if ($hasVaoTre && !$hasVeSom) {
                                    $to_time = strtotime($_raSang);
                                    $from_time = strtotime($row->vaoSang);
                                    $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                } else if (!$hasVaoTre && $hasVeSom) {
                                    $to_time = strtotime($row->raSang);
                                    $from_time = strtotime($_vaoSang);
                                    $caSang = round(round(($to_time - $from_time)/60,2)/60,2);
                                }
                            } else {
                                $to_time = strtotime($_raSang);
                                $from_time = strtotime($_vaoSang);
                                $caSang = round((round(($to_time - $from_time)/60,2) - $treSang)/60,2);
                            }    
                        }
                        // Xử lý ca chiều
                        if ($row->vaoChieu != null && $row->raChieu != null) {
                            $hasVaoTre = false;
                            $hasVeSom = false;
                            $to_time = strtotime($row->vaoChieu);
                            $from_time = strtotime($_vaoChieu);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test > 0) {
                                $treChieu += $test;
                                $hasVaoTre = true;
                            }

                            $to_time = strtotime($row->raChieu);
                            $from_time = strtotime($_raChieu);
                            $test = round(($to_time - $from_time)/60,2);
                            if ($test < 0) {
                                $treChieu += abs($test);
                                $hasVeSom = true;
                            }

                            if ($treChieu) {
                                if ($hasVaoTre && $hasVeSom) {
                                    $to_time = strtotime($row->raChieu);
                                    $from_time = strtotime($row->vaoChieu);
                                    $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                                } else if ($hasVaoTre && !$hasVeSom) {
                                    $to_time = strtotime($_raChieu);
                                    $from_time = strtotime($row->vaoChieu);
                                    $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                                } else if (!$hasVaoTre && $hasVeSom) {
                                    $to_time = strtotime($row->raChieu);
                                    $from_time = strtotime($_vaoChieu);
                                    $caChieu = round(round(($to_time - $from_time)/60,2)/60,2);
                                }                       
                            } else {
                                $to_time = strtotime($_raChieu);
                                $from_time = strtotime($_vaoChieu);
                                $caChieu = round((round(($to_time - $from_time)/60,2) - $treChieu)/60,2);
                            }    
                        }
                    }

                    // Ghi kết quả
                    $row->caSang = $caSang >= 0 ? $caSang : 0;
                    $row->caChieu = $caChieu >= 0 ? $caChieu : 0;
                    $row->treSang = $treSang;
                    $row->treChieu = $treChieu;
                    array_push($mainResult, $row);
                }
                // ------------ Kết thúc xử lý chấm công ------
                // Lưu chấm công vào CSDL
                foreach($mainResult as $row) {
                    $checkChamCong = ChamCongChiTiet::where([
                        ['ngay','=',$ngay],
                        ['thang','=',$thang],
                        ['nam','=',$nam],
                        ['id_user','=',$row->id]
                    ])->exists();

                    if ($checkChamCong) {
                        $chiTiet = ChamCongChiTiet::where([
                            ['ngay','=',$ngay],
                            ['thang','=',$thang],
                            ['nam','=',$nam],
                            ['id_user','=',$row->id]
                        ])
                        ->update([
                            'vaoSang' => $row->vaoSang,
                            'raSang' => $row->raSang,
                            'vaoChieu' => $row->vaoChieu,
                            'raChieu' => $row->raChieu,
                            'gioSang' => $row->caSang,
                            'gioChieu' => $row->caChieu,
                            'treSang' => $row->treSang,
                            'treChieu' => $row->treChieu,
                        ]);
                    } else {
                        $chiTiet = ChamCongChiTiet::insert([
                            'id_user' => $row->id,
                            'ngay' => $ngay,
                            'thang' => $thang,
                            'nam' => $nam,
                            'vaoSang' => $row->vaoSang,
                            'raSang' => $row->raSang,
                            'vaoChieu' => $row->vaoChieu,
                            'raChieu' => $row->raChieu,
                            'gioSang' => $row->caSang,
                            'gioChieu' => $row->caChieu,
                            'treSang' => $row->treSang,
                            'treChieu' => $row->treChieu,
                        ]);
                    }  
                }
                // ---------------------
                if ($mainResult) {
                    return response()->json([
                        'type' => "success",
                        'message' => 'Đã lưu chấm công ngày ' . $_from . ' của nhân viên vào CSDL!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'type' => "error",
                        'message' => 'Lỗi không thể lưu!',
                        'code' => 500
                    ]);
                }
            }            
        } else {
            return response()->json([
                'type' => "error",
                'message' => 'Error get Database from server!',
                'code' => 500
            ]);
        } 
    }

    public function testCode(Request $request) {
        dd(\HelpFunction::trongKhoangThoiGian("12:59","12:00","13:00"));
    }
}
