<?php

namespace App\Http\Controllers;

use App\LoaiCong;
use Illuminate\Http\Request;

class CongController extends Controller
{
    //
    public function index() {
        return view('page.congphutung');
    }

    public function getList() {
        $result = LoaiCong::all();
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

    public function add(Request $request) {
        $package = new LoaiCong();

        $package->name = $request->loaiCong;
        $package->save();

        if($package) {
            return response()->json([
                'message' => 'Insert data successfully!',
                'code' => 200,
                'noidung' => $request->loaiCong
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = LoaiCong::where('id', $request->id)->delete();
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
        $result = LoaiCong::where('id', $request->id)->first();
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
        $result = LoaiCong::where('id', $request->idObject)->update([
            'name' => $request->loaiCong
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
