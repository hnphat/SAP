<?php

namespace App\Http\Controllers;

use App\DangKySuDung;
use App\XeLaiThu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaiThuController extends Controller
{
    public function index()
    {
        $car = XeLaiThu::select('*')->get();
        return view('laithu.car', ['car' => $car]);
    }

    public function store(Request $request)
    {
        $car = new XeLaiThu();
        $car->name = $request->tenXe;
        $car->number_car = $request->bienSo;
        $car->mau = $request->color;
        $car->save();
        if($car) {
            return redirect()->route('laithu.list')->with('succ','Đã thêm xe lái thử');
        } else {
            return redirect()->route('laithu.list')->with('err','Không thể thêm xe lái thử');
        }
    }

    public function destroy(Request $request)
    {
        //
        $car = XeLaiThu::where('id',$request->id)->delete();
        if($car) {
            return response()->json([
                'message' => 'Delete successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function showReg() {
        $car = XeLaiThu::all();
        $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->get();
        return view('laithu.reg', ['car' => $car, 'reg' => $reg]);
    }

    public function postReg(Request $request) {
        $reg = new DangKySuDung();
        $reg->id_user_reg = Auth::user()->id;
        $reg->id_xe_lai_thu = $request->xe;
        $reg->lyDo = $request->lyDo;
        $reg->km_current = $request->km;
        $reg->fuel_current = $request->xang;
        $reg->car_status = $request->trangThaiXe;
        $reg->date_go = $request->timeGo;
        $reg->date_return = $request->timeReturn;
        $reg->save();
        if($reg) {
            return redirect()->route('laithu.reg')->with('succ','Đã đăng ký xe lái thử');
        } else {
            return redirect()->route('laithu.reg')->with('err','Không thể đăng ký xe lái thử');
        }
    }

    public function delReg(Request $request)
    {
        $car = DangKySuDung::where('id',$request->id)->delete();
        if($car) {
            return response()->json([
                'message' => 'Delete successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function showDuyet() {
        $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->get();
        return view('laithu.duyet', ['reg' => $reg]);
    }

    public function allowLaiThu(Request $request) {
        $regInfo = DangKySuDung::find($request->id);
        $reg = DangKySuDung::where('id', $request->id)->update([
            "allow" => true
        ]);

        $upCar = XeLaiThu::where('id', $regInfo->id_xe_lai_thu)->update([
            'id_user_use' => $regInfo->id_user_reg,
            'active' => false
        ]);

        if($reg && $upCar) {
            return response()->json([
                'message' => 'Allow successfully!',
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
