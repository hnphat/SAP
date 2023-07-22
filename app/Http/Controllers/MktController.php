<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MktController extends Controller
{
    //
    public function index() {
        return view("marketing.mkt");
    }
}
