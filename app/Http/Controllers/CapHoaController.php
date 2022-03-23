<?php

namespace App\Http\Controllers;

use App\CapHoa;
use App\User;
use App\NhatKy;
use App\Mail\CapHoaEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CapHoaController extends Controller
{
    //
    public function getPanel() {
        if (Auth::user()->hasRole('system'))
            $capHoa = CapHoa::select("*")->orderBy('id', 'desc')->get();
        else
            $capHoa = CapHoa::select("*")->where('id_user',Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('caphoa.denghi', ['hoa' => $capHoa]);
    }

    public function postCapHoa(Request $request) {
        $exe = new CapHoa();
        $exe->id_user = Auth::user()->id;
        $exe->khachHang = $request->khachHang;
        $exe->dongXe = $request->dongXe;
        $exe->num = $request->num;
        $exe->gioGiaoXe = $request->gioGiaoXe;
        $exe->ngayGiaoXe = $request->ngayGiaoXe;
        $exe->ghiChu = $request->ghiChu;
        $exe->ngayGiaoHoa = $request->ngayGiaoHoa;
        //--- suport email
        $user = User::find(Auth::user()->id);
        $nhanVien = $user->userDetail->surname;
        $khachHang = $request->khachHang;
        $dongXe = $request->dongXe;
        $num = $request->num;
        $gioGiaoXe = $request->gioGiaoXe;
        $ngayGiaoXe = \HelpFunction::revertDate($request->ngayGiaoXe);
        $ghiChu = $request->ghiChu;
        //----
        try {
            $exe->save();
            if ($exe) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Hành chính - quản lý cấp hoa";
                $nhatKy->noiDung = "Thêm yêu cầu cấp hoa<br/>Khách hàng: ".$request->khachHang."<br/>Dòng xe: "
                .$request->dongXe."<br/>Biển số/số khung: ". $request->num."<br/>Giờ giao: "
                .$request->gioGiaoXe."<br/>Ngày giao xe: ". \HelpFunction::revertDate($request->ngayGiaoXe);
                $nhatKy->save();
                //-----
                Mail::to("phongnhansu@hyundaiangiang.com")
                ->send(new CapHoaEmail(["Phòng hành chính",$nhanVien, $khachHang,$dongXe,$num,$gioGiaoXe,$ngayGiaoXe,$ghiChu]));
                //-----
                return redirect()->route('caphoa.panel')->with('succ','Đã gửi đề nghị cấp hoa');
            } else
                return redirect()->route('caphoa.panel')->with('err','Không thể gửi đề nghị cấp hoa');
        } catch (Exception $ex) {
            return redirect()->route('caphoa.panel')->with('err','Không thể gửi đề nghị cấp hoa. Thông tin lỗi: ' . $ex->getMessage());
        }        
    }

    public function delCapHoa(Request $request) {
        $exe = CapHoa::find($request->id);
        $temp = $exe;
        if (Auth::user()->hasRole('system'))
            $exe->delete();
        else {
            if ($exe->duyet == false) 
                $exe->delete();
            else
                return response()->json([
                    'code' => 500,
                    'type' => 'error',
                    'message' => 'Phiếu đã được duyệt không thể xóa!p'
                ]);
        }       
        if ($exe) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - quản lý cấp hoa";
            $nhatKy->noiDung = "Xóa cấp hoa<br/>Khách hàng: ".$temp->khachHang."<br/>Dòng xe: "
            .$temp->dongXe."<br/>Biển số/số khung: ". $temp->num."<br/>Giờ giao: "
            .$temp->gioGiaoXe."<br/>Ngày giao xe: ". \HelpFunction::revertDate($temp->ngayGiaoXe)
            ."<br/> Ngày giao hoa: " . \HelpFunction::revertDate($temp->ngayGiaoHoa) 
            . "<br/>Trạng thái duyệt (1: Đã duyệt; 0: Chưa duyệt" . $temp->duyet;
            $nhatKy->save();
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => 'Đã xóa phiếu'
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể xóa phiếu này'
            ]);
        }
    }

    public function duyetCapHoa(Request $request) {
        $exe = CapHoa::find($request->id);
        if (Auth::user()->hasRole('system') || Auth::user()->hasRole('hcns')) {
            $exe->duyet = true;
            $exe->ngayGiaoHoa = $request->ngayGiaoHoa;
            $exe->save();
        }   
        if ($exe) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Hành chính - quản lý cấp hoa";
            $nhatKy->noiDung = "Phê duyệt cấp hoa<br/>Khách hàng: ".$exe->khachHang."<br/>Dòng xe: "
            .$exe->dongXe."<br/>Biển số/số khung: ". $exe->num."<br/>Giờ giao: "
            .$exe->gioGiaoXe."<br/>Ngày giao xe: ". \HelpFunction::revertDate($exe->ngayGiaoXe)
            ."<br/> Ngày giao hoa: " . \HelpFunction::revertDate($exe->ngayGiaoHoa);
            $nhatKy->save();
            return response()->json([
                'code' => 200,
                'type' => 'success',
                'message' => 'Đã phê duyệt cấp hoa'
            ]);
        } else {
            return response()->json([
                'code' => 500,
                'type' => 'error',
                'message' => 'Không thể phê duyệt phiếu này'
            ]);
        }
    }
}
