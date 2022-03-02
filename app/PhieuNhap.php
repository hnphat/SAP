<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    //
    protected $table = "phieu_nhap";
    public function danhMuc() {
        return $this->belongsToMany('App\DanhMucSP','nhap_sp', 'id_nhap', 'id_danhmuc');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
