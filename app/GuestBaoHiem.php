<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestBaoHiem extends Model
{
    //
    protected $table = "guest_baohiem";

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }

    public function contracts() {
        return $this->hasMany('App\BaoHiemHopDong', 'id_guest_baohiem', 'id');
    }
}
