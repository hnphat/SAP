<?php

namespace App\Http\Controllers;

use App\DangKySuDung;
use App\ReportWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventRealController extends Controller
{

    public function realTime() {
        $s_work = 0;
        $s_xedemo = 0;
        $s_xang = 0;
        $total_full = 0;

        $duyetXang = DangKySuDung::where([
            ['fuel_request','=', true],
            ['fuel_allow','=', false]
        ])->orderBy('id', 'DESC')->get()->count();

        $duyetXangLead = DangKySuDung::where([
            ['fuel_request','=', true],
            ['lead_check','=', false]
        ])->orderBy('id', 'DESC')->get()->count();

        $reg = DangKySuDung::where([
            ['allow','=',false]
        ])->orderBy('id', 'DESC')->get()->count();

        $traXe = DangKySuDung::where([
            ['request_tra','=',true],
            ['tra_allow','=',false]
        ])->orderBy('id', 'DESC')->get()->count();

        $working = ReportWork::where([
                ['user_tao','=',Auth::user()->id],
                ['tienDo','<', 100],
                ['isPersonal','=', true]
            ])->orWhere([
                ['user_nhan','=',Auth::user()->id],
                ['tienDo','<', 100],
                ['isPersonal','=', false],
                ['apply','=', true]
            ])->orderBy('id', 'desc')->get()->count();

        $getWork = ReportWork::where([
                ['user_nhan','=',Auth::user()->id],
                ['isPersonal','=', false],
                ['apply','=', null]
            ])->orderBy('id', 'desc')->get()->count();

        $checkWork = ReportWork::where([
                ['user_tao','=',Auth::user()->id],
                ['tienDo','=', 100],
                ['isPersonal','=', false],
                ['acceptApply','=', false]
            ])->orderBy('id', 'desc')->get()->count();

        $total_full = $getWork + $checkWork;

        if (Auth::user()->hasRole('mkt'))
            $total_full += $reg + $traXe;

        if (Auth::user()->hasRole('hcns'))
            $total_full += $duyetXang;

        if (Auth::user()->hasRole('lead'))
            $total_full += $duyetXangLead;

        if (Auth::user()->hasRole('system')) {
            $s_work = 1;
            $s_xedemo = 1;
            $s_xang = 1;
        }
        if (Auth::user()->hasRole('work'))
            $s_work = 1;
        if (Auth::user()->hasRole('mkt'))
            $s_xedemo = 1;
        if (Auth::user()->hasRole('hcns') || Auth::user()->hasRole('lead'))
            $s_xang = 1;
        $data = [
            's_work' => $s_work,
            's_xedemo' => $s_xedemo,
            's_xang' => $s_xang,
            'newWork' => $getWork,
            'working' => $working,
            'checkWork' => $checkWork,
            'duyet' => $reg,
            'tra' => $traXe,
            'duyetXang' => $duyetXang,
            'total_full' => $total_full
        ];
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->setCallback(
            function() use ($data) {
                    echo "data: ".json_encode($data)."\n\n";
                    flush();
            });
        $response->send();
    }
}
