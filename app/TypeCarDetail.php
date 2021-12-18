<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeCarDetail extends Model
{
    //
    protected $table = "type_car_detail";

    public function typeCar() {
        return $this->belongsTo('App\TypeCar', 'id_type_car', 'id');
    }

    public function carSale() {
        return $this->hasMany('App\CarSale', 'id_type_car_detail', 'id');
    }
}
