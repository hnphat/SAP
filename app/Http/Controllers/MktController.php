<?php

namespace App\Http\Controllers;

use App\MarketingGuest;
use App\NhatKy;
use App\Group;
use App\Guest;
use App\GroupSale;
use App\HopDong;
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
        $gr = Group::find($request->chonNhom);
        $mkt = new MarketingGuest();
        $mkt->hoTen = $request->ten;
        $mkt->dienThoai = $request->dienThoai;
        $mkt->nguonKH = $request->nguonKH;
        $mkt->yeuCau = $request->yeuCau;
        $mkt->id_user_create = Auth::user()->id;
        $mkt->id_group_send = $request->chonNhom;
        $mkt->block = true;
        $mkt->save();
        if($mkt) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
            $nhatKy->noiDung = "Thêm khách hàng MKT; Họ tên: " . $request->ten . "; SĐT: " . $request->dienThoai . " gán vào nhóm " . $gr->name;
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

    public function revertGuest(Request $request) {
        $flag = false;
        $temp = MarketingGuest::find($request->id);
        $idguest = ($temp->id_guest_temp) ? $temp->id_guest_temp : 0;
        // Xử lý trạng thái khách hàng
        $hd = HopDong::where('id_guest',$temp->id_guest_temp)->exists();               
        if ($hd) {
            $flag = true; 
        }  
        // Nếu chưa gán khách cho sale thì recall được
        if ($flag) {
            return response()->json([
                'type' => 'warning',
                'message' => 'Khách hàng này đang được sử dụng trong đề nghị/hợp đồng không thể recall!',
                'code' => 200
            ]);
        } else {
            $guest = Guest::find($idguest);
            ($idguest != 0) ? $guest->delete() : "";

            $mkt = MarketingGuest::find($request->id);
            $mkt->id_group_send = null;
            $mkt->id_sale_recieve  = null;
            $mkt->id_guest_temp = null;
            $mkt->block = false;
            $mkt->save();
            
            if($mkt) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
                $nhatKy->noiDung = "Recall khách hàng mkt; Họ tên: " . $temp->hoTen . "; SĐT: " . $temp->dienThoai;
                $nhatKy->save();
                return response()->json([
                    'type' => 'info',
                    'message' => 'Đã recall thành công!',
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

    public function setSale(Request $request) {
        $idlastest = "";
        $us = User::find($request->id_sale);
        $temp = MarketingGuest::find($request->id);
        $mkt = MarketingGuest::find($request->id);
        $mkt->id_sale_recieve = $request->id_sale;
        $mkt->save();
        if($mkt) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Kinh doanh - Khách hàng MKT";
            $nhatKy->noiDung = "Gán khách hàng " . $temp->hoTen . "; Số điện thoại: " . $temp->dienThoai . " cho sale " . $us->userDetail->surname;
            $nhatKy->save();
            // Xử lý tự động thêm khách vào cho sale
            $guest = new Guest();
            $guest->id_type_guest = 1;
            $guest->name = $temp->hoTen;          
            $guest->phone = $temp->dienThoai;
            $guest->address = "Chưa có thông tin";
            $guest->id_user_create = $request->id_sale;
            $guest->nguon = "Marketing";
            $guest->save();
            $idlastest = $guest->id;
            if($guest) {              
                $mkt2 = MarketingGuest::find($request->id);
                $mkt2->id_guest_temp = $idlastest;
                $mkt2->save();  
                if($mkt2) {  
                    return response()->json([
                        'type' => 'info',
                        'message' => 'Đã gán khách hàng cho sale ' . $us->userDetail->surname,
                        'code' => 200
                    ]);
                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Lỗi phân đoạn cập nhật vào bảng MKT id_guest_temp sau khi gán!',
                        'code' => 500
                    ]);
                }
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi phân đoạn thêm khách vào bảng Guest!',
                    'code' => 500
                ]);
            }
            // -------------------------------------           
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi phân đoạn cập nhật khách vào bảng MKT!',
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
            $stt = "";
            $status = "Null";
            $hasPhone = substr($row->dienThoai,0,4) . "xxxxxxxx";
            $idgroup = 0;
            
            if (Auth::user()->hasRole('truongnhomsale')) {
                $gr = GroupSale::where('user_id', Auth::user()->id)->first();
                $idgroup = ($gr) ? $gr->group_id : 0;
            }

            if ($idgroup != 0) {
                // Dành cho quyền khác trưởng nhóm
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to)) && $idgroup == $row->id_group_send) {
                    $gr = Group::find($row->id_group_send);
                    if ($gr) {
                        $hasGroup = "<span class='text-bold text-primary'>".$gr->name."</span>";
                        if (Auth::user()->hasRole('truongnhomsale') || Auth::user()->hasRole('system'))
                            $hasSale = "<button data-groupid='".$row->id_group_send."' data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setSale' data-toggle='modal' data-target='#saleModal' id='setSale' class='btn btn-info btn-sm'>Gán Sale</button>";    
                    }
                    else {
                        if (Auth::user()->hasRole('mkt') || Auth::user()->hasRole('system') || Auth::user()->hasRole('cskh') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss'))
                        $hasGroup = "<button data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setGroup' data-toggle='modal' data-target='#groupModal' class='btn btn-warning btn-sm'>Gán nhóm</button>";
                    }

                    if (Auth::user()->hasRole('system') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss') || Auth::user()->id == $row->id_user_create) {
                        $hasPhone = $row->dienThoai;
                    }
                    
                    $sale = User::find($row->id_sale_recieve);
                    $guest = Guest::find($row->id_guest_temp);
                    if ($sale)
                        $hasSale = "<span class='text-bold text-info'>".$sale->userDetail->surname."</span>";
                    // Xử lý trạng thái khách hàng
                    $hd = HopDong::select("hop_dong.*")
                    ->where('id_guest',$row->id_guest_temp)
                    ->orderBy('id','desc')
                    ->first();
                    
                    if ($hd) {
                        if ($hd->hdDaiLy == true && $hd->lead_check == true && $hd->lead_check_cancel == false) {
                            $status = "<strong class='text-warning'>Hợp đồng đại lý</strong>";
                        } elseif ($hd->lead_check_cancel == true) {
                            $status = "<strong class='text-danger'>Hợp đồng huỷ</strong>";
                        } else {
                            if ($hd->requestCheck == false) 
                            $status = "<strong class='text-secondary'>Mới tạo</strong>";
                            elseif ($hd->requestCheck == true && $hd->admin_check == false)
                                $status = "<strong class='text-info'>Đợi duyệt (Admin)</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == false)
                                $status = "<strong class='text-primary'>Đợi duyệt (TPKD)</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == true 
                            && $hd->hdWait == true)
                                $status = "<strong class='text-pink'>Hợp đồng chờ</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == true 
                            && $hd->hdWait == false)
                                $status = "<strong class='text-success'>Hợp đồng ký</strong>";
                        }    
                    }
                    //-------------------------
                    if ($guest) {
                        switch($guest->danhGia) {
                            case "COLD": $stt = "<strong class='text-blue'>".$guest->danhGia."</strong>"; break;
                            case "WARM": $stt = "<strong class='text-orange'>".$guest->danhGia."</strong>"; break;
                            case "HOT": $stt = "<strong class='text-red'>".$guest->danhGia."</strong>"; break;
                        }
                    }                
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
                        <td>".($guest ? \HelpFunction::getDateRevertCreatedAt($guest->created_at) : "")."</td>
                        <td>".$stt."</td>
                        <td>".($guest ? $guest->xeQuanTam : "")."</td>
                        <td>".($guest ? $guest->cs1 : "")."</td>
                        <td>".($guest ? $guest->cs2 : "")."</td>
                        <td>".($guest ? $guest->cs3 : "")."</td>
                        <td>".($guest ? $guest->cs4 : "")."</td>
                        <td>".$status."</td>
                        <td>
                            ".(Auth::user()->hasRole('system') ? "<button data-id='".$row->id."' id='delete' class='btn btn-danger btn-sm'>Xoá</button> <br/><br/>  <button data-id='".$row->id."' id='revert' class='btn btn-primary btn-sm'>Recall</button>" : "")."
                        </td>
                    </tr>";
                }
            } else {
                // Dành cho quyền trưởng nhóm
                if ((strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) >= strtotime($_from)) 
                &&  (strtotime(\HelpFunction::getDateRevertCreatedAt($row->created_at)) <= strtotime($_to))) {
                    $gr = Group::find($row->id_group_send);
                    if ($gr) {
                        $hasGroup = "<span class='text-bold text-primary'>".$gr->name."</span>";
                        if (Auth::user()->hasRole('truongnhomsale') || Auth::user()->hasRole('system'))
                            $hasSale = "<button data-groupid='".$row->id_group_send."' data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setSale' data-toggle='modal' data-target='#saleModal' id='setSale' class='btn btn-info btn-sm'>Gán Sale</button>";    
                    }
                    else {
                        if (Auth::user()->hasRole('mkt') || Auth::user()->hasRole('system') || Auth::user()->hasRole('cskh') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss'))
                        $hasGroup = "<button data-phone='".$row->dienThoai."' data-hoten='".$row->hoTen."' data-id='".$row->id."' id='setGroup' data-toggle='modal' data-target='#groupModal' class='btn btn-warning btn-sm'>Gán nhóm</button>";
                    }

                    if (Auth::user()->hasRole('system') || Auth::user()->hasRole('tpkd') || Auth::user()->hasRole('boss') || Auth::user()->id == $row->id_user_create) {
                        $hasPhone = $row->dienThoai;
                    }
                    
                    $sale = User::find($row->id_sale_recieve);
                    $guest = Guest::find($row->id_guest_temp);
                    if ($sale)
                        $hasSale = "<span class='text-bold text-info'>".$sale->userDetail->surname."</span>";
                    // Xử lý trạng thái khách hàng
                    $hd = HopDong::select("hop_dong.*")
                    ->where('id_guest',$row->id_guest_temp)
                    ->orderBy('id','desc')
                    ->first();
                    
                    if ($hd) {
                        if ($hd->hdDaiLy == true && $hd->lead_check == true && $hd->lead_check_cancel == false) {
                            $status = "<strong class='text-warning'>Hợp đồng đại lý</strong>";
                        } elseif ($hd->lead_check_cancel == true) {
                            $status = "<strong class='text-danger'>Hợp đồng huỷ</strong>";
                        } else {
                            if ($hd->requestCheck == false) 
                            $status = "<strong class='text-secondary'>Mới tạo</strong>";
                            elseif ($hd->requestCheck == true && $hd->admin_check == false)
                                $status = "<strong class='text-info'>Đợi duyệt (Admin)</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == false)
                                $status = "<strong class='text-primary'>Đợi duyệt (TPKD)</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == true 
                            && $hd->hdWait == true)
                                $status = "<strong class='text-pink'>Hợp đồng chờ</strong>";
                            elseif ($hd->requestCheck == true 
                            && $hd->admin_check == true 
                            && $hd->lead_check == true 
                            && $hd->hdWait == false)
                                $status = "<strong class='text-success'>Hợp đồng ký</strong>";
                        }    
                    }
                    //-------------------------
                    if ($guest) {
                        switch($guest->danhGia) {
                            case "COLD": $stt = "<strong class='text-blue'>".$guest->danhGia."</strong>"; break;
                            case "WARM": $stt = "<strong class='text-orange'>".$guest->danhGia."</strong>"; break;
                            case "HOT": $stt = "<strong class='text-red'>".$guest->danhGia."</strong>"; break;
                        }
                    }                
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
                        <td>".($guest ? \HelpFunction::getDateRevertCreatedAt($guest->created_at) : "")."</td>
                        <td>".$stt."</td>
                        <td>".($guest ? $guest->xeQuanTam : "")."</td>
                        <td>".($guest ? $guest->cs1 : "")."</td>
                        <td>".($guest ? $guest->cs2 : "")."</td>
                        <td>".($guest ? $guest->cs3 : "")."</td>
                        <td>".($guest ? $guest->cs4 : "")."</td>
                        <td>".$status."</td>
                        <td>
                            ".(Auth::user()->hasRole('system') ? "<button data-id='".$row->id."' id='delete' class='btn btn-danger btn-sm'>Xoá</button> <br/><br/>  <button data-id='".$row->id."' id='revert' class='btn btn-primary btn-sm'>Recall</button>" : "")."
                        </td>
                    </tr>";
                }
            }
        }
    }

    public function getSaleList(Request $request) {
        $salelist = [];
        $gr = GroupSale::where('group_id',$request->idgroup)->get();
        foreach($gr as $row){
            $us = User::find($row->user_id);
            array_push($salelist, [
                'idsale' => $us->id,
                'hoten' => $us->userDetail->surname
            ]);
        }
        if($gr) {            
            return response()->json([
                'type' => 'info',
                'message' => 'Load list saler success!',
                'code' => 200,
                'data' => $salelist
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