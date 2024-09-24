<?php

namespace App\Http\Controllers;

use App\TypeCar;
use App\TypeCarDetail;
use App\NhatKy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TypeCarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('page.typecar');
    }

    public function getList() {
        $result = TypeCar::all();
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

    public function add(Request $request) {
        $typecar = new TypeCar;

        $typecar->name = $request->tenXe;
        $typecar->code = $request->code;
        $typecar->isShow = $request->isShow;
        $typecar->save();

        if($typecar) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Thêm Model xe ". $request->tenXe;
            $nhatKy->save();
            return response()->json([
                'message' => 'Inserted Type Car',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function showEdit(Request $request) {
        $result = TypeCar::where('id', $request->id)->first();
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

    public function update(Request $request) {
        $result = TypeCar::where('id', $request->idMasterXe)->update([
            'name' => $request->tenXeE,
            'code' => $request->codeE,
            'isShow' => $request->eisShow
        ]);
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Cập nhật model xe " . $request->tenXeE;
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

    public function delete(Request $request) {
        $temp = TypeCar::find($request->id);
        $result = TypeCar::where('id', $request->id)->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Xóa model xe " . $temp->name;
            $nhatKy->save();
            return response()->json([
                'message' => 'Deleted Type Car',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function more($id) {
        $result = TypeCarDetail::where('id_type_car',$id)->get();
        echo "<table class='table'><tr class='bg-orange'>
        <th>Tên loại xe</th>
        <th>Động cơ</th>
        <th>Hộp số</th>
        <th>Số chỗ</th>
        <th>Nhiên liệu</th>
        <th>Giá vốn</th>
        <th>Hiển thị</th>
        <th>Sửa</th><th>Xóa</th></tr>";
        foreach($result as $row) {
            echo "<tr><td>".$row->name
            ."</td><td>".$row->machine."</td><td>"
            .$row->gear."</td><td>"
            .$row->seat."</td><td>"
            .$row->fuel."</td><td>".number_format($row->giaVon)."</td><td>"
            .($row->isShow ? "Có" : "<strong class='text-danger'>Không</strong>")."</td><td><button class='btn btn-success btn-sm' data-id='".$row->id."' data-toggle='modal' data-target='#editPlusModal' id='showEditPlus'><span class='far fa-edit'></span></button></td><td><button data-id='".$row->id."' data-idmaster='".$id."' class='btn btn-danger btn-sm' id='deletePlus'><span class='fas fa-times-circle'></span></button></td></tr>";
        }
        echo "</table>";
    }

    public function moreAdd(Request $request) {
        $result = TypeCar::where('id',$request->id)->first();
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
        $typecardetail = new TypeCarDetail;
        $typecardetail->id_type_car = $request->idAddPlus;
        $typecardetail->name = $request->loaiXe;
        $typecardetail->fuel = $request->fuel;
        $typecardetail->seat = $request->seat;
        $typecardetail->machine = $request->machine;
        $typecardetail->gear = $request->gear;
        $typecardetail->giaVon = $request->giaVon;
        $typecardetail->isShow = $request->hienThi;
        $typecardetail->save();

        if($typecardetail) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Thêm chi tiết xe từ model xe. Tên loại " . $request->loaiXe;
            $nhatKy->save();
            return response()->json([
                'message' => 'Inserted Add Plus Car',
                'code' => 200,
                'id' => $request->idAddPlus
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function deleteMore(Request $request) {
        $temp = TypeCarDetail::find($request->id);
        $result = TypeCarDetail::where('id', $request->id)->delete();
        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Xóa chi tiết xe từ model xe. Chi tiết xe " . $temp->name;
            $nhatKy->save();
            return response()->json([
                'message' => 'Deleted Type Car Detail',
                'code' => 200,
                'id' => $request->master
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server fail',
                'code' => 500
            ]);
        }
    }

    public function getEditShowPlus(Request $request) {
        $result = TypeCarDetail::where('id',$request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Edit Show add plus success',
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

    public function editAddPlus(Request $request) {
        $result = TypeCarDetail::where('id', $request->idEditAddPlus)->update([
            'name' => $request->_tenLoaiXe,
            'fuel' => $request->_fuel,
            'gear' => $request->_gear,
            'seat' => $request->_seat,
            'machine' => $request->_machine,
            'giaVon' => $request->_giaVon,
            'isShow' => $request->_hienThi
        ]);

        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->chucNang = "Quản trị - Model xe";
            $nhatKy->noiDung = "Cập nhật chi tiết xe " . $request->_tenLoaiXe .  " từ model xe";
            $nhatKy->save();
            return response()->json([
                'message' => 'Data Add Plus updated',
                'code' => 200,
                'id' => $request->idMaster
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }
}
