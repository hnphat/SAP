<?php

namespace App\Http\Controllers;

use App\TypeCar;
use App\TypeCarDetail;
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
        $typecar->save();

        if($typecar) {
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
            'name' => $request->tenXeE
        ]);
        if($result) {
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
        $result = TypeCar::where('id', $request->id)->delete();
        if($result) {
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
        echo "<table class='table'><tr class='bg-orange'><th>Tên loại xe</th><th>Sửa</th><th>Xóa</th></tr>";
        foreach($result as $row) {
            echo "<tr><td>".$row->name."</td><td><button class='btn btn-success btn-sm' data-id='".$row->id."' data-toggle='modal' data-target='#editPlusModal' id='showEditPlus'><span class='far fa-edit'></span></button></td><td><button data-id='".$row->id."' data-idmaster='".$id."' class='btn btn-danger btn-sm' id='deletePlus'><span class='fas fa-times-circle'></span></button></td></tr>";
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
        $typecardetail->save();

        if($typecardetail) {
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
        $result = TypeCarDetail::where('id', $request->id)->delete();
        if($result) {
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
            'name' => $request->_tenLoaiXe
        ]);

        if($result) {
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