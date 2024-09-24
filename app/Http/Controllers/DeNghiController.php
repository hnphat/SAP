<?php

namespace App\Http\Controllers;

use App\BhPkPackage;
use App\CancelHD;
use App\CarSale;
use App\RequestHD;
use App\Sale;
use App\SaleOff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeNghiController extends Controller
{
    //
    public function index() {
        return view('page.denghi');
    }

    public function getListWaitAll() {
        $result = RequestHD::select('request_hd.user_id','request_hd.guest_id','request_hd.id','request_hd.car_detail_id','request_hd.color','request_hd.tamUng','request_hd.giaXe','request_hd.admin_check','u.name as user_name','t.name as carname','g.name as guestname')
            ->join('guest as g','request_hd.guest_id','=','g.id')
            ->join('type_car_detail as t','request_hd.car_detail_id','=','t.id')
            ->join('users as u','request_hd.user_id','=', 'u.id')
            ->orderby('request_hd.id','desc')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list wailt successfully!',
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

    public function show(Request $request) {
        $carSale = CarSale::select('*')->where([
            ['id_type_car_detail', '=', $request->id],
            ['color', 'like', $request->color],
        ])->get();
        if($carSale) {
            foreach($carSale as $row) {
                $checkExist = Sale::where('id_car_sale',$row->id)->exists();
                if ($checkExist) {
                    $sale = Sale::where('id_car_sale',$row->id)->first();
                    if ($sale->cancelHd === null)
                        continue;
                    elseif ($sale->cancelHd->cancel == 0) continue;
                }
                echo "<option value='".$row->id."'>".$row->typeCarDetail->name." ".$row->color." VIN ".$row->vin."</option>";
            }
        }
    }

    public function thuHoi(Request $request) {
        $r = RequestHD::find($request->id);
        $idSale = $r->sale_id;
        $idUser = $r->user_id;
        $check = Sale::where([
            ['id','=',$idSale],
            ['id_user_create','=',$idUser]
        ])->first();
        if ($check->lead_sale_check == 1)
            return response()->json([
                'message' => 'Quản lý đã phê duyệt hợp đồng không thể thu hồi!',
                'code' => 200
            ]);
        else {
            $car = CarSale::find($check->id_car_sale);
            $car->exist = true;
            $car->save();
            $saleOff = SaleOff::where('id_sale',$idSale)->get();
            foreach($saleOff as $row) {
                $del = SaleOff::where([
                    ['id_bh_pk_package','=',$row->id_bh_pk_package]
                ])->delete();
                $bhpk = BhPkPackage::where('id',$row->id_bh_pk_package)->delete();
            }
            $delCancel = CancelHD::where('sale_id',$idSale)->delete();
            $delSale = Sale::find($idSale);
            $delSale->delete();
            if ($delSale) {
                $r->admin_check = 0;
                $r->save();
                return response()->json([
                    'message' => 'Đã thu hồi thành công!',
                    'code' => 200
                ]);
            }
            else
                return response()->json([
                    'message' => 'Thu hồi không thành công!',
                    'code' => 500
                ]);
        }
    }

    public function pheDuyet(Request $request) {
        $sale = new Sale;
        $sale->id_guest = $request->idGuest;
        $sale->id_car_sale = $request->duyetXe;
        $sale->id_user_create = $request->idUserCreate;
        $sale->admin_check = true;
        $sale->save();
        $idSale = $sale->id;

        // --------------- Add 05 phụ kiện theo xe
        $pkpay = new BhPkPackage;
        $pkpay->name = "Áo trùm xe";
        $pkpay->cost = 0;
        $pkpay->id_user_create =$request->idUserCreate;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new BhPkPackage;
        $pkpay->name = "Bao tay lái";
        $pkpay->cost = 0;
        $pkpay->id_user_create = $request->idUserCreate;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new BhPkPackage;
        $pkpay->name = "Tappi sàn";
        $pkpay->cost = 0;
        $pkpay->id_user_create = $request->idUserCreate;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new BhPkPackage;
        $pkpay->name = "Khăn lau xe";
        $pkpay->cost = 0;
        $pkpay->id_user_create = $request->idUserCreate;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        $pkpay = new BhPkPackage;
        $pkpay->name = "Bình chữa cháy";
        $pkpay->cost = 0;
        $pkpay->id_user_create = $request->idUserCreate;
        $pkpay->type = 'free';
        $pkpay->save();

        if($pkpay) {
            $saleOff = new SaleOff;
            $saleOff->id_sale = $idSale;
            $saleOff->id_bh_pk_package = $pkpay->id;
            $saleOff->save();
        }

        // --------------- End add 05 phụ kiện theo xe
        $carSale = CarSale::where('id', $request->duyetXe)->update([
            'car_sale.exist' => false
        ]);

        $rq = RequestHD::where('id', $request->idRequest)->update([
            'admin_check' => true,
            'sale_id' => $idSale
        ]);

        if($rq && $carSale) {
            return response()->json([
                'message' => 'Update successfully!',
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
