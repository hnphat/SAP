<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhuTung extends Model
{
    //
    protected $table = "phu_tung";
    public function dv() {
        return $this->belongsTo('App\DV', 'id_dv', 'id');
    }

    public function loaiPhuTung() {
        return $this->belongsTo('App\LoaiPhuTung', 'id_loai_phu_tung','id');
    }
}
