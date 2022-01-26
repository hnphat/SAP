<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChamCongChiTiet extends Model
{
    //
    protected $table = "cham_cong_chi_tiet";

    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }   
}
