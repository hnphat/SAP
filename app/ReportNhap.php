<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportNhap extends Model
{
    //
    protected $table = 'report_nhap';
    public function report() {
        return $this->belongsTo('App\Report', 'id_report', 'id');
    }
}
