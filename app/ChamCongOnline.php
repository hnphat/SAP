<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChamCongOnline extends Model
{
    //
    protected $table = "cham_cong_online";

    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }   
}
