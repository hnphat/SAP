<?php

namespace App\Http\Controllers;

use App\DangKySuDung;
use App\DeNghiCapXang;
use App\ReportWork;
use App\EventReal;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventRealController extends Controller
{

    public function realTime() {
        $s_work = 0;
        $s_xedemo = 0;
        $s_xang = 0;
        $s_xang_lead = 0;
        $s_duyet_tbp = 0;
        $total_full = 0;

        $duyettbp = DangKySuDung::where([
            ['id_lead_check','=', Auth::user()->id],
            ['id_lead_check_status','=', false]
        ])->orderBy('id', 'DESC')->get()->count();

        $duyetXang = DeNghiCapXang::where([
            ['fuel_allow','=', false]
        ])->orderBy('id', 'DESC')->get()->count();

        $duyetXangLead = DeNghiCapXang::where([
            ['lead_id','!=', null],
            ['lead_check','=',   false]
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

        if (Auth::user()->hasRole('car'))
            $total_full += $reg + $traXe;

        if (Auth::user()->hasRole('hcns'))
            $total_full += $duyetXang;

        if (Auth::user()->hasRole('lead')) {
            $total_full += $duyettbp;
            $total_full += $duyetXangLead;
        }

        if (Auth::user()->hasRole('system')) {
            $s_work = 1;
            $s_xedemo = 1;
            $s_xang = 1;
            $s_xang_lead = 1;
            $s_duyet_tbp = 1;
        }

        if (Auth::user()->hasRole('work'))
            $s_work = 1;
        if (Auth::user()->hasRole('car'))
            $s_xedemo = 1;
        if (Auth::user()->hasRole('hcns'))
            $s_xang = 1;
        if (Auth::user()->hasRole('lead')) {
            $s_duyet_tbp = 1;
            $s_xang_lead = 1;
        }

        $data = [
            's_work' => $s_work,
            's_xedemo' => $s_xedemo,
            's_xang' => $s_xang,
            's_xang_lead' => $s_xang_lead,
            's_duyet_tbp' => $s_duyet_tbp,
            'newWork' => $getWork,
            'working' => $working,
            'checkWork' => $checkWork,
            'duyet' => $reg,
            'duyettbp' => $duyettbp,
            'tra' => $traXe,
            'duyetXang' => $duyetXang,
            'duyetXangLead' => $duyetXangLead,
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


    public function realTimeReg() {

        $_eve = EventReal::select('*')->get()->count();

        $data = [];

        if (!Session::has('eventreal')) {
            session(['eventreal' => $_eve]);
        }
        
        if ((int)$_eve != (int)session('eventreal')) {
            session(['eventreal' => $_eve]);
            $data = [
                'flag' => true
            ];
        } else {
            $data = [
                'flag' => false
            ];
        }

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
