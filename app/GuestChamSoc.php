<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestChamSoc extends Model
{
    //
    protected $table = "guest_cham_soc";

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }

}
