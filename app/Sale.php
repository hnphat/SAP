<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $table = "sale";
    public function guest() {
        return $this->belongsTo('App\Guest','id_guest','id');
    }

    public function carSale() {
        return $this->belongsTo('App\CarSale', 'id_car_sale','id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user_create', 'id');
    }

//    public function saleOff() {
//        return $this->hasMany('App\SaleOff', 'id_sale', 'id');
//    }

    public function package() {
        return $this->belongsToMany('App\BhPkPackage','sale_off','id_sale', 'id_bh_pk_package');
    }

    public function requestHd() {
        return $this->hasOne('App\RequestHD','sale_id','id');
    }

    public function cancelHd() {
        return $this->hasOne('App\CancelHD','sale_id', 'id');
    }
}
