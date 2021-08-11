<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    //
    protected $table = "guest";
    public function sale() {
        return $this->hasMany('App\Sale', 'id_guest', 'id');
    }

    public function typeGuest() {
        return $this->belongsTo('App\TypeGuest', 'id_type_guest', 'id');
    }
}
