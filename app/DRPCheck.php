<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DRPCheck extends Model
{
    //
    protected $table = "drp_check";
    public function user() {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
