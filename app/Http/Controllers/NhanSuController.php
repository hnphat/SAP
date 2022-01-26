<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiPhep;
use App\User;
use App\XinPhep;
use App\ChamCongChiTiet;

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

            if ($xinPhep !== null && $xinPhep->user_duyet == true) {
                $stt = "<span class='text-info'>Đã duyệt phép</span>";
                $coPhep+=1;
            }               
            elseif ($xinPhep !== null && $xinPhep->user_duyet == false) {
                $stt = "<span class='text-secondary'>Đã gửi phép</span>";
                $khongPhep+=1;
            }             
                
                $btnXinPhep = "";
            if($chiTiet !== null && $chiTiet->ngay == $i && $chiTiet->thang == $thang && $chiTiet->nam == $nam) {
                if ($chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4)
                    $btnXinPhep = " <button id='xinPhep' data-toggle='modal' data-target='#addModal' data-ngay='".$chiTiet->ngay."' data-thang='".$chiTiet->thang."' data-nam='".$chiTiet->nam."' class='btn btn-sm btn-success'>Xin phép</button>";
                if ($xinPhep === null && $chiTiet->gioSang != 4 || $chiTiet->gioChieu != 4) {
                    $khongPhep+=1;
                }
                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
                    <td class='text-success'>".$chiTiet->vaoSang."</td>
                    <td class='text-success'>".$chiTiet->raSang."</td>
                    <td class='text-success'>".$chiTiet->vaoChieu."</td>
                    <td class='text-success'>".$chiTiet->raChieu."</td>
                    <td class='text-info'>".$chiTiet->gioSang." (giờ)</td>
                    <td class='text-info'>".$chiTiet->gioChieu." (giờ)</td>
                    <td class='text-danger'>".$chiTiet->treSang." (phút)</td>
                    <td class='text-danger'>".$chiTiet->treChieu." (phút)</td>
                    <td class='text-danger'>".$stt."</td>
                    <td class='text-danger'>".$btnXinPhep."</td>
                </tr>
                ";

                $tongCong += ($chiTiet->gioSang + $chiTiet->gioChieu);
                $tongTre += ($chiTiet->treSang + $chiTiet->treChieu);
            }
            else {
                if (!\HelpFunction::isSunday($i,$thang,$nam))
                    $khongPhep += 1;
                echo "
                <tr>
                    <td>".$i."/".$thang."/".$nam."</td>
                    <td class='text-success'></td>
                    <td class='text-success'></td>
                    <td class='text-success'></td>
                    <td class='text-success'></td>
                    <td class='text-info'></td>
                    <td class='text-info'></td>
                    <td class='text-danger'></td>
                    <td class='text-danger'></td>
                    <td class='text-danger'></td>
                    <td class='text-danger'></td>
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

            $loaiPhep = LoaiPhep::find($request->loaiPhep);
            switch ($loaiPhep->loaiPhep) {
                case 'PHEPNAM':
                    {
                        switch($request->buoi){
                            case 'SANG': {
                                $gioSang = 4;
                                $treSang = 0;
                            } break;
                            case 'CHIEU': {
                                $gioChieu = 4;
                                $treChieu = 0;
                            } break;
                            case 'CANGAY': {
                                $gioSang = 4;
                                $gioChieu = 4;
                                $treSang = 0;
                                $treChieu = 0;
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
                            } break;
                            case 'CHIEU': {
                                $gioChieu = 4;
                                $treChieu = 0;
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
                            } break;
                            case 'CHIEU': {
                                $gioChieu = 4;
                                $treChieu = 0;
                            } break;
                            case 'CANGAY': {
                                $gioSang = 4;
                                $gioChieu = 4;
                                $treSang = 0;
                                $treChieu = 0;
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
                            } break;
                            case 'CHIEU': {
                                $treChieu = 0;
                            } break;
                            case 'CANGAY': {
                                $treSang = 0;
                                $treChieu = 0;
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
            $xinPhep->save();
            if ($loaiPhep)
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
}
