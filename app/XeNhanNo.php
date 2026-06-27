<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XeNhanNo extends Model
{
    //
    protected $table = "xe_nhan_no";
    
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }

    public function khoanVay() {
        return $this->belongsTo('App\KhoanVay','id_khoanvay', 'id');
    }
}
