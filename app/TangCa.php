<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TangCa extends Model
{
    //
    protected $table = "tang_ca";
    
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }

    public function userDuyet() {
        return $this->belongsTo('App\User','id_user_duyet', 'id');
    }
}
