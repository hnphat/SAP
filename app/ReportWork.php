<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportWork extends Model
{
    //
    protected $table = "report_work";
    public function report() {
        return $this->belongsTo('App\Report', 'id_report', 'id');
    }
}
