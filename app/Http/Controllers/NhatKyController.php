<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NhatKyController extends Controller
{
    public function getList() {
        return view('nhatky.nhatky');
    }
}
