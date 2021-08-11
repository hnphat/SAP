<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaiLieu extends Model
{
    //
    protected $table = "tai_lieu";
    public function user() {
        return $this->belongsTo('App\User','id_user','id');
    }

//    public function quyenXem() {
//        return $this->hasMany('App\QuyenXem','id_tai_lieu', 'id');
//    }
    public function nhom(){
        return $this->belongsToMany('App\Nhom','quyen_xem', 'id_tai_lieu', 'id_nhom');
    }
}
