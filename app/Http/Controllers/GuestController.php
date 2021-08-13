<?php

namespace App\Http\Controllers;

use App\Guest;
use App\TypeGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    //
    public function index() {
        $type_guest = TypeGuest::all();
        return view('page.khachhangkd', ['typeGuest' => $type_guest]);
    }

    public function getList() {
        $result = Guest::select('t.name as type','guest.name','guest.id as idmaster','guest.phone', 'guest.address')->join('type_guest as t','guest.id_type_guest','=','t.id')->get();
        if($result) {
            return response()->json([
                'message' => 'Get list successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function checkPhone($num) {
        $guest = Guest::where('phone',$num)->first();
        if ($guest)
            echo '{"phone": "'.$num.'", "check":"1", "user":"'.$guest->user->userDetail->surname.'"}';
        else
            echo '{"phone": "'.$num.'", "check":"0", "user":"'.$guest->user->userDetail->surname.'"}';
    }
    public function add(Request $request) {
        $guest = new Guest;

        $guest->id_type_guest = $request->loai;
        $guest->name = $request->ten;
        $guest->phone = $request->dienThoai;
        $guest->address = $request->diaChi;
        $guest->id_user_create = Auth::user()->id;
        $guest->save();

        if($guest) {
            return response()->json([
                'message' => 'Insert data successfully!',
                'code' => 200,
                'noidung' => $request->ten
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = Guest::where('id', $request->id)->delete();
        if($result) {
            return response()->json([
                'message' => 'Delete data successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function editShow(Request $request) {
        $result = Guest::where('id', $request->id)->first();
        if($result) {
            return response()->json([
                'message' => 'Show edit data successfully!',
                'code' => 200,
                'data' => $result
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }

    public function update(Request $request) {
        $result = Guest::where('id', $request->eid)->update([
            'id_type_guest' => $request->eloai,
            'name' => $request->eten,
            'phone' => $request->edienThoai,
            'address' => $request->ediaChi
        ]);
        if($result) {
            return response()->json([
                'message' => 'Updated successfully!',
                'code' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }
    }
}
