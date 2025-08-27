<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\XeCuuHo;
use App\NhatKy;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class XeCuuHoController extends Controller
{
    //
    public function index()
    {
        // Logic to display the list of xe cứu hộ
        return view('xecuuho.xecuuho');
    }

    public function danhsach()
    {
        $data = XeCuuHo::all();
        $arr_temp = [];
        if ($data) {
            foreach($data as $item) {
                $obj = $item;
                $obj->newday = Carbon::parse($item->created_at)->format('d/m/Y');
                $obj->newtimedi = Carbon::parse($item->thoiGianDi)->format('H:m d/m/Y');
                $obj->newtimeve = Carbon::parse($item->thoiGianVe)->format('H:m d/m/Y');
                $obj->nguoinhap = User::find($item->id_user)->userDetail->surname;
                array_push($arr_temp, $obj);
            }
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $arr_temp
            ]);
        } else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }

    public function post(Request $request) {
        $obj = new XeCuuHo();
        $obj->id_user = Auth::user()->id;
        $obj->khachHang = $request->khachHang;
        // $obj->sdt = $request->sdt;
        $obj->yeuCau = $request->yeuCau;
        $obj->hinhThuc = $request->hinhThuc;
        $obj->diaDiemDi = $request->diaDiemDi;
        $obj->thoiGianDi = $request->thoiGianDi;
        $obj->thoiGianVe = $request->thoiGianVe;
        $obj->ghiChu = $request->ghiChu;
        $obj->save();
        if ($obj) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->noiDung = "Thêm mới: ";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Thêm mới đề nghị cứu hộ thành công!',
                "code" => 200
            ]);
        }                   
        else
            return response()->json([
                "type" => 'error',
                'message' => 'Lỗi!',
                'code' => 500
            ]);
    }

    public function upFile(Request $request) {
        $obj = XeCuuHo::find($request->eidUp);
        if ($obj->allow) {
            return response()->json([
                'type' => 'error',
                'message' => 'Lệnh này đã khóa không thể cập nhật tập tin đính kèm!',
                'code' => 500
            ]);
        }
        $this->validate($request,[
            'edinhKem'  => 'required|mimes:png,jpg,PNG,JPG,doc,docx,pdf|max:20480',
        ]);      

        if ($files = $request->file('edinhKem')) {
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName());
            if ($name !== null && file_exists('upload/xecuuho/' . $name . "." . $etc))
                unlink('upload/xecuuho/'.$name);

            $name = rand() . "-" . $name . "." . $etc;    

            $obj->baoGia = $name;
            $obj->save();                                     
            if ($obj) {
                $files->move('upload/xecuuho/', $name);    
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
                $nhatKy->noiDung = "Bổ sung file scan báo giá: " . url("upload/xecuuho/" . $name);
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();
                return response()->json([
                    "type" => 'success',
                    "message" => 'Đã upload file thành công',
                    "code" => 200
                ]);
            }                       
            else
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi upload!',
                    'code' => 500
                ]);      
            }
    }

    public function deleteFileScan(Request $request) {
        $obj = XeCuuHo::find($request->id);
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
            $temp = $obj->khachHang;
            $name = $obj->baoGia;
            // if ($name !== null && file_exists('upload/chungtu/' . $name))
            //     unlink('upload/chungtu/'.$name);
            $obj->baoGia = null;
            $obj->save();        
            if ($obj) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
                $nhatKy->noiDung = "Xóa file scan đính kèm trên client. Khách hàng ".$temp."; File cũ tạm ẩn " . url('upload/xecuuho/' . $name);
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã cập nhật!',
                    'code' => 200,
                    'data' => $obj
                ]);    
            }           
            else
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi không thể cập nhật từ máy chủ!',
                    'code' => 500
                ]);
        } else
            return response()->json([
                'type' => 'error',
                'message' => 'File đính kèm đã được tải lên hệ thống không thể xóa!',
                'code' => 500
            ]);       
    }

    public function getEdit($id) {
        $result = XeCuuHo::find($id);
        if ($result) 
            return response()->json([
                'type' => 'success',
                'message' => 'Đã load!',
                'code' => 200,
                'data' => $result
            ]);
        else
            return response()->json([
                'type' => 'error',
                'message' => 'Không thể load dữ liệu!',
                'code' => 500
            ]);
    }

    public function postUpdate(Request $request) {
        $temp = XeCuuHo::find($request->eid);
        $obj = XeCuuHo::find($request->eid);
        if ($obj->allow && (!(Auth::user()->hasRole("system") || !Auth::user()->hasRole("hcns")))) {
            return response()->json([
                'type' => 'error',
                'message' => 'Đã ghi sổ không thể cập nhật!',
                'code' => 500
            ]);
        }
        $obj->khachHang = $request->ekhachHang;
        $obj->yeuCau = $request->eyeuCau;
        $obj->hinhThuc = $request->ehinhThuc;
        $obj->diaDiemDi = $request->ediaDiemDi;
        $obj->thoiGianDi = $request->ethoiGianDi;
        $obj->thoiGianVe = $request->ethoiGianVe;
        $obj->doanhThu = $request->edoanhThu;
        $obj->ghiChu = $request->eghiChu;
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
            $obj->allow = $request->eallow;
        }
        $obj->save();                                     
        if ($obj) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
            $nhatKy->noiDung = "Cập nhật nội dung: " . $temp->khachHang . " thành " . $request->ekhachHang ."; Số lượng: " 
            . $temp->yeuCau . " thành " . $request->eyeuCau . "; Doanh thu: " 
            . $temp->doanhThu . " thành " . $request->edoanhThu . "; Chuyển trạng thái: " 
            . ($temp->allow ? "Đã ghi sổ" : "Chưa ghi sổ") . " thành "
            . ($request->eallow ? "Đã ghi sổ" : "Chưa ghi sổ") . ";";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã cập nhật',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi!',
                "code" => 500
            ]);
        }
    }

     public function ghiSo(Request $request) {
        $temp = XeCuuHo::find($request->id);
        $obj = XeCuuHo::find($request->id);
        $obj->allow = true;
        if (Auth::user()->hasRole("system") || Auth::user()->hasRole("hcns")) {
           $obj->save();      
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Bạn không có quyền thực hiện chức năng này!',
                "code" => 500
            ]);
        }                                   
        if ($obj) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
            $nhatKy->noiDung = "Thực hiện ghi sổ";
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->save();
            return response()->json([
                "type" => 'success',
                "message" => 'Đã ghi sổ',
                "code" => 200
            ]);
        } else {
            return response()->json([
                "type" => 'error',
                "message" => 'Lỗi!',
                "code" => 500
            ]);
        }
    }

    public function delete(Request $request) {
        $obj = XeCuuHo::find($request->id);
        if ($obj->allow != true) {
            $temp = $obj->khachHang;
            $name = $obj->baoGia;
            if ($name !== null && file_exists('upload/xecuuho/' . $name))
                unlink('upload/xecuuho/'.$name);
            $obj->delete();        
            if ($obj) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản lý xe - Quản lý xe cứu hộ";
                $nhatKy->noiDung = "Xóa lệnh cứu hộ của khách hàng " . $temp;
                $nhatKy->ghiChu = Carbon::now();
                $nhatKy->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Đã xóa!',
                    'code' => 200,
                    'data' => $obj
                ]);    
            }           
            else
                return response()->json([
                    'type' => 'error',
                    'message' => 'Lỗi không thể xoá từ máy chủ!',
                    'code' => 500
                ]);
        } else
            return response()->json([
                'type' => 'error',
                'message' => 'Lệnh cứu hỗ đã ghi sổ không thể xoá!',
                'code' => 500
            ]);       
    }
}
