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

    public function change(Request $request) {
        $check = XeLaiThu::find($request->id);
        if ($check->status == 'DSD')
            return response()->json([
                'message' => 'Xe đang được sử dụng không thể chuyển trạng thái!',
                'code' => 200
            ]);
        else {
            $stt = ($check->status == 'T') ? 'DSC' : 'T';
            $car = XeLaiThu::where('id', $request->id)->update([
                'status' => $stt
            ]);
            if($car) {
                return response()->json([
                    'message' => 'Đã chuyển trạng thái xe!',
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

    public function showReg() {
        $car = XeLaiThu::all();
        $reg = DangKySuDung::select('*')->orderBy('id', 'DESC')->get();
        $traXe = DangKySuDung::select('*')->where('allow', true)->orderBy('id', 'DESC')->get();
        return view('laithu.reg', ['car' => $car, 'reg' => $reg, 'traXe' => $traXe]);
    }

    public function pay($id) {
        $car = DangKySuDung::find($id);
        if($car) {
            return response()->json([
                'message' => 'Get data successfully!',
                'code' => 200,
                'data' => $car
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function postPay(Request $request) {
        $reg = DangKySuDung::find($request->_idOff);
        $reg->tra_km_current = $request->_km;
        $reg->tra_fuel_current = $request->_xang;
        $reg->tra_car_status = $request->_trangThaiXe;
        $reg->request_tra = true;
        if ($reg->date_return == null)
            $reg->date_return = Date('H:i d-m-Y');
        $reg->save();
        if($reg) {
            return redirect()->route('laithu.reg')->with('succ','Đã gửi yêu cầu trả xe!');
        } else {
            return redirect()->route('laithu.reg')->with('err','Không thể gửi yêu cầu trả xe!');
        }
    }

    public function postReg(Request $request) {
        $check = XeLaiThu::find($request->xe);
        if ($check->status == 'T') {
            $reg = new DangKySuDung();
            $reg->id_user_reg = Auth::user()->id;
            $reg->id_xe_lai_thu = $request->xe;
            $reg->lyDo = $request->lyDo;
            $reg->km_current = $request->km;
            $reg->fuel_current = $request->xang;
            $reg->car_status = $request->trangThaiXe;
            $reg->date_go = $request->timeGo;
            if ($request->fuelRequest == 'on') {
                $reg->fuel_request = true;
                $reg->fuel_type = $request->fuelType;
                $reg->fuel_num = $request->fuelNum;
                $reg->fuel_lyDo = $request->fuelLyDo;
            }
            $reg->save();
            if($reg) {
                return redirect()->route('laithu.reg')->with('succ','Đã đăng ký xe lái thử');
            } else {
                return redirect()->route('laithu.reg')->with('err','Không thể đăng ký xe lái thử');
            }
        } elseif($check->status == 'DSC') {
            return redirect()->route('laithu.reg')->with('err','Xe này đăng trong tình trạng sửa chữa không thể đăng ký sử dụng');
        } else {
            return redirect()->route('laithu.reg')->with('err','Xe này đang được sử dụng bởi ' .$check->user->userDetail->surname. ' không thể đăng ký');
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
        $traXe = DangKySuDung::select('*')->where('request_tra', true)->orderBy('id', 'DESC')->get();
        return view('laithu.duyet', ['reg' => $reg, 'traXe' => $traXe]);
    }

    public function showCapXang() {
        $reg = DangKySuDung::select('*')->where('fuel_request', true)->orderBy('id', 'DESC')->get();
        return view('laithu.capxang', ['reg' => $reg]);
    }

    public function allowLaiThu(Request $request) {
        $regInfo = DangKySuDung::find($request->id);
        $check = XeLaiThu::find($regInfo->id_xe_lai_thu);
        if ($check->status == 'T') {
            $reg = DangKySuDung::where('id', $request->id)->update([
                "allow" => true
            ]);

            $upCar = XeLaiThu::where('id', $regInfo->id_xe_lai_thu)->update([
                'id_user_use' => $regInfo->id_user_reg,
                'status' => 'DSD'
            ]);

            if($reg && $upCar) {
                return response()->json([
                    'message' => 'Đã phê duyệt sử dụng xe lái thử!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        } elseif($check->status == 'DSC') {
            return response()->json([
                'message' => 'Xe đang được sửa chữa!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Xe đang được sử dụng!',
                'code' => 200
            ]);
        }
    }

    public function approve(Request $request) {
        $car = DangKySuDung::find($request->id);
        $car->tra_allow = true;
        $car->request_tra = true;
        $car->save();

        $upCar = XeLaiThu::where('id', $car->id_xe_lai_thu)->update([
            'status' => 'T'
        ]);

        if($car && $upCar) {
            return response()->json([
                'message' => 'Nhận xe thành công',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function getStatus() {
        $car = XeLaiThu::all();
        return view('laithu.status',['car' => $car]);
    }
}
