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
        $user->save();
        $role = Roles::where('name','normal')->firstOrFail();
        $userAddRole = User::where('id',$user->id)->firstOrFail();
        $userAddRole->roles()->attach($role->id);
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Thêm người dùng";
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
        $user->password = bcrypt($request->password);
        $user->save();
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Cập nhật người dùng";
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
        $nhatKy->chucNang = "Quản trị - người dùng";
        $nhatKy->noiDung = "Thay đổi trạng thái khóa người dùng";
        $nhatKy->save();
        return redirect()->route('user.list');
    }

    public function login(Request $request) {
        $data = ['name' => $request->account, 'password' => $request->password];
        if (Auth::attempt($data)) {
            if (Auth::user()->active == 1)
                return redirect()->route('trangchu');
            else
                {
                    Auth::logout();
                    abort(403);
                }
        }
        $nhatKy = new NhatKy();
        $nhatKy->id_user = Auth::user()->id;
        $nhatKy->chucNang = "Đăng nhập";
        $nhatKy->noiDung = "Thực hiện đăng nhập vào hệ thống";
        $nhatKy->save();
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
        $user = User::find(Auth::user()->id);
        return view('user.pass', ['user' => $user]);
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
}
