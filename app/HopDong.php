<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HopDong extends Model
{
    //
    protected $table = "hop_dong";

    public function guest() {
        return $this->belongsTo('App\Guest','id_guest','id');
    }

    public function carSale() {
        return $this->belongsTo('App\KhoV2', 'id_car_sale','id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }

//    public function saleOff() {
//        return $this->hasMany('App\SaleOff', 'id_sale', 'id');
//    }

    public function package() {
        return $this->belongsToMany('App\PackageV2','saleoffv2','id_hd', 'id_bh_pk_package');
    }

    public function requestHd() {
        return $this->hasOne('App\RequestHD','sale_id','id');
    }

    public function cancelHd() {
        return $this->hasOne('App\CancelHD','sale_id', 'id');
    }
}
