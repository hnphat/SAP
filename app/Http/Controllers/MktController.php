<?php

namespace App\Http\Controllers;

use App\MarketingGuest;
use App\NhatKy;
use App\Group;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MktController extends Controller
{
    //
    public function index() {
        $gr = Group::all();
        return view("marketing.mkt", ['group' => $gr]);
    }

    public function postData(Request $request) {
        $mkt = new MarketingGuest();
        $mkt->hoTen = $request->ten;
        $mkt->dienThoai = $request->dienThoai;
        $mkt->nguonKH = $request->nguonKH;
        $mkt->yeuCau = $request->yeuCau;
        $mkt->id_user_create = Auth::user()->id;
        $mkt->save();
        if($mkt) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
            $nhatKy->noiDung = "Thêm khách hàng MKT; Họ tên: " . $request->ten . "; SĐT: " . $request->dienThoai;
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'message' => 'Inserted successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function deleteGuest(Request $request) {
        $temp = MarketingGuest::find($request->id);
        if ($temp->block) {
            return response()->json([
                'type' => 'error',
                'message' => 'Khách đã bị khoá không thể xoá!',
                'code' => 500
            ]);
        } else {
            $mkt = MarketingGuest::find($request->id);
            $mkt->delete();
            if($mkt) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
                $nhatKy->noiDung = "Xoá khách hàng mkt; Họ tên: " . $temp->hoTen . "; SĐT: " . $temp->dienThoai;
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'message' => 'Deleted successfully!',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Internal server fail!',
                    'code' => 500
                ]);
            }
        }        
    }

    public function setGroup(Request $request) {
        $temp = MarketingGuest::find($request->id);
        $gr = Group::find($request->id_group);
        $mkt = MarketingGuest::find($request->id);
        $mkt->id_group_send = $request->id_group;
        $mkt->block = true;
        $mkt->save();
        if($mkt) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
            $nhatKy->noiDung = "Gán khách hàng " . $temp->hoten . "; Số điện thoại: " . $temp->dienThoai . " cho nhóm " . $gr->name;
            $nhatKy->save();
            return response()->json([
                'type' => 'info',
                'message' => 'Đã gán khách hàng cho nhóm ' . $gr->name,
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }


    public function loadBaoCao(Request $request) {
        $_from = \HelpFunction::revertDate($request->tu);
        $_to = \HelpFunction::revertDate($request->den);

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "KINH DOANH - Khách hàng MKT";
        $nhatKy->noiDung = "Xem báo cáo khách hàng từ " . $_from . " đến " . $_to;
        $nhatKy->save();

        $mkt = MarketingGuest::select("*")->orderBy('id','desc')->get();
        $i = 1;
        foreach($mkt as $row) {
            $hasGroup = "";
            $hasSale = "";
            $hasPhone = substr($row->dienThoai,0,4) . "xxxxxxxx";
            if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
            &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                $gr = Group::find($row->id_group_send);
                if ($gr) {
                    $hasGroup = "<span class='text-bold text-primary'>".$gr->name."</span>";
                    if (Auth::user()->hasRole('truongnhomsale') || Auth::user()->hasRole('system'))
                        $hasSale = "<button data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setSale' data-toggle='modal' data-target='#saleModal' id='setSale' class='btn btn-info btn-sm'>Gán Sale</button>";    
                }
                else {
                    if (Auth::user()->hasRole('mkt') || Auth::user()->hasRole('system') || Auth::user()->hasRole('cskh') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss'))
                    $hasGroup = "<button data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setGroup' data-toggle='modal' data-target='#groupModal' class='btn btn-warning btn-sm'>Gán nhóm</button>";
                }

                if (Auth::user()->hasRole('mkt') || Auth::user()->hasRole('system') || Auth::user()->hasRole('cskh') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss')) {
                    $hasPhone = $row->dienThoai;
                }
                
                $sale = User::find($row->id_sale_recieve);
                if ($sale)
                    $hasSale = "<span class='text-bold text-info'>".$sale->userDetail->surname."</span>";
                
                echo "<tr>
                    <td>".($i++)."</td>
                    <td>".\HelpFunction::getDateRevertCreatedAt($row->created_at)."</td>
                    <td>".$row->user->userDetail->surname."</td>
                    <td>".$row->hoTen."</td>
                    <td>".$hasPhone."</td>
                    <td>".$row->nguonKH."</td>
                    <td>".$row->yeuCau."</td>
                    <td>
                        ".$hasGroup."
                    </td>
                    <td>
                        ".$hasSale."
                    </td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>Coding...</td>
                    <td>
                        <button data-id='".$row->id."' id='delete' class='btn btn-danger btn-sm'>Xoá</button>
                    </td>
                </tr>";
            }
        }
    }
}
