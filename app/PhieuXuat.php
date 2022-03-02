<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuXuat extends Model
{
    //
    protected $table = "phieu_xuat";
    public function danhMuc() {
        return $this->belongsToMany('App\DanhMucSP','xuat_sp', 'id_xuat', 'id_danhmuc');
    }

    public function userXuat() {
        return $this->belongsTo('App\User', 'id_user_xuat', 'id');
    }

    public function userDuyet() {
        return $this->belongsTo('App\User', 'id_user_duyet', 'id');
    }
}
