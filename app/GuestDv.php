<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestDv extends Model
{
    //
    protected $table = "guest_dv";

    public function dv() {
        return $this->hasMany('App\DV', 'id_guest_dv', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }
}
