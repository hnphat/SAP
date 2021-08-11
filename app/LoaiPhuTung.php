<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiPhuTung extends Model
{
    //
    protected $table = "loai_phu_tung";
    public function phuTung() {
        return $this->hasMany('App\PhuTung', 'id_loai_phu_tung', 'id');
    }
}
