<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nhom extends Model
{
    //
    protected $table = "nhom";
//    public function nhomUser() {
//        return $this->hasMany('App\NhomUser','id_nhom', 'id');
//    }
    public function user() {
        return $this->belongsToMany('App\User','nhom_user', 'id_nhom', 'id_user');
    }

//    public function quyenXem() {
//        return $this->hasMany('App\QuyenXem','id_nhom', 'id');
//    }
    public function tailieu() {
        return $this->belongsToMany('App\TaiLieu','quyen_xem', 'id_nhom','id_tai_lieu');
    }
}
