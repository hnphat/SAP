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
                $obj->nguoinhap = User::where('id', $item->id_user)->value('name');
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
}
