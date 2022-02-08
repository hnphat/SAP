<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiPhep;
use App\User;
use App\XinPhep;
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
        if ($loaiPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã thêm dữ liệu",
                "data" => $loaiPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi thêm dữ liệu"
            ]);
    }

    public function quanLyPhepDelete(Request $request) {
        $loaiPhep = LoaiPhep::find($request->id);
        $loaiPhep->delete();
        if ($loaiPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa dữ liệu",
                "data" => $loaiPhep
            ]);
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
        $khongPhep = 0;
        for($i = 1; $i <= $day; $i++) {
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
            $stt = "";
            $flag = true;
            $phepSang = "";
            $phepChieu = "";
            $gioSang = "";
            $gioChieu = "";
            $treSang = "";
            $treChieu = "";

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
            }               
            elseif ($xinPhep !== null && $xinPhep->user_duyet == false) {
                $stt = "<span class='text-secondary'>Đã gửi phép</span>";
                $khongPhep+=1;
                $flag = false;
            }             
                
                $btnXinPhep = "";
            if($chiTiet !== null && $chiTiet->ngay == $i && $chiTiet->thang == $thang && $chiTiet->nam == $nam) {
                if ($chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4) {
                    if ($flag)
                        $btnXinPhep = "<button id='xinPhep' data-toggle='modal' data-target='#addModal' data-ngay='".$chiTiet->ngay."' data-thang='".$chiTiet->thang."' data-nam='".$chiTiet->nam."' class='btn btn-sm btn-success'>Xin phép</button>";
                    else
                        $btnXinPhep = "";
                }                    
                if ($xinPhep === null && ($chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4)) {
                    $khongPhep+=1;
                }
                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
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
                </tr>
                ";

                if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                    $tongTre += ($xinPhep->treSang + $xinPhep->treChieu);
                    $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                } else {
                    $tongTre += ($chiTiet->treSang + $chiTiet->treChieu);
                    $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
                }         
            }
            else {
                $btn = "";
                if (!\HelpFunction::isSunday($i,$thang,$nam)) {
                    $khongPhep += 1;
                    if ($flag)
                        $btn = "<button id='xinPhep' data-toggle='modal' data-target='#addModal' data-ngay='".$i."' data-thang='".$thang."' data-nam='".$nam."' class='btn btn-sm btn-success'>Xin phép</button>";
                    else
                        $btn = "";
                }      

                if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                    $tongCong += ($xinPhep->gioSang + $xinPhep->gioChieu);
                }  

                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
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
                </tr>
                ";
            }                
        }
        echo "
            <tr>
                <td colspan='2'><strong>Tổng công</strong></td>
                <td class='text-success'><strong>".round(($tongCong/8),2)." (ngày)</strong></td>
                <td colspan='2'><strong>Tổng Trể</strong></td>
                <td class='text-danger'><strong>".$tongTre." (phút)</strong></td>
                <td><strong>Có phép</strong></td>
                <td class='text-success'><strong>".$coPhep."</strong></td>
                <td><strong>Không phép</strong></td>
                <td class='text-danger'><strong>".$khongPhep."</strong></td>
                <td></td>
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
            if ($xinPhep)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã tạo phép"
                ]);
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
            if ($xinPhep)
                return response()->json([
                    "type" => "info",
                    "code" => 200,
                    "message" => "Đã tạo phép"
                ]);
            else
                return response()->json([
                    "type" => "info",
                    "code" => 500,
                    "message" => "Lỗi không thể tạo phép"
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
                    <td>".$xinPhep->loaiPhep->moTa."</td>
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
                    <td></td>
                </tr>
                ";
            }
        }   
    }

    public function xinPhepDelete(Request $request) {
        $xinPhep = XinPhep::find($request->id);
        $xinPhep->delete();
        if ($xinPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã xóa phép",
                "data" => $xinPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Lỗi xóa dữ liệu"
            ]);
    }


    // Phê duyệt phép
    public function pheDuyetGetList() {
        return view("nhansu.pheduyet");
    }

    public function pheDuyetPhepGetList() {
        if (!Auth::user()->hasRole('system'))
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
        $xinPhep = XinPhep::where('id',$request->id)->update([
            'user_duyet' => true
        ]);
        if ($xinPhep)
            return response()->json([
                "type" => "info",
                "code" => 200,
                "message" => "Đã phê duyệt phép",
                "data" => $xinPhep
            ]);
        else
            return response()->json([
                "type" => "info",
                "code" => 500,
                "message" => "Không thể phê duyệt phép"
            ]);
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
                            $stt = "<strong class='text-danger'>Không phép</strong>";
                        // }                   
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
                        <td>$stt</td>
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
                            $stt = "<strong class='text-danger'>Không phép</strong>";
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
                                if ($xinPhep->buoi === "SANG" || $xinPhep->buoi === "CANGAY")
                                    echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
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
                <td rowspan='4'></td>
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
                                if ($xinPhep->buoi === "CHIEU" || $xinPhep->buoi === "CANGAY")
                                    echo "<td>".$xinPhep->loaiPhep->maPhep."</td>";
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
                    if(\HelpFunction::isSunday($i,$thang,$nam)) {
                        echo "<td class='bg-warning'></td>";
                    } else {
                        echo "<td></td>";
                    }
                }
             echo "<td></td>
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
                return back()->with('success','Đã cập nhật chấm công! Vào chấm công chi tiết để kiểm tra!');
            }
		}
		return back()->with('error','Không tìm thấy file theo yêu cầu!!!');
    }
}
