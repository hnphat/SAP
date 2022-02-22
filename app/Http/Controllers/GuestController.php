<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Sale;
use App\NhatKy;
use App\TypeGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    //
    public function index() {
        $type_guest = TypeGuest::all();
        $sale = Sale::where('id_user_create',Auth::user()->id)->get();
        return view('page.khachhangkd', ['typeGuest' => $type_guest, 'sale' => $sale]);
    }

    public function getList() {
        if (Auth::user()->hasRole('system'))
            $result = Guest::select('t.name as type','guest.*','guest.id as idmaster')
                ->join('type_guest as t','guest.id_type_guest','=','t.id')
                ->orderBy('guest.id', 'DESC')
                ->get();
        if (Auth::user()->hasRole('sale'))
            $result = Guest::select('t.name as type','guest.*','guest.id as idmaster')
                ->join('type_guest as t','guest.id_type_guest','=','t.id')
                ->where('id_user_create', Auth::user()->id)
                ->orderBy('guest.id', 'DESC')
                ->get();
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
        if ($guest)
            echo '{"phone": "'.$num.'", "check":"1", "user":"'.$guest->user->name.'"}';
        else
            echo '{"phone": "'.$num.'", "check":"0", "user":"'.$guest->user->name.'"}';
    }

    public function add(Request $request) {
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
                'message' => 'Insert data successfully!',
                'code' => 200,
                'noidung' => $request->ten
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = Guest::find($request->id);
        $temp = $result;
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
                'message' => 'Delete data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
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
            'chucVu' => $request->echucVu
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
                'message' => 'Updated successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }
}
