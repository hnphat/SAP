<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DV extends Model
{
    //
    protected $table = "dv";
    public function guestDv() {
        return $this->belongsTo('App\GuestDv', 'id_guest_dv', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }

    public function cong() {
        return $this->hasMany('App\Cong', 'id_dv', 'id');
    }

    public function phuTung() {
        return $this->hasMany('App\PhuTung','id_dv', 'id');
    }
}
