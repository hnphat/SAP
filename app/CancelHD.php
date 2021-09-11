<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelHD extends Model
{
    //
    protected $table = "cancel_hd";

    public function sale() {
        return $this->belongsTo('App\Sale','sale_id','id');
    }

    public function user() {
        return $this->belongsTo('App\User','user_id', 'id');
    }
}
