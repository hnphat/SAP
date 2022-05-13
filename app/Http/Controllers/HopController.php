<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HopTuan;
use App\HopTuanMem;
use App\NoiDungHop;
use App\NoiDungHopGopY;
use App\NoiDungHopMem;
use App\User;
use Illuminate\Support\Facades\Auth;
class HopController extends Controller
{
    //
    public function getQuanLy() {
        $user = User::select("*")->where("active", true)->get();
        return view('hoptuan.quanlyhop', ['user' => $user]);
    }

    public function getList() {
        $hop = HopTuan::all();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã tải danh sách cuộc họp!',
                'code' => 200,
                'data' => $hop
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi tải danh sách cuộc họp!',
                'code' => 500
            ]);
        }
    }

    public function postHop(Request $request) {
        $hop = new HopTuan();
        $hop->tenCuocHop = $request->tenCuocHop;
        $hop->id_user = Auth::user()->id;
        $hop->ngay = Date('d');
        $hop->thang = Date('m');
        $hop->nam = Date('Y');
        $hop->save();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã thêm cuộc họp mới!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể thêm cuộc họp!',
                'code' => 500
            ]);
        }
    }

    public function postEdit(Request $request) {
        $hop = HopTuan::find($request->idCuocHop);
        $hop->tenCuocHop = $request->etenCuocHop;
        $hop->save();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã chỉnh sửa cuộc họp!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể chỉnh sửa cuộc họp!',
                'code' => 500
            ]);
        }
    }

    public function deleteHop(Request $request) {
        $hop = HopTuan::find($request->id);      
        $hop->delete();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã xoá!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể xoá!',
                'code' => 500
            ]);
        }
    }

    public function deleteMem(Request $request) {
        $hop = HopTuanMem::where([
            ['id_hop','=',$request->idhop],
            ['id_user','=',$request->iduser]
        ]);      
        $hop->delete();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã xoá!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể xoá!',
                'code' => 500
            ]);
        }
    }

    public function loadMember(Request $request) {
        $mem = HopTuanMem::select("h.tenCuocHop","hop_tuan_mem.id_hop","hop_tuan_mem.id_user","d.surname")
        ->join("users as u","u.id","=","hop_tuan_mem.id_user")
        ->join("users_detail as d","u.id","=","d.id_user")
        ->join("hop_tuan as h","h.id","=","hop_tuan_mem.id_hop")
        ->where("id_hop", $request->id)
        ->get();
        if ($mem) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã tải thông tin thành viên!',
                'code' => 200,
                'data' => $mem,
                'idHop' => $request->id
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể tải thông tin!',
                'code' => 500
            ]);
        }
    }

    public function loadEdit(Request $request) {
        $hop = HopTuan::find($request->id);
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã tải thông tin họp!',
                'code' => 200,
                'data' => $hop
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể tải thông tin họp!',
                'code' => 500
            ]);
        }
    }

    public function postMember(Request $request) {
        $check = HopTuanMem::where([
            ['id_hop','=',$request->idHop],
            ['id_user','=',$request->thanhVien]
        ])->exists();
        if ($check) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Thành viên này đã được thêm!',
                'code' => 200
            ]);
        } else {
            $hop = new HopTuanMem();
            $hop->id_hop = $request->idHop;
            $hop->id_user = $request->thanhVien;
            $hop->save();
            if ($hop) {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thêm thành viên!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Lỗi không thể thêm thành viên!',
                    'code' => 500
                ]);
            }
        }
    }

    public function hopMoRong($id) {
        $hop = HopTuan::find($id);
        $user = HopTuanMem::where('id_hop', $id)->get();
        return view('hoptuan.morong', ['hop' => $hop, 'user' => $user]);
    }

    public function loadChiTiet(Request $request) {
        $noiDung = NoiDungHop::where("id_hop",$request->id)->get();
        $i = 1;
        foreach($noiDung as $row) {
            echo "
                <!-- Timeline -->
                <div class='timeline'>
                    <div class='time-label'>
                        <span class='bg-info'>Vấn đề 0".($i++).": ".$row->noiDungHop."</span>
                        <button id='delVanDe' data-idvande='".$row->id."' class='btn btn-danger btn-sm'>x</button>
                        <button id='themThanhVien' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#addModalMem' data-idnoidung='".$row->id."'>THÀNH VIÊN</button>
                    </div>";
                $gopy = NoiDungHopGopY::where("id_noidung", $row->id)->get();
                foreach($gopy as $_gopy) {
                    $user = User::find($_gopy->id_user);
                    echo "<!-- timeline item -->
                    <div>
                        <i class='fas fa-user bg-green'></i>
                        <div class='timeline-item'>
                            <span class='time'><i class='fas fa-clock'></i> ".\HelpFunction::revertCreatedAt($_gopy->created_at)."</span>
                            <h3 class='timeline-header no-border'>
                                <span class='text-primary text-bold'>".$user->userDetail->surname.":</span> 
                                <button class='btn btn-info btn-sm'>Sửa</button>
                                <button class='btn btn-danger btn-sm'>Xoá</button>                                              
                            </h3>
                            <div class='timeline-body'>
                                ".$_gopy->gopY."
                            </div>
                        </div>
                    </div>
                    <!-- END timeline item -->";
                }
             $stt = "";
             switch($row->status){
                case "DONE": {
                    $stt = "[HOÀN TẤT]";
                } break;
                case "PROCESS": {
                    $stt = "[ĐANG THỰC HIỆN]";
                } break;
                case "NEW": {
                    $stt = "[CHƯA XỬ LÝ]";
                } break;
             }       
                echo "<div>
                    <button class='btn btn-success btn-sm'>THÊM GÓP Ý</button>
                </div>                    
                <div>
                    <i class='fas fa-clock bg-gray'></i>
                </div>
            </div>
            <!-- Timeline end -->
            <!-- Kết luận -->
            <div class='card card-default'>
                <div class='card-header'>
                    <h3 class='card-title'>
                        <span class='text-danger text-bold'>".$stt."</span>
                        <i class='fas fa-bullhorn'></i>
                        <strong>Kết luận &nbsp;<button class='btn btn-info btn-sm'>CẬP NHẬT</button></strong>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class='card-body'>
                    <div class='callout callout-info'>
                        ".$row->ketLuan."
                    </div>";

                $hopMem = NoiDungHopMem::where('id_noidung', $row->id)->get();                
                foreach($hopMem as $_hopMem) {
                    $user = User::find($_hopMem->id_user);
                    $stt = ($_hopMem->xacNhan) ? "<span class='text-success text-bold'>Đã xác nhận</span>" : "<span class='text-danger text-bold'>Chưa xác nhận</span>";
                    echo "<p><strong>".$user->userDetail->surname.":</strong> 
                        ".$stt."
                    <button class='btn btn-warning btn-sm'>XÁC NHẬN</button></p>";
                }

                echo "</div>
                <!-- /.card-body -->
            </div>
            <!-- Kết luận end -->   
            ";
        }        
    }

    public function postVanDe(Request $request) {
        $vanDe = new NoiDungHop();
        $vanDe->noiDungHop = $request->tenVanDe;
        $vanDe->id_hop = $request->idHop2;
        $vanDe->status = "NEW";
        $vanDe->save();
        if ($vanDe) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã thêm vấn đề cần họp!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể thêm!',
                'code' => 500
            ]);
        }
    }

    public function xoaVanDe(Request $request) {
        $hop = NoiDungHop::where('id',$request->id)->delete();
        if ($hop) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã xoá!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể xoá!',
                'code' => 500
            ]);
        }
    }

    public function postMemVanDe(Request $request) {
        $check = NoiDungHopMem::where([
            ['id_noidung','=',$request->idNoiDung],
            ['id_user','=',$request->thanhVien]
        ])->exists();
        if ($check) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Thành viên này đã được thêm!',
                'code' => 200
            ]);
        } else {
            $mem = new NoiDungHopMem();
            $mem->id_noidung = $request->idNoiDung;
            $mem->id_user = $request->thanhVien;
            $mem->save();
            if ($mem) {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã thêm thành viên!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'info',
                    'message' => 'Lỗi không thể thêm thành viên!',
                    'code' => 500
                ]);
            }
        }
    }

    public function loadChiTietMem(Request $request) {
        $mem = NoiDungHopMem::select("noi_dung_hop_mem.xacNhan","noi_dung_hop_mem.id_noidung","noi_dung_hop_mem.id_user","d.surname")
        ->join("users as u","u.id","=","noi_dung_hop_mem.id_user")
        ->join("users_detail as d","u.id","=","d.id_user")       
        ->where("id_noidung",$request->noidung)
        ->get();
        if ($mem) {
            return response()->json([
                'type' => 'info',
                'message' => 'Đã tải thông tin thành viên!',
                'code' => 200,
                'data' => $mem
            ]);
        } else {
            return response()->json([
                'type' => 'info',
                'message' => 'Lỗi không thể tải thông tin!',
                'code' => 500
            ]);
        }
    }
}
