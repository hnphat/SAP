<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportWork extends Model
{
    //
    protected $table = "report_work";

    public function userTao() {
    	return $this->belongsTo('App\User','user_tao','id');
    }

    public function userNhan() {
    	return $this->belongsTo('App\User','user_nhan','id');
    }

}
