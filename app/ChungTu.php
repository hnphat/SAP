<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChungTu extends Model
{
    //
    protected $table = "chung_tu";
    public function userCreate() {
        return $this->belongsTo('App\User', 'user_create', 'id');
    }
}
