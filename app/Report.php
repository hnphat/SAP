<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $table = "report";

    public function user() {
        return $this->belongsTo('App\User','user_report', 'id');
    }

    public function reportCar() {
        return $this->hasMany('App\ReportCar', 'id_report', 'id');
    }
}
