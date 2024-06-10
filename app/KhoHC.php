<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhoHC extends Model
{
    //
    protected $table = "kho_hc";
    public function user() {
        return $this->belongsTo('App\User','id_user', 'id');
    }
}
