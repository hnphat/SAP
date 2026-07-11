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
}
