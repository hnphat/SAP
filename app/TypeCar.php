<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeCar extends Model
{
    //
    protected $table = "type_car";
    public function typeCarDetail() {
        return $this->hasMany('App\TypeCarDetail', 'id_type_car', 'id');
    }

    public function reportCar() {
        return $this->hasMany('App\ReportCar', 'dongXe', 'id');
    }
}
