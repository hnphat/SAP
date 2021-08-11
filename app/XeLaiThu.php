<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XeLaiThu extends Model
{
    //
    protected $table = "xe_lai_thu";
    public function user() {
        return $this->belongsTo('App\User', 'id_user_use', 'id');
    }

    public function dangKySuDung() {
        return $this->hasMany('App\DangKySuDung', 'id_xe_lai_thu', 'id');
    }

    public function traXe() {
        return $this->hasMany('App\TraXe', 'id_xe_lai_thu', 'id');
    }

}
