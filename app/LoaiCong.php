<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiCong extends Model
{
    //
    protected $table = "loai_cong";

    public function cong() {
        return $this->hasMany('App\Cong', 'id_loai_cong', 'id');
    }
}
