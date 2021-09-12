<?php

namespace App\Http\Controllers;

use App\CancelHD;
use App\CarSale;
use App\Sale;
use App\SaleOff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PheDuyetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Sale::select('sale.created_at','r.giaXe as cost','sale.id','u.name as salemen','g.name as surname','t.name','sale.complete','sale.admin_check','sale.lead_sale_check')
            ->join('guest as g','sale.id_guest','=','g.id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('request_hd as r','sale.id','=','r.sale_id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->join('users as u','sale.id_user_create','=','u.id')
            ->orderby('sale.id','desc')
            ->get();
        return view('page.pheduyet', ['hd' => $result]);
    }


    public function huy($sale,$user)
    {
        $cancel = CancelHD::where([
            ['sale_id','=',$sale],
            ['user_id','=',$user]
        ])->update([
            'cancel' => true
        ]);

        if ($cancel) {
            $hd = Sale::find($sale);
            $idCar = $hd->id_car_sale;
            $car = CarSale::find($idCar);
            $car->exist = 1;
            $car->save();
            if ($car)
                return redirect()->route('pheduyet.list')->with('thongbao','Đã phê duyệt hủy hợp đồng HAGI-0'.$sale.'/HDMB-PA');
            else
                return redirect()->route('pheduyet.list')->with('err','Đã phê duyệt hủy hợp đồng HAGI-0'.$sale.'/HDMB-PA Nhưng chưa hoàn trạng kho xe! Vui lòng gửi quản trị viên để xử lý!');
        }
        else
            return redirect()->route('pheduyet.list')->with('err','Phê duyệt hủy hợp đồng không thành côngc');
    }

    public function check($id) {
        if (Auth::user()->hasRole('adminsale'))
            $sale = Sale::where('id', $id)->update([
                'admin_check' => 1
            ]);

        if (Auth::user()->hasRole('tpkd'))
            $sale = Sale::where('id', $id)->update([
                'lead_sale_check' => 1
            ]);

        if (Auth::user()->hasRole('ketoan'))
            $sale = Sale::where('id', $id)->update([
                'complete' => 1
            ]);

        return $this->index();
    }

    public function detailHD($id) {
        $result = Sale::select('u.name as salemen','r.giaXe','r.tamUng','sale.id as idsale','sale.admin_check','sale.lead_sale_check','sale.complete','g.*','g.name as surname', 'c.*','t.name as name_car')
            ->join('guest as g','sale.id_guest','=','g.id')
            ->join('request_hd as r','sale.id','=','r.sale_id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->join('users as u','sale.id_user_create','=','u.id')
            ->where('sale.id', $id)
            ->orderby('sale.id','desc')
            ->first();

        $existCancel = Sale::find($id);

        if($result) {
            $cancel = [
                'cancel' => 0,
                'lyDoCancel' => null
            ];
            if($existCancel->cancelHd !== null)
                $cancel = [
                    'cancel' => $existCancel->cancelHd->cancel,
                    'lyDoCancel' => $existCancel->cancelHd->lyDoCancel
                ];
            return response()->json([
                'message' => 'Get Detail HD Success!',
                'code' => 200,
                'data' => $result,
                'cancel' => $cancel
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }
}
