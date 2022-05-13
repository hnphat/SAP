<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HopTuan extends Model
{
    //
    protected $table = "hop_tuan";
    public function member() {
        return $this->hasMany('App\HopTuanMem', 'id_hop', 'id');
    }
}
