<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ReportWork;
use App\User;
use App\EventReal;

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

    public function editWork(Request $request) {
        $reportWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['id','=', $request->_id]
        ])->orWhere([
            ['user_nhan','=',Auth::user()->id],
            ['id','=', $request->_id]
        ])->update([
            "tenCongViec" => $request->_tenCongViec,
            "tienDo" => $request->_tienDo,
            "ngayEnd" => $request->_ngayEnd,
            "ketQua" => $request->_ketQua,
            "ghiChu" => $request->_ghiChu
        ]);

        $eventReal = new EventReal;
        $eventReal->name = "Work";
        $eventReal->save();

        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã chỉnh sửa công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể chỉnh sửa công việc",
                'code' => 500
            ]);
        }
    }

      public function editWorking(Request $request) {
        $reportWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['id','=', $request->_id]
        ])->orWhere([
            ['user_nhan','=',Auth::user()->id],
            ['id','=', $request->_id]
        ])->update([
            "tenCongViec" => $request->_tenCongViec,
            "tienDo" => $request->_tienDo,
            "ketQua" => $request->_ketQua,
            "ghiChu" => $request->_ghiChu
        ]);

        $eventReal = new EventReal;
        $eventReal->name = "Work";
        $eventReal->save();

        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã chỉnh sửa công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể chỉnh sửa công việc",
                'code' => 500
            ]);
        }
    }

    public function checkWork(Request $request) {
        $current = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['isPersonal','=', true],
            ['id','=', $request->id]
        ])->first();
        if ($current != null) {
            $newVal = ($current->isReport == true) ? false : true;
            $reportWork = ReportWork::where([
                ['user_tao','=',Auth::user()->id],
                ['isPersonal','=', true],
                ['id','=', $request->id]
            ])->update([
                'isReport' => $newVal
            ]);

            if($reportWork) {
                return response()->json([
                    'type' => 'success',
                    'message' => ($newVal == true) ? " Added!" : " Deleted!",
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => " Không thao tác trên báo cáo",
                    'code' => 500
                ]);
            }
        } else {
            $current = ReportWork::where([
                ['user_nhan','=',Auth::user()->id],
                ['isPersonal','=', false],
                ['id','=', $request->id]
            ])->first();
            $newVal = ($current->isReport == true) ? false : true;
            $reportWork = ReportWork::where([
                ['user_nhan','=',Auth::user()->id],
                ['isPersonal','=', false],
                ['id','=', $request->id]
            ])->update([
                'isReport' => $newVal
            ]);

            if($reportWork) {
                return response()->json([
                    'type' => 'success',
                    'message' => ($newVal == true) ? " Added!" : " Deleted!",
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => " Không thao tác trên báo cáo",
                    'code' => 500
                ]);
            }
        }
    }

    public function complete() {
        return view('work.complete');
    }

    public function showComplete() {
        $result = ReportWork::select("report_work.*","d.surname as surname")
         ->join('users_detail as d','d.id_user','=','report_work.user_tao')
         ->where([
            ['user_tao','=',Auth::user()->id],
            ['tienDo','=', 100]
        ])->orWhere([
            ['user_nhan','=',Auth::user()->id],
            ['tienDo','=', 100]
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

    public function working() {
        return view('work.working');
    }

    public function showWorking() {
        $result = ReportWork::select("report_work.*","d.surname as surname")
         ->join('users_detail as d','d.id_user','=','report_work.user_tao')
         ->where([
            ['user_tao','=',Auth::user()->id],
            ['tienDo','<', 100],
            ['isPersonal','=', true]
        ])->orWhere([
            ['user_nhan','=',Auth::user()->id],
            ['tienDo','<', 100],
            ['isPersonal','=', false],
            ['apply','=', true]
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

    public function pushWork() {
        $user = User::where('id','!=', 1)->get();
        return view('work.giaoviec', ['user' => $user]);
    }

    public function showPushWork() {
         $result = ReportWork::select("report_work.*","d.surname as surname")
         ->join('users as u','report_work.user_nhan','=','u.id')
         ->join('users_detail as d','d.id_user','=','u.id')
         ->where([
            ['user_tao','=',Auth::user()->id],
            ['isPersonal','=', false]
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

      public function addPushWork(Request $request) {
        $reportWork = new ReportWork();
        $reportWork->user_tao = Auth::user()->id;
        $reportWork->user_nhan = $request->giaoCho;
        $reportWork->ngayTao = Date('d-m-Y');
        $reportWork->tenCongViec = $request->tenCongViec;
        $reportWork->ngayStart = $request->ngayStart;
        $reportWork->ngayEnd = $request->ngayEnd;
        $reportWork->requestWork = $request->yeuCau;
        $reportWork->isPersonal = false;
        $reportWork->save();
        $eventReal = new EventReal;
        $eventReal->name = "Work";
        $eventReal->save();
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã giao việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thể giao việc",
                'code' => 500
            ]);
        }
    }

    public function editPushWork(Request $request) {

        $checkGetWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['id','=', $request->_id],
            ['apply','=',true]
        ])->exists();

        if (!$checkGetWork) {
            $reportWork = ReportWork::where([
                ['user_tao','=',Auth::user()->id],
                ['id','=', $request->_id]
            ])->update([
                "tenCongViec" => $request->_tenCongViec,
                "ngayStart" => $request->_ngayStart,
                "ngayEnd" => $request->_ngayEnd,
                "requestWork" => $request->_yeuCau,
                "apply" => null
            ]);
            $eventReal = new EventReal;
            $eventReal->name = "Work";
            $eventReal->save();
            if($reportWork) {
                return response()->json([
                    'type' => 'success',
                    'message' => " Đã chỉnh sửa giao việc",
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => " Không thể chỉnh sửa giao việc",
                    'code' => 500
                ]);
            }
        } else {
            return response()->json([
                'type' => 'info',
                'message' => " Công việc đã được nhận không thể chỉnh sửa",
                'code' => 200
            ]);
        }
    }

    public function delPushWork(Request $request) {
            $reportWork = ReportWork::where([
                ['user_tao','=',Auth::user()->id],
                ['isPersonal','=', false],
                ['id','=', $request->id]
            ])->delete();
            $eventReal = new EventReal;
            $eventReal->name = "Work";
            $eventReal->save();
            if($reportWork) {
                return response()->json([
                    'type' => 'success',
                    'message' => " Đã xóa giao việc",
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'message' => " Không thể xóa giao việc",
                    'code' => 500
                ]);
            }
    }

    public function checkPushWork(Request $request) {
        $current = ReportWork::where([
            ['user_tao','=', Auth::user()->id],
            ['isPersonal','=', false],
            ['id','=', $request->id]
        ])->first();
        $newVal = ($current->isReportPush == true) ? false : true;
        $reportWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['isPersonal','=', false],
            ['id','=', $request->id]
        ])->update([
            'isReportPush' => $newVal
        ]);

        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => ($newVal == true) ? " Added!" : " Deleted!",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Không thao tác trên báo cáo",
                'code' => 500
            ]);
        }
    }

    public function approve(Request $request) {
        $reportWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['id','=', $request->_idPhanHoi]
        ])->update([
            "replyWork" => $request->phanHoi,
            "acceptApply" => true
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Work";
        $eventReal->save();
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã chấp nhận kết quả công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Lỗi! Không thể thay đổi",
                'code' => 500
            ]);
        }
    }

    public function noApprove(Request $request) {
        $reportWork = ReportWork::where([
            ['user_tao','=',Auth::user()->id],
            ['id','=', $request->_idPhanHoi]
        ])->update([
            "replyWork" => $request->phanHoi,
            "tienDo" => 99
        ]);
        $eventReal = new EventReal;
        $eventReal->name = "Work";
        $eventReal->save();
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã từ chối kết quả công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Lỗi! Không thể thay đổi",
                'code' => 500
            ]);
        }
    }

    public function getWork() {
        return view('work.nhanviec');
    }

    public function getWorkDetail() {
        $result = ReportWork::select("report_work.*","d.surname as surname")
         ->join('users as u','report_work.user_tao','=','u.id')
         ->join('users_detail as d','d.id_user','=','u.id')
         ->where([
            ['user_nhan','=',Auth::user()->id],
            ['isPersonal','=', false],
            ['apply','=', null]
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

    public function getNoApprove(Request $request) {
        $reportWork = ReportWork::where([
            ['id','=', $request->_id]
        ])->update([
            "ketQua" => $request->ketQua,
            "ghiChu" => $request->ghiChu,
            "apply" => false
        ]);
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã từ chối công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Lỗi! Không thể từ chối công việc",
                'code' => 500
            ]);
        }
    }

    public function getApprove(Request $request) {
        $reportWork = ReportWork::where([
            ['id','=', $request->id]
        ])->update([
            "apply" => true
        ]);
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Đã nhận công việc",
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Lỗi! Không thể nhận công việc",
                'code' => 500
            ]);
        }
    }

    public function viewMore($id) {
        $reportWork = ReportWork::find($id);
        if($reportWork) {
            return response()->json([
                'type' => 'success',
                'message' => " Loaded!",
                'code' => 200,
                'data' => $reportWork
            ]);
        } else {
            return response()->json([
                'type' => 'warning',
                'message' => " Error!",
                'code' => 500
            ]);
        }
    }
}
