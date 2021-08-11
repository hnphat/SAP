<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Quyen;

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
        $quyen = Quyen::all();
        return view('user.list', ['user' => $user, 'quyen' => $quyen]);
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
        $user->rule = $request->rule;
        $user->active = $request->active;
        $user->save();
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
        $user->rule = $request->rule;
        $user->save();
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
//        $user = User::find($id);
//        $user->delete();
        $user = User::where('id', $id)->delete();
        if ($user)
            return redirect()->route('user.list');
        return "Loi";
    }

    public function lock($id)
    {
        $user = User::find($id);
        $active = $user->active;
        $active = ($active == 1) ? 0 : 1;
        $user->active = $active;
        $user->save();
        return redirect()->route('user.list');
    }

    public function login(Request $request) {
        $data = ['name' => $request->account, 'password' => $request->password];
        if (Auth::attempt($data)) {
            return redirect()->route('trangchu');
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
                    <td>".$row->quyen->name."</td>
                    <td>Xoa</td>
              </tr>";
        }
    }
}
