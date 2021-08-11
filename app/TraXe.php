<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraXe extends Model
{
    //
    protected $table = "tra_xe";
    public function xeLaiThu() {
        return $this->belongsTo('App\XeLaiThu','id_xe_lai_thu','id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_pay', 'id');
    }
}
