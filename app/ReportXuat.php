<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportXuat extends Model
{
    //
    protected $table = "report_xuat";
    public function report() {
        return $this->belongsTo('App\Report', 'id_report', 'id');
    }
}
