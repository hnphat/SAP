<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiPhep extends Model
{
    //
    protected $table = "loai_phep";

    public function xinPhep() {
        return $this->hasMany('App\XinPhep','id_phep','id');
    }
}
