<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $table = "report";

    public function reportCar() {
        return $this->hasMany('App\ReportCar', 'id_report', 'id');
    }

    public function reportWork() {
        return $this->hasMany('App\ReportWork', 'id_report', 'id');
    }

    public function reportNhap() {
        return $this->hasMany('App\ReportNhap', 'id_report', 'id');
    }

    public function reportXuat() {
        return $this->hasMany('App\ReportXuat', 'id_report', 'id');
    }
}
