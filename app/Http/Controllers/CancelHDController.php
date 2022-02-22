<?php

namespace App\Http\Controllers;

use App\CancelHD;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancelHDController extends Controller
{
    //
    public function index() {
        $result = Sale::select('sale.created_at','r.giaXe as cost','sale.id','u.name as salemen','g.name as surname','t.name','sale.complete','sale.admin_check','sale.lead_sale_check')
            ->join('guest as g','sale.id_guest','=','g.id')
            ->join('car_sale as c','sale.id_car_sale','=','c.id')
            ->join('request_hd as r','sale.id','=','r.sale_id')
            ->join('type_car_detail as t','c.id_type_car_detail','=','t.id')
            ->join('users as u','sale.id_user_create','=','u.id')
            ->where('u.id','=', Auth::user()->id)
            ->orderby('sale.id','desc')
            ->get();
        $cancel = CancelHD::all()->where('user_id',Auth::user()->id)->sortByDesc('id');
        return view('page.cancel', ['hd' => $result, 'cancel' => $cancel]);
    }

    public function postCancel(Request $request) {
       $checkCancel = CancelHD::where([
           ['user_id','=', Auth::user()->id],
           ['sale_id','=', $request->chonHD]
       ])->exists();
       if ($checkCancel) {
           return redirect()
           ->route('cancel.list')
           ->with('err','[LỖI TRÙNG LẮP]: Hợp đồng HAGI-0'.$request->chonHD.'/HDMB-PA đã được yêu cầu hủy trên hệ thống!');
       } else {
           $cancel = new CancelHD();
           $cancel->user_id = Auth::user()->id;
           $cancel->sale_id = $request->chonHD;
           $cancel->lyDoCancel = $request->lyDoCancel;
           $cancel->save();
           if ($cancel) {
               return $this->index();
           }
       }
    }

    public function delCancel($id) {
        $check = CancelHD::find($id);
        if ($check->cancel == 1)
            return $this->index();
        else {
            $cancel = CancelHD::where('id', $id)->delete();
            return $this->index();
        }
        return $this->index();
    }
}
