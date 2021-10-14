<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportCar extends Model
{
    //
    protected $table = 'report_car';

    public function report() {
        return $this->belongsTo('App\Report', 'id_report', 'id');
    }
}
