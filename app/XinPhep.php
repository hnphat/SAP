<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XinPhep extends Model
{
    //
    protected $table = "xin_phep";

    public function loaiPhep() {
        return $this->belongsTo('App\LoaiPhep','id_phep', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }

    public function userDuyet() {
        return $this->belongsTo('App\User','user_duyet', 'id');
    }
}
