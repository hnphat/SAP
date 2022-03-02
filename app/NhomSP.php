<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhomSP extends Model
{
    //
    protected $table = "nhom_sp";

    public function danhMuc() {
        return $this->hasMany('App\DanhMucSP','id_nhom','id');
    }
}
