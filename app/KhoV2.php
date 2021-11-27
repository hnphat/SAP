<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhoV2 extends Model
{
    //
     protected $table = "kho_v2";
     public function typeCarDetail() {
         return $this->belongsTo('App\TypeCarDetail', 'id_type_car_detail', 'id');
     }
 
     public function sale() {
         return $this->hasMany('App\Sale', 'id_car_sale', 'id');
     }
 
     public function user() {
         return $this->belongsTo('App\User','id_user_create','id');
     }
}
