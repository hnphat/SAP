<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BhPkPackage extends Model
{
    //
    protected $table = "bh_pk_package";
//    public function saleOff() {
//        return $this->hasMany('App\SaleOff', 'id_bh_pk_package', 'id');
//    }

    public function sale() {
        return $this->belongsToMany('App\Sale', 'sale_off', 'id_bh_pk_package','id_sale');
    }
}
