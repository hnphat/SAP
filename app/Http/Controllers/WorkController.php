<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ReportWork;

class WorkController extends Controller
{
    //
    public function show() {
    	return view('work.work');
    }

    public function getWorkList() {
    	$result = ReportWork::where([
    		['user_tao','=',Auth::user()->id],
    		['isPersonal','=', true]
    	])->orderBy('id', 'desc')->get();
        if($result) {
            return response()->json([
            	'type' => "success",
                'message' => 'Get reports successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
               'type' => "error",
               'message' => 'Internal Server fail',
               'code' => 500
            ]);
        }
    }

    public function addWork(Request $request) {
        $reportWork = new ReportWork();
        $reportWork->user_tao = Auth::user()->id;
        $reportWork->ngayTao = Date('d-m-Y');
        $reportWork->tenCongViec = $request->tenCongViec;
        $reportWork->tienDo = $request->tienDo;
        $reportWork->ngayStart = $request->ngayStart;
        $reportWork->ngayEnd = $request->ngayEnd;
        $reportWork->ketQua = $request->ketQua;
        $reportWork->ghiChu = $request->ghiChu;
        $reportWork->save();
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã thêm công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể thêm công việc",
                'code' => 500
            ]);
        }
    }

    public function delWork(Request $request) {
        $reportWork = ReportWork::where([
        	['user_tao','=',Auth::user()->id],
    		['isPersonal','=', true],
    		['id','=', $request->id]
        ])->delete();
        
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã xóa công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể xóa công việc",
                'code' => 500
            ]);
        }
    }

    public function getworkedit($id) {
    	$reportWork = ReportWork::find($id);
    	if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã load công việc",
                'code' => 200,
                'data' => $reportWork
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể load công việc",
                'code' => 500
            ]);
        }
    }
}
