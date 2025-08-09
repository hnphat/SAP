<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\XeCuuHo;
use App\NhatKy;
use App\User;

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
        if ($data) 
            return response()->json([
                'message' => 'Đã tải dữ liệu!',
                'code' => 200,
                'data' => $data
            ]);
        else
            return response()->json([
                'message' => 'Lỗi tải dữ liệu!',
                'code' => 500
            ]);
    }

}
