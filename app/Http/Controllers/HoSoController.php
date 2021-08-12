<?php

namespace App\Http\Controllers;

use App\User;
use App\UsersDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
        $hoso = User::select('ud.id','users.name','ud.surname','ud.phone','ud.birthday','ud.address')->join('users_detail as ud','ud.id_user','=','users.id')->get();
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
        $hoso = UsersDetail::where('id', $request->edit_id)->update([
            'surname' => $request->_hoTen,
            'birthday' => $request->_ngaySinh,
            'phone' => $request->_phone,
            'address' => $request->_diaChi
        ]);

        if($hoso) {
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
        $hoso = UsersDetail::where('id', $request->id)->delete();
        if($hoso) {
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
}
