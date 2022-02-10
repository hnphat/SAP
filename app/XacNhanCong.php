<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XacNhanCong extends Model
{
    //
    protected $table = "xac_nhan_cong";
    
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
