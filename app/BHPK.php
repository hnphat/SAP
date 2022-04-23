<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BHPK extends Model
{
    //
    protected $table = "baohiem_phukien";
    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }
}
