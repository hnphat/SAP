<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeGuest extends Model
{
    //
    protected $table = "type_guest";

    public function guest() {
        return $this->hasMany('App\Guest', 'id_type_guest', 'id');
    }
}
