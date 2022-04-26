<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietBHPK extends Model
{
    //
    protected $table = "chitiet_bhpk";
    public function userWork() {
        return $this->belongsTo('App\User', 'id_user_work', 'id');
    }
}
