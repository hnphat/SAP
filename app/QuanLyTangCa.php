<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuanLyTangCa extends Model
{
    //
    protected $table = "quan_ly_tang_ca";
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
