<?php

namespace App\Http\Controllers;

use App\Roles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\NhatKy;
use App\UsersDetail;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all()->sortByDesc('id');
        return view('user.list', ['user' => $user]);
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
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->active = $request->active;
        $user->allowPhepNam = $request->allow;
        if ($request->allow == 1) {
            $user->ngay = $request->ngay;
            $user->thang = $request->thang;
            $user->nam = $request->nam;
        }
        $user->save();
        $role = Roles::where('name','normal')->firstOrFail();
        $userAddRole = User::where('id',$user->id)->firstOrFail();
        $userAddRole->roles()->attach($role->id);
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Thêm người dùng tài khoản ".$request->name."<br/>Trạng thái phép năm (1: Kích hoạt; 0: Khóa): " . $request->allow . " Ngày ".$request->ngay." tháng ".$request->thang." năm ".$request->nam;
        $nhatKy->save();
        return response()->json(['success'=>'Đã thêm người dùng']);
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
    public function update(Request $request)
    {
        //
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->changepass == 1) 
            $user->password = bcrypt($request->password);
        $user->allowPhepNam = $request->allow;
        if ($request->allow == 1) {
            $user->ngay = $request->ngay;
            $user->thang = $request->thang;
            $user->nam = $request->nam;
        } else {
            $user->ngay = null;
            $user->thang = null;
            $user->nam = null;
        }
        $user->save();
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Cập nhật người dùng tài khoản ".$request->name."<br/>Trạng thái phép năm (1: Kích hoạt; 0: Khóa): " . $request->allow . " Ngày ".$request->ngay." tháng ".$request->thang." năm ".$request->nam;
        $nhatKy->save();
        return response()->json(['success'=>'Đã cập nhật người dùng']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = User::find($id);
        if ($check->hasRole('system'))
            return redirect()->route('user.list')->with('loi','Không thể xóa tài khoản hệ thống');
        try {
            $user = User::where('id', $id)->delete();
            if ($user) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->chucNang = "Quản trị - người dùng";
                $nhatKy->noiDung = "Xóa người dùng";
                $nhatKy->save();
                return redirect()->route('user.list');
            }
        } catch (QueryException $ex) {
            return redirect()->route('user.list')->with('loi','Không thể xóa vì người dùng đang còn sử dụng ở những nơi khác NOTE: ' .$ex->getMessage());
        }
    }

    public function lock($id)
    {
        $user = User::find($id);
        if ($user->hasRole('system'))
            return redirect()->route('user.list')->with('loi','Không thể khóa tài khoản hệ thống');
        $active = $user->active;
        $active = ($active == 1) ? 0 : 1;
        $user->active = $active;
        $user->save();
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->thoiGian = Date("H:m:s");
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Thay đổi trạng thái khóa người dùng";
        $nhatKy->save();
        return redirect()->route('user.list');
    }

    public function login(Request $request) {
        $data = ['name' => $request->account, 'password' => $request->password];
        if (Auth::attempt($data)) {
            if (Auth::user()->active == 1) {
                $nhatKy = new NhatKy();
                $nhatKy->id_user = Auth::user()->id;
                $nhatKy->chucNang = "Đăng nhập";
                $nhatKy->thoiGian = Date("H:m:s");
                $nhatKy->noiDung = "Đăng nhập thành công vào vào hệ thống";
                $nhatKy->save();
                return redirect()->route('trangchu');
            }
            else
                {
                    Auth::logout();
                    abort(403);
                }
        }
        return view('login', ['error' => 'Sai tài khoản hoặc mật khẩu']);
    }

    public function getUser()
    {
        $user = User::all();
        foreach($user as $row) {
            echo "<tr>
                    <td>".$row->id."</td>
                    <td>".$row->name."</td>
                    <td>".$row->email."</td>
                    <td>Xoa</td>
              </tr>";
        }
    }

    public function changePass() {
        $jsonString = file_get_contents('upload/cauhinh/app.json');
        $data = json_decode($jsonString, true); 
        $user = User::find(Auth::user()->id);
        return view('user.pass', ['user' => $user, 'data' => $data]);
    }

    public function change(Request $request) {        
        $user = User::find(Auth::user()->id);
        if ($request->passRequest == 'on') {
            $data = ['name' => Auth::user()->name, 'password' => $request->oldPass];
            if (Auth::attempt($data)) {
                if ($request->newPass == $request->newPassAgain) {
                    if(strlen($request->newPassAgain) < 6) {
                        return response()->json([
                            'type' => 'warning',
                            'message' => 'Mật khẩu mới tối thiểu 06 ký tự!',
                            'code' => 100
                        ]);
                    } else {
                        $user->password = bcrypt($request->newPassAgain);
                    }
                } else {
                    return response()->json([
                            'type' => 'warning',
                            'message' => 'Mật khẩu mới không trùng khớp!',
                            'code' => 100
                    ]);
                }
            } else {
                return response()->json([
                            'type' => 'error',
                            'message' => 'Mật khẩu cũ không đúng!',
                            'code' => 100
                    ]);
            }
        } 

        $user->email = $request->email;
        $user->save();
        $userDetail = UsersDetail::where('id_user', Auth::user()->id)->first();  
        $userDetail->phone = $request->phone;
        $userDetail->address = $request->address;
        $userDetail->birthday = $request->birthday;       

        $userDetail->save();
        if($user) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - người dùng";
            $nhatKy->noiDung = "Cập nhật thông tin người dùng";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật thông tin',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi: Không thể cập nhật!',
                'code' => 500
            ]);
        }      
        
    }

    public function upPic(Request $request) {
        $hoSo = UsersDetail::find($request->up_id);
        $temp_anh = $hoSo->anh;
        $name = $temp_anh;
        if ($request->hasFile('fileAnh') && $files = $request->file('fileAnh')) {
            if ($temp_anh != null && file_exists('upload/hoso/' . $temp_anh))
                unlink('upload/hoso/'.$temp_anh); 
            $etc = strtolower($files->getClientOriginalExtension());
            $name = \HelpFunction::changeTitle($files->getClientOriginalName()) . "." . $etc;
            while(file_exists("upload/hoso/" . $name)) {
                $name = rand() . "-" . $name . "." . $etc;
            }
            $hoSo->anh = $name;
            $files->move('upload/hoso/', $name);                
        }
        $hoSo->save();
        if($hoSo) {
            $nhatKy = new NhatKy();
            $nhatKy->id_user = Auth::user()->id;
            $nhatKy->thoiGian = Date("H:m:s");
            $nhatKy->chucNang = "Quản trị - người dùng";
            $nhatKy->noiDung = "Cập nhật hình ảnh đại diện";
            $nhatKy->save();
            return response()->json([
                'type' => 'success',
                'message' => 'Đã cập nhật hình ảnh. Đang tải ảnh mới....',
                'code' => 200,
                'newimage' => $name 
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Lỗi: Không thể cập nhật!',
                'code' => 500
            ]);
        }      
    } 
}
