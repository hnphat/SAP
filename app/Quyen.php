<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quyen extends Model
{
    //
    protected $table = "quyen";
    public function user() {
        return $this->hasMany('App\User','rule', 'id');
    }
}
