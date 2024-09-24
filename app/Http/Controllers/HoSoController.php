<?php

namespace App\Http\Controllers;

use App\User;
use App\NhatKy;
use App\UsersDetail;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HoSoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $user_empty = User::select('*')->whereNotIn('id', DB::table('users_detail')->select('id_user')->join('users','users_detail.id_user','=','users.id'))->get();
        $user = User::all();
        //return view('user.hoso',['user' => $user, 'user_empty' => $user_empty]);
        return view('user.hoso',['user' => $user]);
    }

    public function getUser() {
        $user_empty = User::select('*')->whereNotIn('id', DB::table('users_detail')->select('id_user')->join('users','users_detail.id_user','=','users.id'))->get();
        foreach($user_empty as $row) {
            echo "<option value='".$row->id."'>".$row->name."</option>";
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $hoso = new UsersDetail;

        $hoso->id_user = $request->user_id;
        $hoso->surname = $request->hoTen;
        $hoso->phone = $request->phone;
        $hoso->birthday = $request->ngaySinh;
        $hoso->address = $request->diaChi;

        $result = $hoso->save();

        if($result) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Quản trị - Hồ sơ";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Thêm thông tin nhân viên<br/> Họ tên: "
            .$request->hoTen." <br/>Điện thoại: ".$request->phone." <br/>Ngày sinh: "
            .$request->ngaySinh." <br/>Địa chỉ:" .$request->diaChi;
            $nhatKy->save();
            return response()->json([
               'message' => 'Data Inserted',
               'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getHoSo() {
        $hoso = User::select('ud.id','ud.hoSo','ud.anh','users.name','ud.surname','ud.phone','ud.birthday','ud.address')
        ->join('users_detail as ud','ud.id_user','=','users.id')
        ->get();
        if($hoso) {
            return response()->json([
                'message' => 'Data found',
                'code' => 200,
                'data' => $hoso
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }

    public function editHoSo(Request $request) {
        $hoso = UsersDetail::where('id',$request->id)->first();

        if($hoso) {
            return response()->json([
                'message' => 'Data found',
                'code' => 200,
                'data' => $hoso
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }

    public function updateHoSo(Request $request)
    {
        //
        $temp = UsersDetail::where('id', $request->edit_id)->first();
        $hoso = UsersDetail::where('id', $request->edit_id)->update([
            'surname' => $request->_hoTen,
            'birthday' => $request->_ngaySinh,
            'phone' => $request->_phone,
            'address' => $request->_diaChi
        ]);

        if($hoso) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Quản trị - Hồ sơ";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->ghiChu = Carbon::now();
            $nhatKy->noiDung = "Cập nhật thông tin nhân viên<br/>THÔNG TIN CŨ<br/>Họ tên: "
            .$temp->surname." <br/>Điện thoại: ".$temp->phone." <br/>Ngày sinh: "
            .$temp->birthday." <br/>Địa chỉ:" .$temp->address."<br/>THÔNG TIN MỚI<br/>Họ tên: "
            .$request->_hoTen." <br/>Điện thoại: ".$request->_phone." <br/>Ngày sinh: "
            .$request->_ngaySinh." <br/>Địa chỉ:" .$request->_diaChi;
            $nhatKy->save();
            return response()->json([
                'message' => 'Data updated',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }

    public function deleteHoSo(Request $request)
    {
        $temp = UsersDetail::where('id', $request->id)->first();
        $hoSo = UsersDetail::find($request->id);
        $temp_anh = $hoSo->anh;        
        $temp_hoSo = $hoSo->hoSo;        
        $hoso = UsersDetail::where('id', $request->id)->delete();
        if($hoso) {
            if ($temp_anh != null && file_exists('upload/hoso/' . $temp_anh))
                unlink('upload/hoso/'.$temp_anh); 
            if ($temp_hoSo != null && file_exists('upload/tephoso/' . $temp_hoSo))
                unlink('upload/tephoso/'.$temp_hoSo); 
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->chucNang = "Quản trị - Hồ sơ";
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->noiDung = "Xóa thông tin nhân viên<br/> Họ tên: "
            .$temp->surname." <br/>Điện thoại: ".$temp->phone." <br/>Ngày sinh: "
            .$temp->birthday." vĐịa chỉ:" .$temp->address;
            $nhatKy->save();
            return response()->json([
                'message' => 'Data deleted',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Server Error',
                'code' => 500
            ]);
        }
    }

    public function postTep(Request $request) {
        $hoSo = UsersDetail::find($request->up_id);
        $temp_anh = $hoSo->anh;
        $temp_hoSo = $hoSo->hoSo;
        $name_temp = $hoSo->surname;
        $txt = "";
        $checkAnh = false;
        $checkHoso = false;
        $this->validate($request,[
            // 'fileAnh'  => 'required|mimes:jpg,JPG,png,PNG|max:10480',
            // 'fileHoso'  => 'required|mimes:pdf,zip,rar,doc,docx,xls,xlsx,ppt,pptx|max:20480',
            'fileAnh'  => 'mimes:jpg,JPG,png,PNG|max:10480',
            'fileHoso'  => 'mimes:pdf,zip,rar,doc,docx,xls,xlsx,ppt,pptx|max:20480',
        ]);      
        
        if ($request->hasFile('fileAnh') && $files = $request->file('fileAnh')) {
            if ($temp_anh != null && file_exists('upload/hoso/' . $temp_anh))
                unlink('upload/hoso/'.$temp_anh); 
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/hoso/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $hoSo->anh = $name;
            $hoSo->save();
            $files->move('upload/hoso/', $name);
            
            if ($hoSo) {
                $txt .= "Bổ sung hình ảnh của " . $name_temp . "<br/>";
            }            
        }

        if ($request->hasFile('fileHoso') && $files = $request->file('fileHoso')) {
            if ($temp_hoSo != null && file_exists('upload/tephoso/' . $temp_hoSo))
                unlink('upload/tephoso/'.$temp_hoSo);     
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/tephoso/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $hoSo->hoSo = $name;
            $hoSo->save();
            $files->move('upload/tephoso/', $name);            
            if ($hoSo) {
                $txt .= "Bổ sung tệp hồ sơ của " . $name_temp . "<br/>";
            }           
        }

        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->ghiChu = Carbon::now();
        $nhatKy->chucNang = "Quản lý hồ sơ";
        $nhatKy->noiDung = ($txt == "" ? "Cập nhật nội dung file trống" : $txt);
        $nhatKy->save(); 
        return response()->json([
            "type" => 'info',
            "message" => 'File: Đã xử lý các file, vui lòng kiểm tra lại dữ liệu',
            "code" => 200
        ]);
    }
}
