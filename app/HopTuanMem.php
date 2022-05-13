<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HopTuanMem extends Model
{
    //
    protected $table = "hop_tuan_mem";
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
