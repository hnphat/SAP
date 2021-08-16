<?php

namespace App\Http\Controllers;

use App\CarSale;
use App\TypeCarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhoController extends Controller
{
    //
    public function index() {
        $type_detail = TypeCarDetail::all()->sortBy('name');
        return view('page.kho', ['typecar' =>  $type_detail]);
    }

    public function getList() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->where('car_sale.exist',1)->get();
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

    public function getListOut() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->where([
            ['car_sale.exist','=',0],
            ['car_sale.order','=',0]
        ])->get();
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

    public function getListOrder() {
        $result = CarSale::select('car_sale.*','t.name as ten')->join('type_car_detail as t','car_sale.id_type_car_detail','=','t.id')->where([
            ['car_sale.order','=',1],
            ['car_sale.exist','=',0]
        ])->get();
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

    public function add(Request $request) {
        $package = new CarSale;

        $package->id_type_car_detail = $request->tenXe;
        $package->year = $request->nam;
        $package->vin = $request->vin;
        $package->frame = $request->frame;
        $package->color = $request->color;
        $package->gear = $request->gear;
        $package->machine = $request->machine;
        $package->seat = $request->seat;
        $package->fuel = $request->fuel;
        $package->cost = $request->cost;
        if ($request->exist == 1) {
            $package->exist = 1;
            $package->order = 0;
        }
        if ($request->exist == 2) {
            $package->exist = 0;
            $package->order = 1;
        }
        $package->id_user_create = Auth::user()->id;
        $package->save();

        if($package) {
            return response()->json([
                'message' => 'Insert data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = CarSale::where('id', $request->id)->delete();
        if($result) {
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
        $result = CarSale::where('id', $request->id)->first();
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
        $exist = 0;
        $order = 0;
        if ($request->eexist == 1) {
            $exist = 1;
            $order = 0;
        }
        if ($request->eexist == 2) {
            $exist = 0;
            $order = 1;
        }
        $result = CarSale::where('id', $request->eid)->update([
            "id_type_car_detail" => $request->etenXe,
            "year" => $request->enam,
            "vin" => $request->evin,
            "frame" => $request->eframe,
            "color" => $request->ecolor,
            "gear" => $request->egear,
            "machine" => $request->emachine,
            "seat" => $request->eseat,
            "fuel" => $request->efuel,
            "cost" => $request->ecost,
            'exist' => $exist,
            'order' => $order
        ]);
        if($result) {
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
