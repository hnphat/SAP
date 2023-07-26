<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSale extends Model
{
    //
    protected $table = "group_sale";

    public function user() {
        return $this->hasMany('App\User', 'user_id', 'id');
    }
}
