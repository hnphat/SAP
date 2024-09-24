<?php

namespace App\Http\Controllers;

use App\Nhom;
use App\NhomUser;
use App\NhatKy;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    //
    public function getPanel() {
        $user = User::select("*")->where('active', true)->get();
        return view('phong.quanlyphong', ['user' => $user]);
    }

    public function getList() {
        $result = Nhom::all();
        if($result) {
            return response()->json([
                'message' => 'Get list type car success',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
               'message' => 'Internal Server fail',
               'code' => 500
            ]);
        }
    }

    public function addPhong(Request $request) {
        $exe = new Nhom();
        $exe->name = $request->tenPhong;
        $exe->save();
        if($exe) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Quản lý phòng";
            $nhatKy->noiDung = "Thêm phòng ban " . $request->tenPhong;
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã thêm phòng ban',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function deletePhong(Request $request) {
        $result = Nhom::where('id', $request->id)->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Quản lý phòng";
            $nhatKy->noiDung = "Xóa phòng ban";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa phòng ban',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function editPhong(Request $request) {
        $result = Nhom::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Data founded',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function updatePhong(Request $request) {
        $result = Nhom::where('id', $request->idMasterPhong)->update([
            'name' => $request->etenPhong
        ]);
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Quản lý phòng";
            $nhatKy->noiDung = "Cập nhật tên phòng ban. Tên mới: " . $request->etenPhong;
            $nhatKy->save();
            return response()->json([
                'message' => 'Data updated',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function showMore($id) {
        $result = NhomUser::where('id_nhom',$id)->get();
        echo "<table class='table'><tr class='bg-orange'><th>Tên phòng</th><th>Nhân viên</th><th>Trạng thái</th><th>Tác vụ</th></tr>";
        foreach($result as $row) {
            $stt = ($row->leader == true) ? "<strong class='text-success'>Quản lý</strong>" : "<strong>Nhân viên</strong>" ;
            $nhom = Nhom::find($row->id_nhom);
            $user = User::find($row->id_user);
            echo "<tr><td>"
            .$nhom->name."</td><td>"
            .$user->userDetail->surname
            ."</td><td>".$stt."</td><td><button data-iduser='".$user->id."' data-idnhom='".$nhom->id."' class='btn btn-danger btn-sm' id='deletePlus'><span class='fas fa-times-circle'></span></button></td></tr>";
        }
        echo "</table>";
    }

    public function moreAdd(Request $request) {
        $result = Nhom::where('id',$request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Add plus success',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function addPlus(Request $request) {
        $check = NhomUser::select("*")
        ->where([
            ['id_nhom','=', $request->idAddPlus],
            ['id_user','=', $request->nhanVien]
        ])->exists();
        if ($check) {
            return response()->json([
                'type' => 'info',
                'message' => 'Bạn đã thêm nhân viên này rồi',
                'code' => 200,
                'id' => $request->idAddPlus
            ]);
        } else {
            $exe = new NhomUser;
            $exe->id_nhom = $request->idAddPlus;
            $nhom = Nhom::find($request->idAddPlus)->name;
            $exe->id_user  = $request->nhanVien;
            $user = User::find($request->nhanVien)->userDetail->surname;
            $exe->leader = $request->trangThai;
            $exe->save();

            if($exe) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->chucNang = "Quản trị - Quản lý phòng";
                $nhatKy->noiDung = "Thêm nhân viên ".$user." vào phòng ban " . $nhom;
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thêm nhân viên',
                    'code' => 200,
                    'id' => $request->idAddPlus
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal Server fail',
                    'code' => 500
                ]);
            }
        }        
    }

    public function moreDelete(Request $request) {
        $nhom = Nhom::find($request->idNhom);
        $user = User::find($request->idUser)->userDetail->surname;
        $result = NhomUser::where([
            ['id_user','=', $request->idUser],
            ['id_nhom','=', $request->idNhom],
        ])->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Quản lý phòng";
            $nhatKy->noiDung = "Xóa nhân viên ".$user." ra khỏi phòng ban " . $nhom;
            $nhatKy->save();
            return response()->json([
                'message' => 'Deleted Type Car Detail',
                'code' => 200,
                'id' => $request->idNhom
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }
}
