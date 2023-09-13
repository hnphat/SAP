<?php

namespace App\Http\Controllers;

use App\Guest;
use App\User;
use App\Sale;
use App\NhatKy;
use App\TypeGuest;
use App\BhPkPackage;
use App\PackageV2;
use App\SaleOffV2;
use App\CarSale;
use App\RequestHD;
use App\KhoV2;
use App\HopDong;
use App\SaleOff;
use App\TypeCarDetail;
use App\PhoneHcare;
use App\GroupSale;
use App\Group;
use App\MarketingGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use Excel;

class GuestController extends Controller
{
    //
    public function index() {
        $type_guest = TypeGuest::all();
        $sale = Sale::where('id_user_create',Auth::user()->id)->get();
        //------------------------
        $arr = [];
        $user = User::all();
        $group = GroupSale::all();
        foreach($user as $row){            
            if ($row->hasRole('sale') && $row->active) {
                $gr = GroupSale::where('user_id', $row->id)->first();
                array_push($arr, [
                    'id' => $row->id,
                    'code' => $row->name,
                    'name' => $row->userDetail->surname
                ]);
            }
        }
        // return view('page.khachhangkd', ['typeGuest' => $type_guest, 'sale' => $sale]);
        return view('page.khachhangkdv2', ['typeGuest' => $type_guest, 'sale' => $sale, 'groupsale' => $arr]);

    }

    public function indexBaoCao() {
        $type_guest = TypeGuest::all();
        $sale = Sale::where('id_user_create',Auth::user()->id)->get();
        return view('page.khachhangkdbaocao', ['typeGuest' => $type_guest, 'sale' => $sale]);
    }

    public function getList() {
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('tpkd'))
            $result = Guest::select('t.name as type','guest.*','guest.id as idmaster', 'd.surname as sale')
                ->join('type_guest as t','guest.id_type_guest','=','t.id')
                ->join('users_detail as d','d.id_user','=','guest.id_user_create')
                ->orderBy('guest.id', 'desc')
                ->get();
        elseif (Auth::user()->hasRole('sale') || Auth::user()->hasRole('adminsale'))
            $result = Guest::select('t.name as type','guest.*','guest.id as idmaster', 'd.surname as sale')
                ->join('type_guest as t','guest.id_type_guest','=','t.id')
                ->join('users_detail as d','d.id_user','=','guest.id_user_create')
                ->where('id_user_create', Auth::user()->id)
                ->orderBy('guest.id', 'desc')
                ->get();
        else
            return response()->json([
                'message' => 'Error get Database from server!',
                'code' => 500
            ]);
    
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getListReport() {
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('tpkd'))
            $result = Guest::select('t.name as type','guest.*','guest.id as idmaster', 'd.surname as sale')
                ->join('type_guest as t','guest.id_type_guest','=','t.id')
                ->join('users_detail as d','d.id_user','=','guest.id_user_create')
                ->orderBy('guest.id', 'DESC')
                ->get();
        // if (Auth::user()->hasRole('sale'))
        //     $result = Guest::select('t.name as type','guest.*','guest.id as idmaster', 'd.surname as sale')
        //         ->join('type_guest as t','guest.id_type_guest','=','t.id')
        //         ->join('users_detail as d','d.id_user','=','guest.id_user_create')
        //         ->where('id_user_create', Auth::user()->id)
        //         ->orderBy('guest.id', 'DESC')
        //         ->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function checkPhone($num) {
        $guest = Guest::where('phone',$num)->first();
        if ($guest) {
            if (Auth::user()->hasRole("system")) {
                echo '{"phone": "'.$num.'", "check":"2", "user":"'.(($guest->user->userDetail) ? $guest->user->userDetail->surname : $guest->user->name).'"}';
            } else {
                echo '{"phone": "'.$num.'", "check":"1", "user":"'.(($guest->user->userDetail) ? $guest->user->userDetail->surname : $guest->user->name).'"}';
            }
        }
        else
            echo '{"phone": "'.$num.'", "check":"0", "user":"'.("NULL").'"}';
    }

    public function add(Request $request) {
        // $theArray = Excel::toArray([], storage_path('oldcus/data.xlsx'));
        // $theArray = Excel::toArray([], 'upload/oldcus/data.xlsx');
        // $numlen = count($theArray[1]);
        // $flag = true;
        // dd($theArray[1][2][0]);                    
        // for($i = 1; $i < $numlen; $i++) {
        //     if ($request->dienThoai == $theArray[1][$i][0]) {
        //         $flag = false;
        //         break;
        //     }
        // }
        $flag = PhoneHcare::select("*")->where('phone',$request->dienThoai)->exists();

        if (!$flag) {
            $guest = new Guest;
            $guest->id_type_guest = $request->loai;
            $guest->name = $request->ten;
            $guest->mst = $request->mst;
            $guest->cmnd = $request->cmnd;
            $guest->ngayCap = $request->ngayCap;
            $guest->noiCap = $request->noiCap;
            $guest->ngaySinh = $request->ngaySinh;
            $guest->daiDien = $request->daiDien;
            $guest->chucVu = $request->chucVu;
            $guest->phone = $request->dienThoai;
            $guest->address = $request->diaChi;
            $guest->id_user_create = Auth::user()->id;
            $guest->nguon = $request->nguon;
            $guest->lenHopDong = $request->lenHopDong;
            $guest->danhGia = $request->danhGia;
            $guest->xeQuanTam = $request->quanTam;
            $guest->cs1 = $request->cs1;
            $guest->cs2 = $request->cs2;
            $guest->cs3 = $request->cs3;
            $guest->cs4 = $request->cs4;
            $guest->save();

            if($guest) {
                
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                $nhatKy->noiDung = "Thêm khách hàng mới <br/>Họ tên: "
                .$request->ten." <br/>CMND: ".$request->cmnd." <br/>Ngày cấp: ".$request->ngayCap." <br/>Nơi cấp: "
                .$request->noiCap." <br/>MST: ".$request->mst." <br/>Đại diện: ".$request->daiDien." <br/>Chức vụ: "
                .$request->chucVu." <br/>Điện thoại: ".$request->dienThoai." <br/>Địa chỉ: " . $request->diaChi;
                $nhatKy->save();

                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thêm: ' . $request->ten,
                    'code' => 200,
                    'noidung' => $request->ten
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => ' Đây là khách hàng cũ từ Hcare không thể thêm!',
                'code' => 500
            ]);
        }
    }

    public function delete(Request $request) {
        $result = Guest::find($request->id);
        $temp = $result;
        // if (Auth::user()->hasRole('system') || Auth::user()->id == $result->id_user_create)
        //     $result->delete();    
        if (Auth::user()->hasRole('system')) {
            $result->delete();    
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                $nhatKy->noiDung = "Xóa khách hàng <br/>Họ tên: "
                .$temp->name." <br/>CMND: ".$temp->cmnd." <br/>Ngày cấp: ".$temp->ngayCap." <br/>Nơi cấp: "
                .$temp->noiCap." <br/>MST: ".$temp->mst." <br/>Đại diện: ".$temp->daiDien." <br/>Chức vụ: "
                .$temp->chucVu." <br/>Điện thoại: ".$temp->phone." <br/>Địa chỉ: " . $temp->address;
                $nhatKy->save();
                return response()->json([
                    'type' => 'success',
                    'message' => 'Delete data successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Có lỗi trong quá trình xoá khách hàng!',
                    'code' => 500
                ]);
            }
        }
        else {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền xoá khách hàng!',
                'code' => 500
            ]);
        }        
    }

    public function editShow(Request $request) {
        $result = Guest::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Show edit data successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function update(Request $request) { 
        $temp = Guest::find($request->eid);
        $mkt = MarketingGuest::where('id_guest_temp',$request->eid)->exists();
        $hopdong = HopDong::where('id_guest', $temp->id)->first();
        if (Auth::user()->hasRole('system')) {
            $result = Guest::where('id',$request->eid)->update([
                'id_type_guest' => $request->eloai,
                'name' => $request->eten,
                'phone' => $request->edienThoai,
                'address' => $request->ediaChi,
                'mst' => $request->emst,
                'cmnd' => $request->ecmnd,
                'ngayCap' => $request->engayCap,
                'noiCap' => $request->enoiCap,
                'ngaySinh' => $request->engaySinh,
                'daiDien' => $request->edaiDien,
                'chucVu' => $request->echucVu,
                'nguon' => $request->enguon,
                'lenHopDong' => $request->elenHopDong,
                'danhGia' => $request->edanhGia,
                'xeQuanTam' => $request->equanTam,
                'cs1' => $request->ecs1,
                'cs2' => $request->ecs2,
                'cs3' => $request->ecs3,
                'cs4' => $request->ecs4,
            ]);
            if($result) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->noiDung = "Chỉnh sửa thông tin khách hàng. <br/>THÔNG TIN CŨ: <br/>Họ tên: "
                .$temp->name." <br/>CMND: ".$temp->cmnd." <br/>Ngày cấp: ".$temp->ngayCap." <br/>Nơi cấp: "
                .$temp->noiCap." <br/>MST: ".$temp->mst." <br/>Đại diện: ".$temp->daiDien." <br/>Chức vụ: "
                .$temp->chucVu." <br/>Điện thoại: ".$temp->phone." <br/>Địa chỉ: " . $temp->address." <br/>THÔNG TIN MỚI: <br/>Họ tên: "
                .$request->eten." <br/>CMND: ".$request->ecmnd." <br/>Ngày cấp: ".$request->engayCap." <br/>Nơi cấp: "
                .$request->enoiCap." <br/>MST: ".$request->emst." <br/>Đại diện: ".$request->edaiDien." <br/>Chức vụ: "
                .$request->echucVu." <br/>Điện thoại: ".$request->edienThoai." <br/>Địa chỉ: " . $request->ediaChi;
                $nhatKy->save();    
                return response()->json([
                    'type' => 'success',
                    'message' => 'Updated successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } elseif (Auth::user()->id == $temp->id_user_create) {
            if ($hopdong) {
                if ($hopdong->admin_check) {
                    return response()->json([
                        'type' => 'info',
                        'message' => 'Khách hàng này đã lên hợp đồng và được duyệt bởi admin sale không thể sữa',
                        'code' => 500
                    ]);
                } else {
                    if ($mkt) {
                        $result = Guest::where('id',$request->eid)->update([
                            'id_type_guest' => $request->eloai,
                            'name' => $request->eten,
                            'address' => $request->ediaChi,
                            'mst' => $request->emst,
                            'cmnd' => $request->ecmnd,
                            'ngayCap' => $request->engayCap,
                            'noiCap' => $request->enoiCap,
                            'ngaySinh' => $request->engaySinh,
                            'daiDien' => $request->edaiDien,
                            'chucVu' => $request->echucVu,
                            'lenHopDong' => $request->elenHopDong,
                            'danhGia' => $request->edanhGia,
                            'xeQuanTam' => $request->equanTam,
                            'cs1' => $request->ecs1,
                            'cs2' => $request->ecs2,
                            'cs3' => $request->ecs3,
                            'cs4' => $request->ecs4,
                        ]);
                    } else {
                        $result = Guest::where('id',$request->eid)->update([
                            'id_type_guest' => $request->eloai,
                            'name' => $request->eten,
                            'phone' => $request->edienThoai,
                            'address' => $request->ediaChi,
                            'mst' => $request->emst,
                            'cmnd' => $request->ecmnd,
                            'ngayCap' => $request->engayCap,
                            'noiCap' => $request->enoiCap,
                            'ngaySinh' => $request->engaySinh,
                            'daiDien' => $request->edaiDien,
                            'chucVu' => $request->echucVu,
                            'nguon' => $request->enguon,
                            'lenHopDong' => $request->elenHopDong,
                            'danhGia' => $request->edanhGia,
                            'xeQuanTam' => $request->equanTam,
                            'cs1' => $request->ecs1,
                            'cs2' => $request->ecs2,
                            'cs3' => $request->ecs3,
                            'cs4' => $request->ecs4,
                        ]);
                    }                    
                    if($result) {
                        $nhatKy = new NhatKy();
                        $nhatKy->id_user = Auth::user()->id;
                        $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                        $nhatKy->thoiGian = Date("H:m:s");
                        $nhatKy->noiDung = "Chỉnh sửa thông tin khách hàng. <br/>THÔNG TIN CŨ: <br/>Họ tên: "
                        .$temp->name." <br/>CMND: ".$temp->cmnd." <br/>Ngày cấp: ".$temp->ngayCap." <br/>Nơi cấp: "
                        .$temp->noiCap." <br/>MST: ".$temp->mst." <br/>Đại diện: ".$temp->daiDien." <br/>Chức vụ: "
                        .$temp->chucVu." <br/>Điện thoại: ".$temp->phone." <br/>Địa chỉ: " . $temp->address." <br/>THÔNG TIN MỚI: <br/>Họ tên: "
                        .$request->eten." <br/>CMND: ".$request->ecmnd." <br/>Ngày cấp: ".$request->engayCap." <br/>Nơi cấp: "
                        .$request->enoiCap." <br/>MST: ".$request->emst." <br/>Đại diện: ".$request->edaiDien." <br/>Chức vụ: "
                        .$request->echucVu." <br/>Điện thoại: ".$request->edienThoai." <br/>Địa chỉ: " . $request->ediaChi;
                        $nhatKy->save();    
                        return response()->json([
                            'type' => 'success',
                            'message' => 'Updated successfully!',
                            'code' => 200
                        ]);
                    } else {
                        return response()->json([
                            'type' => 'error',
                            'message' => 'Internal server fail!',
                            'code' => 500
                        ]);
                    }
                }
            } else {
                if ($mkt) {
                    $result = Guest::where('id',$request->eid)->update([
                        'id_type_guest' => $request->eloai,
                        'name' => $request->eten,                       
                        'address' => $request->ediaChi,
                        'mst' => $request->emst,
                        'cmnd' => $request->ecmnd,
                        'ngayCap' => $request->engayCap,
                        'noiCap' => $request->enoiCap,
                        'ngaySinh' => $request->engaySinh,
                        'daiDien' => $request->edaiDien,
                        'chucVu' => $request->echucVu,                        
                        'lenHopDong' => $request->elenHopDong,
                        'danhGia' => $request->edanhGia,
                        'xeQuanTam' => $request->equanTam,
                        'cs1' => $request->ecs1,
                        'cs2' => $request->ecs2,
                        'cs3' => $request->ecs3,
                        'cs4' => $request->ecs4,
                    ]);
                } else {
                    $result = Guest::where('id',$request->eid)->update([
                        'id_type_guest' => $request->eloai,
                        'name' => $request->eten,
                        'phone' => $request->edienThoai,
                        'address' => $request->ediaChi,
                        'mst' => $request->emst,
                        'cmnd' => $request->ecmnd,
                        'ngayCap' => $request->engayCap,
                        'noiCap' => $request->enoiCap,
                        'ngaySinh' => $request->engaySinh,
                        'daiDien' => $request->edaiDien,
                        'chucVu' => $request->echucVu,
                        'nguon' => $request->enguon,
                        'lenHopDong' => $request->elenHopDong,
                        'danhGia' => $request->edanhGia,
                        'xeQuanTam' => $request->equanTam,
                        'cs1' => $request->ecs1,
                        'cs2' => $request->ecs2,
                        'cs3' => $request->ecs3,
                        'cs4' => $request->ecs4,
                    ]);
                }                
                if($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Chỉnh sửa thông tin khách hàng. <br/>THÔNG TIN CŨ: <br/>Họ tên: "
                    .$temp->name." <br/>CMND: ".$temp->cmnd." <br/>Ngày cấp: ".$temp->ngayCap." <br/>Nơi cấp: "
                    .$temp->noiCap." <br/>MST: ".$temp->mst." <br/>Đại diện: ".$temp->daiDien." <br/>Chức vụ: "
                    .$temp->chucVu." <br/>Điện thoại: ".$temp->phone." <br/>Địa chỉ: " . $temp->address." <br/>THÔNG TIN MỚI: <br/>Họ tên: "
                    .$request->eten." <br/>CMND: ".$request->ecmnd." <br/>Ngày cấp: ".$request->engayCap." <br/>Nơi cấp: "
                    .$request->enoiCap." <br/>MST: ".$request->emst." <br/>Đại diện: ".$request->edaiDien." <br/>Chức vụ: "
                    .$request->echucVu." <br/>Điện thoại: ".$request->edienThoai." <br/>Địa chỉ: " . $request->ediaChi;
                    $nhatKy->save();    
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Updated successfully!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Bạn không có quyền cập nhật thông tin này!',
                'code' => 500
            ]);
        }
        
    }

    public function updateMoving(Request $request) { 
        $temp = Guest::find($request->idguest);
        $user = User::find($request->idsale);
        $hopdong = HopDong::where('id_guest', $request->idguest)->first();
        if (Auth::user()->hasRole('system')) {
            if ($hopdong && $hopdong->admin_check) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Khách hàng này đang nằm trong kế hoạch lên hợp đồng không thể chuyển',
                    'code' => 500
                ]);
            } else {
                $result = Guest::where('id',$request->idguest)->update([
                    'id_user_create' => $request->idsale
                ]);
                if($result) {
                    $nhatKy = new NhatKy();
                    $nhatKy->id_user = Auth::user()->id;
                    $nhatKy->chucNang = "Kinh doanh - Khách hàng";
                    $nhatKy->thoiGian = Date("H:m:s");
                    $nhatKy->noiDung = "Chuyển khách hàng THÔNG TIN: Họ tên: "
                    .$temp->name." Điện thoại: ".$temp->phone." Địa chỉ: " . $temp->address . " cho sale " . $user->userDetail->surname;
                    $nhatKy->save();    
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Chuyển khách hàng '.$temp->name.' cho ' . $user->userDetail->surname . ' thành công!',
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Internal server fail!',
                        'code' => 500
                    ]);
                }
            }
        } 
    }

    public function getKhachHangSaleHD() {
        $arr = [];
        $groupid = 0;
        if (Auth::user()->hasRole('truongnhomsale')) {
            $gr = GroupSale::where('user_id',Auth::user()->id)->first();
            $groupid = ($gr) ? $gr->group_id : 0;
        }
        $user = User::all();
        $iduser = Auth::user()->id;
        $nameuser = Auth::user()->userDetail->surname;
        $group = GroupSale::all();
        foreach($user as $row){            
            if ($row->hasRole('sale') && $row->active) {
                $gr = GroupSale::where('user_id', $row->id)->first();
                array_push($arr, [
                    'id' => $row->id,
                    'code' => $row->name,
                    'name' => $row->userDetail->surname,
                    'group' => ($gr) ? $gr->group_id : 0
                ]);
            }
        }
        // dd($arr);
        return view('page.khachhangsalehd',['user' => $user, 'iduser' => $iduser, 'nameuser' => $nameuser, 'groupsale' => $arr, 'groupid' => $groupid]);
    }

    public function loadBaoCaoKhachhangSaleHD(Request $request) {
        $idgroupmain = 0;
        if (Auth::user()->hasRole('truongnhomsale')) {
            $idgroupmain = GroupSale::where('user_id', Auth::user()->id)->exists() ? GroupSale::where('user_id', Auth::user()->id)->first()->group_id : 0;
        }
        $nv = $request->nhanVien;
        $tu = $request->tu;
        $den = $request->den;
        echo "<div style='overflow:auto;'>
        <table class='table table-striped table-bordered'>
        <tr class='text-center'>
            <th>STT</th>
            <th>Nhóm</th>
            <th>Saler</th>
            <th>HĐ ký/chờ</th>
            <th>HĐ xuất</th>
            <th>Khách hàng mới</th>
            <th>Phụ kiện bán được</th>
        </tr>
        <tbody>";   
        if ($nv == 0) {
            // Sắp xếp
            if (Auth::user()->hasRole('truongnhomsale'))
                $nhomkd = Group::where('id', $idgroupmain)->get(); 
            else 
                $nhomkd = Group::all(); 
            $stonghd = 0;
            $stonghdxuat = 0;
            $stongpkban = 0;
            $stongkh = 0;  
            $stongmkt = 0;          
            foreach($nhomkd as $rowkd) {
                $i = 1;
                $tonghd = 0;
                $tonghdxuat = 0;
                $tongpkban = 0;
                $tongkh = 0;
                $tongmkt = 0;
                $nhomsale = GroupSale::where('group_id', $rowkd->id)->get();
                foreach($nhomsale as $rowsale) {
                    $row = User::find($rowsale->user_id);
                    $hdky = 0;
                    $hdcho = 0;
                    $hdxuat = 0;
                    $pkban = 0;
                    $mktguest = 0;
                    $guestnew = 0;
                    $tenNhom = "";
                    if ($row->active && $row->hasRole('sale')) {
                        $g = Guest::select("*")->where('id_user_create', $row->id)->get();
                        $gr = GroupSale::where('user_id',$row->id)->first();
                        if ($gr) {
                            $tenNhom = Group::find($gr->group_id)->name;
                        }
                        foreach($g as $kh){
                            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($kh->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($kh->created_at)) <= strtotime($den))) {
                                $guestnew++;
                                $mkttemp = MarketingGuest::where('id_guest_temp',$kh->id)->exists();
                                if ($mkttemp)
                                    $mktguest++;
                            }
                        }
                        
                        $hdkyList = HopDong::select('k.xuatXe','hop_dong.*')
                        ->join('kho_v2 as k','k.id','=','hop_dong.id_car_kho')
                        ->where([
                            ['hop_dong.lead_check','=',true],
                            ['hdWait','=',false],
                            ['hop_dong.lead_check_cancel','=',false],
                            ['hop_dong.id_user_create','=',$row->id]
                        ])->get();
                        foreach($hdkyList as $row2){
                            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row2->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row2->created_at)) <= strtotime($den))) {
                                $hdky++;
                            }
    
                            $checkDaXuat = KhoV2::find($row2->id_car_kho);
                            if ($checkDaXuat && $checkDaXuat->xuatXe && ((strtotime($checkDaXuat->ngayGiaoXe) >= strtotime($tu)) 
                            &&  (strtotime($checkDaXuat->ngayGiaoXe) <= strtotime($den)))) {
                                $hdxuat++;
                                // Xử lý pk bán
                                $phuKien = SaleOffV2::select('package.*')
                                ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
                                ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
                                ->where([
                                    ['saleoffv2.id_hd','=', $row2->id],
                                    ['package.type','=','pay']
                                ])->get();
                                foreach($phuKien as $rowPK)
                                    $pkban += $rowPK->cost;
                            }
                        }
            
                        $hdchoList = HopDong::select('*')->where([
                            ['lead_check','=',true],
                            ['hdWait','=',true],
                            ['lead_check_cancel','=',false],
                            ['id_user_create','=',$row->id]
                        ])->get();
                        foreach($hdchoList as $row3){
                            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row3->created_at)) >= strtotime($tu)) 
                            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row3->created_at)) <= strtotime($den))) {
                                    $hdcho++;
                            }
                        }
    
                        echo "<tr class='text-center'>
                                <td>".($i++)."</td>
                                <td>".$tenNhom."</td>
                                <td>".$row->userDetail->surname."</td>
                                <td><strong class='text-primary'>".($hdky + $hdcho)."</strong></td>
                                <td><strong class='text-success'>".$hdxuat."</strong></td>
                                <td><strong class='text-orange'>".$guestnew."</strong>".($mktguest ? "<i class='text-pink'> (mkt: ".$mktguest.")</i>" : "")."</td>
                                <td><strong class='text-info'>".number_format($pkban)."<strong></td>
                            </tr>";
                        $tonghd += ($hdky + $hdcho);
                        $tonghdxuat += $hdxuat;
                        $tongkh += $guestnew;
                        $tongpkban += $pkban;
                        $tongmkt += $mktguest;
                        // ----------------
                    }
                }   
                echo "<tr class='text-center table-info'>
                            <td colspan='3'><strong>TỔNG NHÓM</strong></td>                            
                            <td><strong class='text-primary'>".$tonghd."</strong></td>
                            <td><strong class='text-success'>".$tonghdxuat."</strong></td>
                            <td><strong class='text-orange'>".$tongkh."".($tongmkt ? "<i class='text-pink'> (mkt: ".$tongmkt.")</i>" : "")."</strong></td>
                            <td><strong class='text-info'>".number_format($tongpkban)."<strong></td>
                        </tr>";  
                               
                $stonghd += $tonghd;
                $stonghdxuat += $tonghdxuat;
                $stongpkban += $tongpkban;     
                $stongkh += $tongkh;        
                $stongmkt += $tongmkt;
            }
            if (Auth::user()->hasRole('truongnhomsale')) {

            } else {
                echo "<tr class='text-center table-success'>
                    <td colspan='3'><strong>TỔNG CỘNG PHÒNG KINH DOANH</strong></td>                            
                    <td><strong class='text-primary'>".$stonghd."</strong></td>
                    <td><strong class='text-success'>".$stonghdxuat."</strong></td>
                <td><strong class='text-orange'>".$stongkh."".($stongmkt ? "<i class='text-pink'> (mkt: ".$stongmkt.")</i>" : "")."</strong></td>
                    <td><strong class='text-info'>".number_format($stongpkban)."<strong></td>
                </tr><tbody>"; 
            }

            echo "</table></div>";
        } else {
            $i = 0;
            $u = User::find($nv);
            $hdky = 0;
            $hdcho = 0;
            $hdxuat = 0;
            $pkban = 0;
            $guestnew = 0;
            $tenNhom = "";
            $gr = GroupSale::where('user_id',$u->id)->first();
                if ($gr) {
                    $tenNhom = Group::find($gr->group_id)->name;
                }
            $g = Guest::select("*")->where('id_user_create', $u->id)->get();
            foreach($g as $kh){
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($kh->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($kh->created_at)) <= strtotime($den))) {
                    $guestnew++;
                }
            }

            $hdkyList = HopDong::select('k.xuatXe','hop_dong.*')
            ->join('kho_v2 as k','k.id','=','hop_dong.id_car_kho')
            ->where([
                ['hop_dong.lead_check','=',true],
                ['hdWait','=',false],
                ['hop_dong.lead_check_cancel','=',false],
                ['hop_dong.id_user_create','=',$u->id]
            ])->get();
            foreach($hdkyList as $row2){
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row2->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row2->created_at)) <= strtotime($den))) {
                    $hdky++; 
                }

                $checkDaXuat = KhoV2::find($row2->id_car_kho);
                if ($checkDaXuat && $checkDaXuat->xuatXe && ((strtotime($checkDaXuat->ngayGiaoXe) >= strtotime($tu)) 
                &&  (strtotime($checkDaXuat->ngayGiaoXe) <= strtotime($den)))) {
                    $hdxuat++;
                        // Xử lý pk bán
                        $phuKien = SaleOffV2::select('package.*')
                        ->join('packagev2 as package','saleoffv2.id_bh_pk_package','=','package.id')
                        ->join('hop_dong as h','saleoffv2.id_hd','=','h.id')
                        ->where([
                            ['saleoffv2.id_hd','=', $row2->id],
                            ['package.type','=','pay']
                        ])->get();
                        foreach($phuKien as $rowPK)
                            $pkban += $rowPK->cost;
                }
            }

            $hdchoList = HopDong::select('*')->where([
                ['lead_check','=',true],
                ['hdWait','=',true],
                ['lead_check_cancel','=',false],
                ['id_user_create','=',$u->id]
            ])->get();
            foreach($hdchoList as $row3){
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row3->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row3->created_at)) <= strtotime($den))) {
                        $hdcho++;
                }
            }

            echo "<tr class='text-center'>
                    <td>".($i++)."</td>
                    <td>".$tenNhom."</td>
                    <td>".$u->userDetail->surname."</td>
                    <td><strong class='text-primary'>".($hdky + $hdcho)."</strong></td>
                    <td><strong class='text-success'>".$hdxuat."</strong></td>
                    <td><strong class='text-orange'>".$guestnew."</strong></td>
                    <td><strong class='text-info'>".number_format($pkban)."<strong></td>
                </tr><tbody></table>";
            
            // Show danh sách khách hàng Chăm sóc
            echo "<h5>THÔNG TIN KHÁCH HÀNG CHI TIẾT</h5>
            <table class='table table-striped table-bordered'>
                        <tr class='text-center'>
                            <th>STT</th>
                            <th>Ngày nhập</th>
                            <th>Họ và tên</th>
                            <th>Nguồn</th>
                            <th>Điện thoại</th>
                            <th>Đánh giá</th>
                            <th>Xe quan tâm</th>
                            <th>CS L1</th>
                            <th>CS L2</th>
                            <th>CS L3</th>
                            <th>CS L4</th>
                        </tr>
                        <tbody>";
            $chamSoc = Guest::select("*")->where('id_user_create', $u->id)->get();
            $j = 1;
            foreach($chamSoc as $khach){
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($khach->created_at)) >= strtotime($tu)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($khach->created_at)) <= strtotime($den))) {
                    $stt = "";
                    switch($khach->danhGia) {
                        case "COLD": $stt = "<strong class='text-blue'>".$khach->danhGia."</strong>"; break;
                        case "WARM": $stt = "<strong class='text-orange'>".$khach->danhGia."</strong>"; break;
                        case "HOT": $stt = "<strong class='text-red'>".$khach->danhGia."</strong>"; break;
                    }
                    echo "<tr class='text-center'>
                        <td>".($j++)."</td>
                        <td>".\HelpFunction::revertCreatedAt($khach->created_at)."</td>
                        <td>".$khach->name."</td>
                        <td><strong class='text-primary'>".$khach->nguon."</strong></td>
                        <td> ".(Auth::user()->hasRole('truongnhomsale') ? (Auth::user()->id == $khach->id_user_create ? $khach->phone : substr($khach->phone,0,4)."xxxxxx") : $khach->phone)." </td>
                        <td>".$stt."</td>
                        <td>".$khach->xeQuanTam."</td>
                        <td><i>".$khach->cs1."</i></td>
                        <td><i>".$khach->cs2."</i></td>
                        <td><i>".$khach->cs3."</i></td>
                        <td><i>".$khach->cs4."</i></td>
                    </tr>";
                }
            }    
            echo "</tbody></table></div>";
        }
    }

    public function upFile(Request $request) {
        $this->validate($request,[
            '_tep'  => 'required|mimes:xls,xlsx|max:10480',
        ]);
        // echo "OK";
        // $files = $request->file('_tep');
        // $etc = strtolower($files->getClientOriginalExtension());
        // $files->move('upload/oldcus/', "data." . $etc);
        // return redirect()->route('guest.upload.file')->with('succ','Đã tải dữ liệu!');  
        $fileName = 'data.'.$request->_tep->extension();  
        $request->_tep->move(public_path('upload/oldcus/'), $fileName);
        return back()->with('succ','You have successfully upload file.');      
    }

    public function getAllPhone() {
        // $theArray = Excel::toArray([], 'upload/oldcus/data.xlsx');
        // $numlen = count($theArray[1]);
        // for($i = 1; $i < $numlen; $i++) {
        //     if ($theArray[1][$i][0] != null) {
        //         $p = new PhoneHcare();
        //         $p->phone = $theArray[1][$i][0];
        //         $p->save();
        //     } else {
        //         break;
        //     }
        // }
        $p = PhoneHcare::all();
        if ($p) {
            return response()->json([
                'type' => 'success',
                'message' => 'Load thành công!',
                'code' => 200,
                'data' => $p
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function deleteOnPhone(Request $request) {
        $result = PhoneHcare::find($request->id);
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("quanlyhcare")) {
            $result->delete();    
            if($result) {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Delete data successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }
    }
}
