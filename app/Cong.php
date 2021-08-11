<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cong extends Model
{
    //
    protected $table = "cong";
    public function dv() {
        return $this->belongsTo('App\DV','id_dv', 'id');
    }

    public function loaiCong() {
        return $this->belongsTo('App\LoaiCong', 'id_loai_cong', 'id');
    }
}
