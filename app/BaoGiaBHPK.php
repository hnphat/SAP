<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaoGiaBHPK extends Model
{
   //
   protected $table = "baogia_bhpk";
   public function user() {
      return $this->belongsTo('App\User', 'id_user_create', 'id');
  }
}
