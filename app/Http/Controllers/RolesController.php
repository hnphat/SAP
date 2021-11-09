<?php

namespace App\Http\Controllers;

use App\Roles;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        $roles = Roles::all();
        return view('role.list', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $user = User::where('id', $request->chonTaiKhoan)->firstOrFail();
        $role = Roles::where('id', $request->chonQuyen)->firstOrFail();
        if (!$user->hasRole($role->name))
            $user->roles()->attach($role->id);
        return redirect()->route('roles.list');
    }

    public function rm($role_id,$user_id)
    {
        $user = User::where('id', $user_id)->firstOrFail();
        $role = Roles::where('id', $role_id)->firstOrFail();
        $user->roles()->detach($role->id);
        return redirect()->route('roles.list');
    }

    public function showDetail($id) {
        $role = Roles::find($id);
        echo $role->description;
    }
}
