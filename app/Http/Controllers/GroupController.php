<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupSale;
use App\NhatKy;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getPanel() {
        $user = User::select("*")->where('active', true)->get();
        return view('group.group', ['user' => $user]);
    }

    public function getList() {
        $result = Group::all();
        if($result) {
            return response()->json([
                'message' => 'Get list success',
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
        $exe = new Group();
        $exe->name = $request->tenPhong;
        $exe->save();
        if($exe) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - Quản lý Nhóm/Saler";
            $nhatKy->noiDung = "Thêm nhóm " . $request->tenPhong;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã thêm nhóm',
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
        $result = Group::where('id', $request->id)->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - Quản lý Nhóm/Saler";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Xóa nhóm";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã xóa nhóm',
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
        $result = Group::where('id', $request->id)->first();
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
        $result = Group::where('id', $request->idMasterPhong)->update([
            'name' => $request->etenPhong
        ]);
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - Quản lý Nhóm/Saler";
            $nhatKy->noiDung = "Cập nhật tên nhóm. Tên mới: " . $request->etenPhong;
            $nhatKy->ghiChu = Carbon::now();
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
        $result = GroupSale::where('group_id',$id)->get();
        echo "<table class='table'><tr class='bg-orange'><th>Tên nhóm</th><th>Nhân viên</th><th>Trạng thái</th><th>Tác vụ</th></tr>";
        foreach($result as $row) {
            $stt = ($row->leader == true) ? "<strong class='text-success'>Trưởng nhóm</strong>" : "<strong>Nhân viên</strong>" ;
            $nhom = Group::find($row->group_id);
            $user = User::find($row->user_id);
            echo "<tr><td>"
            .$nhom->name."</td><td>"
            .$user->userDetail->surname
            ."</td><td>".$stt."</td><td><button data-iduser='".$user->id."' data-idnhom='".$nhom->id."' class='btn btn-danger btn-sm' id='deletePlus'><span class='fas fa-times-circle'></span></button></td></tr>";
        }
        echo "</table>";
    }

    public function moreAdd(Request $request) {
        $result = Group::where('id',$request->id)->first();
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
        $check = GroupSale::select("*")
        ->where([
            ['group_id','=', $request->idAddPlus],
            ['user_id','=', $request->nhanVien]
        ])->exists();
        if ($check) {
            return response()->json([
                'type' => 'info',
                'message' => 'Bạn đã thêm nhân viên này rồi',
                'code' => 200,
                'id' => $request->idAddPlus
            ]);
        } else {
            $exe = new GroupSale;
            $exe->group_id = $request->idAddPlus;
            $nhom = Group::find($request->idAddPlus)->name;
            $exe->user_id  = $request->nhanVien;
            $user = User::find($request->nhanVien)->userDetail->surname;
            $exe->leader = $request->trangThai;
            $exe->save();

            if($exe) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản trị - Quản lý Nhóm/Saler";
                $nhatKy->noiDung = "Thêm nhân viên ".$user." vào nhóm " . $nhom;
                $nhatKy->ghiChu = Carbon::now();
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
        $nhom = Group::find($request->idNhom);
        $user = User::find($request->idUser)->userDetail->surname;
        $result = GroupSale::where([
            ['user_id','=', $request->idUser],
            ['group_id','=', $request->idNhom],
        ])->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - Quản lý Nhóm/Saler";
            $nhatKy->noiDung = "Xóa nhân viên ".$user." ra khỏi nhóm " . $nhom;
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                'message' => 'Deleted Success',
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
