<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BienBanKhenThuong extends Model
{
    //
    protected $table = "bienban_khenthuong";
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
