<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestHD extends Model
{
    //
    protected $table = "request_hd";

    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function carDetail() {
        return $this->belongsTo('App\TypeCarDetail','car_detail_id','id');
    }

    public function sale() {
        return $this->belongsTo('App\Sale','sale_id','id');
    }

    public function guest() {
        return $this->belongsTo('App\Guest','guest_id','id');
    }
}
