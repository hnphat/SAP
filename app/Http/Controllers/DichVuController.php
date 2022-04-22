<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DichVuController extends Controller
{
    //
    public function phuKienPanel() {
        return view('dichvu.quanlyphukien');
    }
}
