<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestDv extends Model
{
    //
    protected $table = "guest_dichvu";

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }
}
