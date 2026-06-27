<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhoanVay extends Model
{
    //
    protected $table = "khoan_vay";
    
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }

    public function xeNhanNo() {
        return $this->hasMany('App\XeNhanNo','id_khoanvay','id');
    }
}
