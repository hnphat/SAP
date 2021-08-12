<?php

namespace App\Http\Controllers;

use App\BhPkPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    //
    public function index() {
        return view('page.package');
    }

    public function getList() {
        $result = BhPkPackage::all();
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
        $package = new BhPkPackage;

        $package->name = $request->noiDung;
        $package->cost = $request->gia;
        $package->profit = $request->hoaHong;
        $package->save();

        if($package) {
            return response()->json([
                'message' => 'Insert data successfully!',
                'code' => 200,
                'noidung' => $request->noiDung
            ]);
        } else {
            return response()->json([
                'message' => 'Internal server fail!',
                'code' => 500
            ]);
        }

    }

    public function delete(Request $request) {
        $result = BhPkPackage::where('id', $request->id)->delete();
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
        $result = BhPkPackage::where('id', $request->id)->first();
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
        $result = BhPkPackage::where('id', $request->idObject)->update([
            'name' => $request->noiDung,
            'cost' => $request->gia,
            'profit' => $request->hoaHong
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
